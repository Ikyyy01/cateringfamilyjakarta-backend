<x-guest-layout>
    {{-- Status --}}
    @if(session('status'))
        <div class="alert alert-success rounded-3 mb-4 small d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus autocomplete="username"
                   placeholder="contoh@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <label class="d-flex align-items-center gap-2 small text-muted" style="cursor:pointer">
                <input type="checkbox" name="remember" class="form-check-input m-0"
                       style="width:16px;height:16px;border-color:#ccc">
                Ingat saya
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-auth small">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-auth">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>

        @if(Route::has('register'))
        <div class="divider">atau</div>
        <p class="text-center small text-muted mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="link-auth">Daftar sekarang</a>
        </p>
        @endif
    </form>
</x-guest-layout>
