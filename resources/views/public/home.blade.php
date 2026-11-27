
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PIJAR–SUAR</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <section class="public-hero">

        <p class="eyebrow">PRAKTISI MENGAJAR</p>

        <h1 style="font-size:clamp(42px,7vw,82px);max-width:900px;line-height:.95;margin:20px 0">
            Kolaborasi kampus dan industri yang lebih terarah.
        </h1>

        <p style="max-width:650px;color:#c7dace">
            Kelola pengajuan, praktisi, mitra, jadwal, dan bahan mengajar dalam satu portal.
        </p>

        <a class="btn orange" href="{{ route('login') }}" style="margin-top:24px">
            Masuk Portal
        </a>

    </section>

    <main class="public-main">

        <p class="eyebrow">JADWAL PUBLIK</p>

        <h2 class="title">Kelas praktisi terdekat</h2>

        <div class="grid">

            @forelse ($schedules as $x)

                <article class="panel" style="display:grid;grid-template-columns:140px 1fr auto;align-items:center;gap:20px">

                    <strong>
                        {{ \Carbon\Carbon::parse($x->date)->translatedFormat('d M Y') }}
                    </strong>

                    <div>

                        <span class="badge">PIJAR–SUAR</span>

                        <h3>{{ $x->course_name }}</h3>

                        <p class="muted">
                            {{ $x->study_program }} · {{ $x->location }}
                        </p>

                    </div>

                    <strong>{{ $x->time }}</strong>

                </article>

            @empty

                <p class="muted">
                    Belum ada jadwal publik.
                </p>

            @endforelse

        </div>

    </main>

</body>
</html>
