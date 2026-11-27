@extends('layouts.portal')

@section('title', 'Jadwal Perkuliahan')

@section('content')
    <p class="eyebrow">JADWAL</p>

    <h1 class="title">Jadwal Perkuliahan</h1>

    <div class="grid">
        @forelse($items as $x)
            <article class="panel" style="display:grid;grid-template-columns:130px 1fr auto;align-items:center;gap:20px">
                <strong>{{ \Carbon\Carbon::parse($x->date)->translatedFormat('d M Y') }}</strong>

                <div>
                    <h3>{{ $x->course_name }}</h3>
                    <p class="muted">{{ $x->study_program }} · {{ $x->location }}</p>
                </div>

                <span class="badge">{{ $x->time }}</span>
            </article>
        @empty
            <p>Belum ada jadwal.</p>
        @endforelse
    </div>
@endsection