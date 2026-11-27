
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Masuk · PIJAR–SUAR</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <main class="login-wrap">

        <form class="login-card" method="post" action="{{ route('login.store') }}">

            @csrf

            <p class="eyebrow">PORTAL AKADEMIK</p>

            <h1 class="title">Masuk ke PIJAR–SUAR</h1>

            <p class="muted">
                Gunakan akun internal sesuai peran Anda.
            </p>

            <label>
                <span>Email</span>

                <input
                    class="input"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </label>

            <label style="display:block;margin-top:14px">
                <span>Kata sandi</span>

                <input
                    class="input"
                    type="password"
                    name="password"
                    required
                >
            </label>

            @error('email')
                <p style="color:#b42318">
                    {{ $message }}
                </p>
            @enderror

            <label style="display:flex;gap:8px;margin:15px 0">
                <input
                    type="checkbox"
                    name="remember"
                    style="width:auto"
                >
                Ingat saya
            </label>

            <button class="btn primary" style="width:100%">
                Masuk
            </button>

            <p class="muted" style="font-size:12px;margin-top:18px">
                Demo: superadmin@pijar.demo / Demo123!
            </p>

        </form>

    </main>

</body>
</html>
