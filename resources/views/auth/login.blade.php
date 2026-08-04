@extends('layouts.guest')

@section('content')
<div class="login-card">
    <div class="login-header">
        <i class="fa-solid fa-laptop-code"></i>
        <h4 class="fw-bold m-0">SYSCOMP TECH</h4>
        <p class="text-white-50 small mb-0">Gestión de Inventario y Ventas</p>
    </div>

    <div class="p-4">
        @if(session('info'))
            <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-dark">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', 'admin@syscomp.com') }}" required autofocus placeholder="correo@empresa.com">
                </div>
                @error('email')
                    <span class="text-danger small d-block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold text-dark">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" value="password" required placeholder="••••••••">
                </div>
                @error('password')
                    <span class="text-danger small d-block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-secondary small" for="remember">
                        Recordar sesión
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar Sesión
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center text-muted" style="font-size: 0.82rem;">
            <p class="mb-1"><strong>Cuentas Demo para aprendizaje:</strong></p>
            <span class="badge bg-secondary">Admin: admin@syscomp.com</span>
            <span class="badge bg-secondary">Vendedor: vendedor@syscomp.com</span>
            <span class="badge bg-secondary">Almacén: almacenero@syscomp.com</span>
            <p class="mt-1 mb-0 text-muted">Clave para todos: <code>password</code></p>
        </div>
    </div>
</div>
@endsection
