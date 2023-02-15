@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@extends('layouts.base_layout')

@section('base_layout_content')
    
    @include('layouts.inc.navbar')
    
    <main>
        {{ $slot }}
    </main>

    @include('layouts.inc.footer')

@endsection