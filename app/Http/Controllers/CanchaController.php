<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CanchaController extends Controller
{
    /**
     * Mostrar la lista de canchas disponibles
     */
    public function index()
    {
        $canchas = $this->getDemoCanchas();
        return view('canchas.index', compact('canchas'));
    }

    /**
     * Mostrar los detalles de una cancha específica
     */
    public function show($id)
    {
        $canchas = $this->getDemoCanchas();
        
        // Encontrar la cancha por ID
        $cancha = collect($canchas)->firstWhere('id', $id);
        
        if (!$cancha) {
            abort(404, 'Cancha no encontrada');
        }
        
        // Obtener fecha seleccionada o usar la actual
        $fecha = request('fecha', now()->format('Y-m-d'));
        
        // Simulamos algunas reservas
        $reservations = $this->getDemoReservations($id, $fecha);
        
        return view('canchas.show', compact('cancha', 'reservations', 'fecha'));
    }

    /**
     * Mostrar el formulario de reserva
     */
    public function reservar(Request $request)
    {
        $fecha = $request->input('fecha', now()->format('Y-m-d'));
        $hora_inicio = $request->input('hora_inicio');
        $hora_fin = $request->input('hora_fin');
        $cancha_id = $request->input('cancha_id', 1);
        
        $canchas = $this->getDemoCanchas();
        $cancha = collect($canchas)->firstWhere('id', (string)$cancha_id);
        
        if (!$cancha) {
            abort(404, 'Cancha no encontrada');
        }
        
        return view('reservas.reservas', compact('cancha', 'fecha', 'hora_inicio', 'hora_fin'));
    }

    /**
     * Procesar la reserva
     */
    public function procesarReserva(Request $request)
    {
        // Aquí iría la lógica para guardar la reserva en la base de datos
        
        return redirect()->route('canchas.index')
                         ->with('success', 'Reserva realizada con éxito');
    }

    /**
     * Datos demo de canchas para el prototipo
     */
    private function getDemoCanchas()
    {
        return [
            [
                'id' => '1',
                'name' => 'Cancha El Golazo',
                'address' => 'Av. Principal 123, San Isidro',
                'description' => 'Cancha de césped sintético de última generación, perfecta para partidos amistosos y torneos de alto nivel.',
                'rating' => 4.5,
                'reviewCount' => 45,
                'size' => 'Cancha 7x7',
                'surface' => 'Césped sintético',
                'pricePerHour' => 80,
                'schedule' => 'Lun-Dom: 8:00 - 22:00',
                'phone' => '+51 987 654 321',
                'website' => 'https://www.elgolazo.com',
                'image' => 'https://via.placeholder.com/400x250?text=Cancha+El+Golazo',
                'amenities' => [
                    'Iluminación nocturna', 
                    'Estacionamiento', 
                    'Duchas', 
                    'Camerinos', 
                    'Cafetería'
                ],
            ],
            [
                'id' => '2',
                'name' => 'Complejo Deportivo Campeones',
                'address' => 'Jr. Los Deportistas 456, Miraflores',
                'description' => 'Complejo deportivo profesional con canchas reglamentarias y servicios de primer nivel para equipos competitivos.',
                'rating' => 4,
                'reviewCount' => 32,
                'size' => 'Cancha 11x11',
                'surface' => 'Césped natural',
                'pricePerHour' => 120,
                'schedule' => 'Lun-Sáb: 9:00 - 21:00, Dom: 10:00 - 18:00',
                'phone' => '+51 999 888 777',
                'website' => 'https://www.campeonesfc.com',
                'image' => 'https://via.placeholder.com/400x250?text=Complejo+Deportivo+Campeones',
                'amenities' => [
                    'Iluminación nocturna', 
                    'Estacionamiento amplio', 
                    'Duchas', 
                    'Camerinos', 
                    'Tienda deportiva', 
                    'Bar'
                ],
            ],
            [
                'id' => '3',
                'name' => 'Fulbito San Martín',
                'address' => 'Calle Los Pinos 789, Surco',
                'description' => 'Canchas pequeñas ideales para partidos rápidos de fútbol 5, ambiente familiar y precios accesibles.',
                'rating' => 3.5,
                'reviewCount' => 18,
                'size' => 'Cancha 5x5',
                'surface' => 'Césped sintético',
                'pricePerHour' => 60,
                'schedule' => 'Lun-Dom: 10:00 - 20:00',
                'phone' => '+51 912 345 678',
                'website' => 'https://www.fulbitosanmartin.pe',
                'image' => 'https://via.placeholder.com/400x250?text=Fulbito+San+Martin',
                'amenities' => [
                    'Iluminación nocturna', 
                    'Kiosco', 
                    'Baños'
                ],
            ],
            [
                'id' => '4',
                'name' => 'Club de Fútbol Libertad',
                'address' => 'Av. Libertadores 321, La Molina',
                'description' => 'Club exclusivo con canchas de nivel FIFA, ideal para entrenamiento profesional y eventos deportivos importantes.',
                'rating' => 5,
                'reviewCount' => 62,
                'size' => 'Cancha 11x11',
                'surface' => 'Césped natural profesional',
                'pricePerHour' => 150,
                'schedule' => 'Lun-Dom: 8:00 - 23:00',
                'phone' => '+51 923 456 789',
                'website' => 'https://www.cflibertad.com',
                'image' => 'https://via.placeholder.com/400x250?text=Club+de+Futbol+Libertad',
                'amenities' => [
                    'Iluminación profesional', 
                    'Estacionamiento VIP', 
                    'Duchas de lujo', 
                    'Camerinos privados', 
                    'Restaurante', 
                    'Gimnasio', 
                    'Fisioterapia'
                ],
            ],
            [
                'id' => '5',
                'name' => 'Mini Fútbol El Barrio',
                'address' => 'Jr. Amistad 567, Breña',
                'description' => 'Canchas para fulbito recreativo, perfectas para jugar con amigos o organizar pequeños torneos barriales.',
                'rating' => 3,
                'reviewCount' => 24,
                'size' => 'Cancha 5x5',
                'surface' => 'Césped sintético',
                'pricePerHour' => 50,
                'schedule' => 'Lun-Vie: 15:00 - 22:00, Sáb-Dom: 10:00 - 22:00',
                'phone' => '+51 934 567 890',
                'website' => 'https://www.minifutbolelbarrio.com',
                'image' => 'https://via.placeholder.com/400x250?text=Mini+Futbol+El+Barrio',
                'amenities' => [
                    'Iluminación básica', 
                    'Baños', 
                    'Kiosco'
                ],
            ]
        ];
    }

    /**
     * Datos demo de reservas para una cancha y fecha
     */
    private function getDemoReservations($canchaId, $fecha)
    {
        // Crear algunas reservas de demostración
        $horariosOcupados = [];
        
        // Para la cancha 2 (Complejo Deportivo Campeones) en la fecha actual
        if ($canchaId == '2' && $fecha == now()->format('Y-m-d')) {
            $horariosOcupados = [
                ['start_time' => $fecha . ' 09:00:00', 'end_time' => $fecha . ' 11:00:00'],
                ['start_time' => $fecha . ' 14:00:00', 'end_time' => $fecha . ' 15:00:00'],
                ['start_time' => $fecha . ' 19:00:00', 'end_time' => $fecha . ' 20:00:00'],
            ];
        }
        
        // Para cualquier cancha el 5 de mayo de 2025
        if ($fecha == '2025-05-05') {
            $horariosOcupados = [
                ['start_time' => $fecha . ' 10:00:00', 'end_time' => $fecha . ' 12:00:00'],
                ['start_time' => $fecha . ' 16:00:00', 'end_time' => $fecha . ' 17:00:00'],
            ];
        }
        
        return $horariosOcupados;
    }
}