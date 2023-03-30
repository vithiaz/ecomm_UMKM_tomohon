@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@extends('layouts.base_layout')

@section('base_layout_content')
    
    @include('layouts.inc.navbar')
    
    <div class="app">
        <div class="row-container">
            {{-- Side-menu --}}
            @include('layouts.inc.user-sidemenu')

            {{-- .app-content below --}}
            <div class="app-content">
                {{ $slot }}
            </div>
        </div>
    </div>
    
    @include('layouts.inc.footer')

@endsection

@push('script')
<script>    
    
</script>
@endpush