@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
@endpush

@extends('layouts.base_layout')

@section('base_layout_content')
    
    @include('layouts.inc.navbar')

    <main>
        {{ $slot }}
    </main>

@endsection