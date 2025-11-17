@extends('layouts.master')

@section('title', $title ?? 'Listagem')

@section('content')

    <div class="page-header">
        <h2>{{ $title ?? 'Listagem' }}</h2>

        @if(isset($button_create))
            <a href="{{ $button_create['url'] }}" class="btn btn-primary">
                {{ $button_create['label'] }}
            </a>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            @yield('table')
        </div>
    </div>

@endsection
