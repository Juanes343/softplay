@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>Detalles de la cancha</h4>
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <img src="{{ asset('images/cancha.jpg') }}" class="img-fluid mb-3" alt="Complejo Deportivo Campeones">
                    <h5><strong>{{ $cancha->name ?? 'Complejo Deportivo Campeones' }}</strong></h5>
                    <p><span class="text-warning">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                        <span>({{ $cancha->reviewCount ?? '32' }} reseñas)</span></p>
                    <p><i class="bi bi-geo-alt"></i> {{ $cancha->address ?? 'Jr. Los Deportistas 456, Miraflores' }}</p>
                    <p><i class="bi bi-telephone"></i> {{ $cancha->phone ?? '+51 999 888 777' }}</p>
                    <p><a href="{{ $cancha->website ?? '#' }}" target="_blank">Sitio web</a></p>
                    <p><i class="bi bi-info-circle"></i>
                        {{ $cancha->description ?? 'Complejo deportivo profesional con canchas reglamentarias y servicios de primer nivel para equipos competitivos.' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <h5 class="text-success"><i class="bi bi-star-fill"></i> Servicios</h5>
                        <ul class="list-unstyled">
                            @if (isset($cancha->amenities))
                                @foreach ($cancha->amenities as $amenity)
                                    <li>✔ {{ $amenity }}</li>
                                @endforeach
                            @else
                                <li>✔ Iluminación nocturna</li>
                                <li>✔ Estacionamiento amplio</li>
                                <li>✔ Duchas</li>
                                <li>✔ Camerinos</li>
                                <li>✔ Tienda deportiva</li>
                                <li>✔ Bar</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <!-- Nueva sección para disponibilidad -->
                <div class="border-bottom pb-3 mb-3">
                    <h4>Consulta los Horarios Disponibles para tu reserva</h4>

                    <!-- Selector de fecha y hora -->
                    <div class="form-group mb-3">
                        <input type="datetime-local" id="fechaInput" name="disponibilidad_date" class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div id="calendar" style="display:none;"></div>

                    <div class="mt-3">
                        <h5>Fecha seleccionada:</h5>
                        <p id="fechaSeleccionada" class="fs-5">
                            {{ now()->format('d M. Y') }}
                        </p>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const fechaInput = document.getElementById('fechaInput');
                            const fechaSeleccionada = document.getElementById('fechaSeleccionada');

                            fechaInput.addEventListener('input', function() {
                                const fecha = new Date(this.value);

                                if (!isNaN(fecha)) {
                                    const opciones = {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    };
                                    const fechaFormateada = fecha.toLocaleDateString('es-ES', opciones).replace('.', '');
                                    fechaSeleccionada.textContent = fechaFormateada;
                                }
                            });
                        });
                    </script>

                    <div class="mt-4">
                        <h5 class="fw-bold">Día</h5>
                        <p class="text-warning">Sin disponibilidad</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold">Tarde</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-outline-dark">3:00 - 4:00</a>
                            <a href="#" class="btn btn-outline-dark">4:00 - 5:00</a>
                            <a href="#" class="btn btn-outline-dark">5:00 - 6:00</a>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold">Noche</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-outline-dark">6:00 - 7:00</a>
                            <a href="#" class="btn btn-outline-dark">7:00 - 8:00</a>
                            <a href="#" class="btn btn-outline-dark">8:00 - 9:00</a>
                            <a href="#" class="btn btn-outline-dark">9:00 - 10:00</a>
                            <a href="#" class="btn btn-outline-dark">10:00 - 11:00</a>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('reservar', ['cancha_id' => $cancha->id ?? 2]) }}"
                            class="btn btn-primary">Reservar ahora</a>
                    </div>
                    
                    <!-- Botón de volver -->
                    <div class="d-flex justify-content-start mt-4">
                        <a href="{{ route('canchas.index') }}" class="btn btn-dark">
                            <i class="bi bi-arrow-left-circle"></i> {{ __('Volver') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 400,
                selectable: true,
                select: function(info) {
                    // Actualizar la fecha seleccionada
                    const fecha = new Date(info.start);
                    const options = {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    };
                    const fechaFormateada = fecha.toLocaleDateString('es-ES', options).replace('.', '');

                    // Redireccionar o actualizar la página con la fecha seleccionada
                    window.location.href = '{{ url('/cancha/1') }}?fecha=' + info.startStr;
                },
                events: [
                    @if (isset($reservations))
                        @foreach ($reservations as $reservation)
                            {
                                title: 'Reservado',
                                start: '{{ $reservation->start_time }}',
                                end: '{{ $reservation->end_time }}',
                                color: '#dc3545'
                            },
                        @endforeach
                    @endif {
                        title: 'Disponible',
                        start: '2025-05-06',
                        color: '#198754'
                    }
                ]
            });
            calendar.render();

            // Actualizar la fecha seleccionada cuando cambie el input
            const fechaInput = document.getElementById('fechaInput');
            fechaInput.addEventListener('change', function() {
                // Convertir el valor del input datetime-local a formato de fecha
                const fechaSeleccionada = new Date(this.value);
                const options = {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                };
                const fechaFormateada = fechaSeleccionada.toLocaleDateString('es-ES', options).replace('.',
                    '');

                // Actualizar el texto de la fecha seleccionada
                document.querySelector('.mt-3 .fs-5').textContent = fechaFormateada;

                // Opcional: Recargar los horarios disponibles para la fecha seleccionada
                const fechaISO = fechaSeleccionada.toISOString().split('T')[0];
                window.location.href = '{{ url('/cancha/' . ($cancha->id ?? '1')) }}?fecha=' + fechaISO;
            });
        });
    </script>
@endpush
