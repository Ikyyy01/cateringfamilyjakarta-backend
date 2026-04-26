<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required autofocus autocomplete="name"
                   placeholder="Masukkan nama lengkap">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autocomplete="username"
                   placeholder="contoh@email.com">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- No HP --}}
        <div class="mb-3">
            <label for="phone" class="form-label">No. HP / WhatsApp</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                <input id="phone" type="text" name="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" required
                       placeholder="08123456789">
            </div>
            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label for="address" class="form-label">Alamat <span class="text-muted small fw-400">(opsional)</span></label>
            <textarea id="address" name="address" rows="2"
                      class="form-control @error('address') is-invalid @enderror"
                      placeholder="Jalan, kelurahan, kota…">{{ old('address') }}</textarea>
            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password"
                   placeholder="Minimal 8 karakter">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   required autocomplete="new-password"
                   placeholder="Ulangi password">
            @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn-auth">
            <i class="bi bi-person-plus me-2"></i>Buat Akun
        </button>

        <div class="divider">atau</div>
        <p class="text-center small text-muted mb-0">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="link-auth">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>
