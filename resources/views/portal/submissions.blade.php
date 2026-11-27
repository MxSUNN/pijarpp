@extends('layouts.portal')

@section('title', 'Monitoring Pengajuan')

@section('content')
    <p class="eyebrow">PUSAT DATA PENGAJUAN</p>

    <h1 class="title">Monitoring Pengajuan</h1>

    <form class="toolbar">
        <input
            class="input"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari mata kuliah atau nomor"
        >

        <select name="status">
            <option value="">Semua status</option>
            @foreach(['submitted', 'under_review', 'revision_required', 'rejected', 'approved'] as $s)
                <option @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>

        <button class="btn primary">Filter</button>
    </form>

    <section class="panel">
        @include('portal.partials.submission-table', ['items' => $items])
    </section>

    {{ $items->links() }}
@endsection