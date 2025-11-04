{{-- 
    SIAKAD Performance Optimization Guide
    =====================================
    
    Conditional Script Loading Helper
    File: resources/views/admin/layouts/conditional-scripts.blade.php
    
    Gunakan @push('conditional-scripts') di view yang memerlukan script khusus
    
    Example Usage di views:
    
    @push('conditional-scripts')
        @include('admin.layouts.conditional-scripts', ['scripts' => ['charts', 'maps']])
    @endpush
--}}

@if(isset($scripts))
    @foreach($scripts as $script)
        @if($script === 'charts')
            <!-- Chart.js - Only for dashboard/analytics pages -->
            <script src="{{ asset('template/kaiadmin/js/plugin/chart.js/chart.min.js') }}"></script>
            <script src="{{ asset('template/kaiadmin/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
            <script src="{{ asset('template/kaiadmin/js/plugin/chart-circle/circles.min.js') }}"></script>
        @endif
        
        @if($script === 'maps')
            <!-- Vector Maps - Only for location-based pages -->
            <script src="{{ asset('template/kaiadmin/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
            <script src="{{ asset('template/kaiadmin/js/plugin/jsvectormap/world.js') }}"></script>
        @endif
        
        @if($script === 'notifications')
            <!-- Bootstrap Notify - Alternative to SweetAlert -->
            <script src="{{ asset('template/kaiadmin/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
        @endif
        
        @if($script === 'demo')
            <!-- Demo Scripts - Only for development -->
            <script src="{{ asset('template/kaiadmin/js/setting-demo.js') }}"></script>
            <script src="{{ asset('template/kaiadmin/js/demo.js') }}"></script>
        @endif
        
        @if($script === 'file-upload')
            <!-- File Upload Plugin - Only for forms with file uploads -->
            <script src="{{ asset('template/kaiadmin/js/plugin/dropzone/dropzone.min.js') }}"></script>
        @endif
        
        @if($script === 'date-picker')
            <!-- Date Picker - Only for forms with date inputs -->
            <script src="{{ asset('template/kaiadmin/js/plugin/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        @endif
        
        @if($script === 'editor')
            <!-- Rich Text Editor - Only for content forms -->
            <script src="{{ asset('template/kaiadmin/js/plugin/summernote/summernote-lite.min.js') }}"></script>
        @endif
    @endforeach
@endif