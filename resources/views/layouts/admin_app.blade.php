@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin_app.css') }}">
@endpush


@extends('layouts.base_layout')

@section('base_layout_content')
    
    <main class="admin-layout">
        @include('layouts.inc.admin-sidebar')

        <div class="layout-content">
            @include('layouts.inc.admin-navbar')
            {{ $slot }}
        </div>
    </main>

@endsection

@push('script')
<script>    
    
</script>
@endpush