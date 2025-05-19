@extends('layouts.app')

@section('content')
<!-- Navbar Superior -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>
            PANEL DE CONTROL
        </span>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Dropdown de usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-item m-0 p-0">
                                @csrf
                                <button type="submit" class="btn btn-link dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <!-- Tarjetas del Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm h-100">
                <div class="card-body position-relative">
                    <h5 class="card-title">Gestion de usuarios</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('register') }}" class="btn btn-sm btn-light text-dark">
                            <i class="bi bi-person-plus me-1"></i> Crear Usuario
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-light text-dark">
                            <i class="bi bi-person-gear me-1"></i> Administrar Usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body position-relative">
                    <h5 class="card-title">Busqueda de Canchas</h5>
                    <a href="{{ route('canchas.index') }}" class="stretched-link text-white">Más info <i class="bi bi-arrow-right"></i></a>
                    <i class="bi bi-check-circle-fill position-absolute bottom-0 end-0 opacity-25 m-3 fs-1"></i>
                </div>
            </div>
        </div>      
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm h-100">
                <div class="card-body position-relative">
                    <h5 class="card-title">Reserva</h5>
                    <a href="{{ route('reservar') }}" class="stretched-link text-white">Más info <i class="bi bi-arrow-right"></i></a>
                    <i class="bi bi-check-circle-fill position-absolute bottom-0 end-0 opacity-25 m-3 fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm h-100">
                <div class="card-body position-relative">
                    <h5 class="card-title">Clientes</h5>
                    <a href="#" class="stretched-link text-white">Más info <i class="bi bi-arrow-right"></i></a>
                    <i class="bi bi-check-circle-fill position-absolute bottom-0 end-0 opacity-25 m-3 fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de barras simple (placeholder) -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-light">
            <strong>Ganancias alquiler por mes</strong>
        </div>
        <div class="card-body bg-light">
            <div class="d-flex align-items-end" style="height: 200px;">
                <div class="bg-info me-3" style="width: 40px; height: 50%;"></div>
                <div class="bg-info me-3" style="width: 40px; height: 75%;"></div>
                <div class="bg-info me-3" style="width: 40px; height: 60%;"></div>
                <div class="bg-info me-3" style="width: 40px; height: 85%;"></div>
            </div>
        </div>
    </div>

    <!-- Botón de Cerrar Sesión (opcional si se usa el dropdown) -->
    {{-- <div class="text-center mb-4">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-lg shadow-sm">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
            </button>
        </form>
    </div> --}}
</div>
@endsection