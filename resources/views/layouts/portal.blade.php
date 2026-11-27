
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>@yield('title','PIJAR–SUAR')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body>

    <div class="shell">

        {{-- SIDEBAR --}}
        <aside class="sidebar">

            <div class="brand">
                PIJAR<span>–SUAR</span>
            </div>

            <p style="color:#a9c4b4;font-size:12px">
                Sistem Informasi Praktisi Mengajar
            </p>

            <nav class="nav">

                {{-- Dashboard --}}
                <a
                    class="{{request()->routeIs('portal.dashboard')?'active':''}}"
                    href="{{route('portal.dashboard')}}"
                >
                    Dashboard
                </a>

                {{-- Monitoring Pengajuan --}}
                <a
                    class="{{request()->routeIs('portal.submissions*')?'active':''}}"
                    href="{{route('portal.submissions')}}"
                >
                    Monitoring Pengajuan
                </a>

                {{-- Menu Kaprodi --}}
                @if(auth()->user()->role==='kaprodi')
                    <a href="{{route('portal.submissions.create')}}">
                        Buat Pengajuan
                    </a>
                @endif

                {{-- Menu Akademik --}}
                <a href="{{route('portal.schedules')}}">
                    Jadwal Perkuliahan
                </a>

                <a href="{{route('portal.materials')}}">
                    Pelaporan Bahan Mengajar
                </a>

                <a href="{{route('portal.documents')}}">
                    Dokumen
                </a>

                {{-- Super Admin dan Admin PIJAR-SUAR --}}
                @if(in_array(auth()->user()->role,['super_admin','admin_pijar_suar']))
                    <a href="{{route('portal.directory','lecturers')}}">
                        Data Dosen
                    </a>

                    <a href="{{route('portal.directory','practitioners')}}">
                        Data Praktisi
                    </a>
                @endif

                {{-- Super Admin dan Admin KERMA --}}
                @if(in_array(auth()->user()->role,['super_admin','admin_kerma']))
                    <a href="{{route('portal.directory','partners')}}">
                        Data Mitra
                    </a>
                @endif

                {{-- Khusus Super Admin --}}
                @if(auth()->user()->role==='super_admin')
                    <a href="{{route('portal.administration','users')}}">
                        Pengguna & Role
                    </a>

                    <a href="{{route('portal.administration','master')}}">
                        Data Master
                    </a>

                    <a href="{{route('portal.administration','recap')}}">
                        Rekapitulasi
                    </a>

                    <a href="{{route('portal.administration','audit')}}">
                        Audit Log
                    </a>
                @endif

            </nav>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="main">

            {{-- TOPBAR --}}
            <header class="topbar">

                <button class="btn secondary mobile" data-menu>
                    Menu
                </button>

                <div>
                    <strong>
                        {{auth()->user()->name}}
                    </strong>

                    <small class="muted" style="display:block">
                        {{str_replace('_',' ',auth()->user()->role)}}
                    </small>
                </div>

                {{-- Logout --}}
                <form method="post" action="{{route('logout')}}">
                    @csrf

                    <button class="btn secondary">
                        Keluar
                    </button>
                </form>

            </header>

            {{-- PAGE CONTENT --}}
            <div class="content">

                @if(session('success'))
                    <div class="flash">
                        {{session('success')}}
                    </div>
                @endif

                @yield('content')

            </div>

        </main>

    </div>

</body>

</html>
