@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 theme-transition">
                <div class="card-header bg-primary text-white text-center position-relative p-4">
                    <h2 class="mb-0">
                        <i class="bi bi-lock-fill me-2"></i>{{ __('Iniciar Sesión') }}
                    </h2>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-fill me-2 text-primary"></i>{{ __('Correo Electrónico') }}
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-at"></i></span>
                                <input 
                                    id="email" 
                                    type="email" 
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="email" 
                                    autofocus
                                    placeholder="{{ __('Ingrese su correo electrónico') }}"
                                >
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-shield-lock-fill me-2 text-primary"></i>{{ __('Contraseña') }}
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input 
                                    id="password" 
                                    type="password" 
                                    class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    placeholder="{{ __('Ingrese su contraseña') }}"
                                >
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input 
                                type="checkbox" 
                                class="form-check-input" 
                                id="remember" 
                                name="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                {{ __('Recordarme') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Iniciar Sesión') }}
                            </button>
                        <br>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Registrarse') }}
                            </a>
                        </div>                        
                    </form>

                    <div class="text-center mt-3">
                        <a href="#" class="text-muted small">
                            <i class="bi bi-question-circle-fill me-1"></i>{{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    </div>
                </div>

<!-- 
    <div class="card-footer text-center bg-light">
        <p class="mb-0 small text-muted">
            {{ __('¿No tienes una cuenta?') }} 
            <a href="#" class="text-primary">
                {{ __('Regístrate') }}
            </a>
        </p>
    </div>
-->

<!-- 
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="bi bi-shield-lock me-1"></i>
                    {{ __('Tus datos están protegidos con cifrado SSL') }}
                </small>
            </div>
        </div>
    </div>
</div>
-->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const body = document.body;

    // Password show/hide toggle
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye-fill');
            this.querySelector('i').classList.toggle('bi-eye-slash-fill');
        });
    });

    // Theme toggle
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
            localStorage.setItem('theme', 'dark');
        } else {
            themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
            localStorage.setItem('theme', 'light');
        }
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
    }
});
</script>
@endpush

@push('styles')
<style>
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --light-bg: #f8f9fa;
    --dark-bg: #212529;
    --text-light: #ffffff;
    --text-dark: #212529;
}

body {
    background-color: var(--light-bg);
    transition: background-color 0.3s, color 0.3s;
}

body.dark-mode {
    background-color: var(--dark-bg);
    color: var(--text-light);
}

.theme-transition {
    transition: all 0.3s ease;
}

/* Dark Mode Styles */
body.dark-mode .card {
    background-color: #2c3034;
    border-color: #495057;
}

body.dark-mode .card-header {
    background-color: #495057 !important;
}

body.dark-mode .form-control {
    background-color: #495057;
    border-color: #6c757d;
    color: var(--text-light);
}

body.dark-mode .form-control:focus {
    background-color: #6c757d;
    border-color: var(--primary-color);
    color: var(--text-light);
}

body.dark-mode .btn-outline-secondary {
    color: var(--text-light);
    border-color: #6c757d;
}

body.dark-mode .form-check-input {
    background-color: #495057;
    border-color: #6c757d;
}

body.dark-mode .card-footer {
    background-color: #343a40 !important;
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    .card {
        margin: 0 15px;
    }
}
</style>
@endpush