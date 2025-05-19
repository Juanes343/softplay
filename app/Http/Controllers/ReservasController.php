<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservasController extends Controller
{
    public function mostrarDetalle()
    {
        return view('reservas.reservas'); // Requiere que la vista esté en resources/views/reservas/reservas.blade.php
    }
}

