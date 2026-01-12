<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-SIGNATURE');
        $timestamp = $request->header('X-TIMESTAMP');
        
        // 1. Validasi keberadaan header
        if (!$signature || !$timestamp) {
            Log::warning('Webhook rejected: Missing headers', [
                'headers' => $request->headers->all()
            ]);
            return response()->json(['message' => 'Missing signature headers'], 401);
        }

        // 2. Validasi Timestamp (Anti-replay attack) - toleransi 5 menit
        if (abs(time() - (int)$timestamp) > 300) {
             Log::warning('Webhook rejected: Timestamp expired', [
                'timestamp' => $timestamp,
                'server_time' => time()
            ]);
            return response()->json(['message' => 'Request expired'], 401);
        }

        // 3. Ambil Secret Key
        $secret = config('services.internal_api.secret'); 
        
        if (!$secret) {
            Log::error('Webhook error: INTERNAL_API_SECRET not configured');
            return response()->json(['message' => 'Server configuration error'], 500);
        }

        // 4. Reconstruct String to Sign
        $method = $request->method();
        $path   = $request->getPathInfo();
        
        // Payload harus diambil exact seperti pengirim mengirimnya (raw body vs json encode)
        // Di siakad-keu: 
        // GET: json_encode($request->query())
        // POST/PUT: json_encode($request->except(['_token', 'bukti_pembayaran']))
        
        if ($method === 'GET') {
             $bodyForSign = json_encode($request->query());
        } else {
             $bodyForSign = json_encode($request->except(['_token', 'bukti_pembayaran', 'file']));
        }

        $stringToVerify = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";

        // 5. Hitung HMAC
        $expectedSignature = hash_hmac('sha256', $stringToVerify, $secret);

        // 6. Bandingkan Signature
        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Webhook rejected: Invalid signature', [
                'expected' => $expectedSignature,
                'received' => $signature,
                'stringToVerify' => $stringToVerify
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
