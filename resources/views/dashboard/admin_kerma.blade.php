
@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')

    <p class="eyebrow">RINGKASAN PORTAL</p>

    <h1 class="title">Dashboard</h1>

    <p class="muted">
        Informasi sesuai cakupan akses {{ auth()->user()->name }}.
    </p>

    <div class="grid stats">
        @foreach ($stats as $label => $value)
            <article class="card">
                <span class="muted">{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </article>
        @endforeach
    </div>

    <section class="panel">
        <h2>Pengajuan terbaru</h2>

        @include('portal.partials.submission-table', [
            'items' => $submissions
        ])
    </section>

@endsection
