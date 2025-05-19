@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4"><i class="bi bi-people-fill"></i> Listado de Usuarios</h1>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Mensaje de error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabla de usuarios -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col">ID</th>
                   <!-- <th scope="col">Login</th> -->
                    <th scope="col">Nombre de Usuario</th>
                    <th scope="col">Correo</th>
                    <!--<th scope="col" class="text-center">Empresa</th>-->
                    <th scope="col" class="text-center">Acción</th>
                    <th scope="col" class="text-center">Eventos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                     <!--   <td>{{ $user->login }}</td>-->
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                       <!-- <td class="text-center">Empresa Ejemplo</td>-->
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.change_password', $user->id) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-key"></i> Cambiar Contraseña
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botón de volver -->
    <div class="d-flex justify-content-start mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-dark">
            <i class="bi bi-arrow-left-circle"></i> {{ __('Volver') }}
        </a>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-warning {
        color: #000;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .alert {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection