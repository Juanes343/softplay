<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Canchas de Fútbol</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .container-fluid {
            padding: 0;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
            z-index: 1;
        }

        .sidebar {
            height: calc(100vh - 56px);
            overflow-y: auto;
            background-color: white;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .field-card {
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .field-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .field-header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
        }

        .field-body {
            padding: 15px;
        }

        .filter-section {
            background-color: #f1f8e9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .rating {
            color: #FFC107;
        }

        .navbar {
            background-color: #388E3C !important;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .field-detail-modal .modal-header {
            background-color: #4CAF50;
            color: white;
        }

        .search-box {
            margin-bottom: 15px;
        }

        .search-box .input-group {
            border-radius: 20px;
            overflow: hidden;
        }

        .search-box .form-control {
            border-right: none;
        }

        .search-box .btn {
            border-left: none;
            background-color: #fff;
        }

        .search-box .btn:hover {
            background-color: #f8f9fa;
        }

        .highlight {
            background-color: #ffff99;
            padding: 0 2px;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .sidebar {
                height: 40vh;
                order: 2;
            }
            #map {
                height: 60vh;
                order: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-futbol me-2"></i>Buscador de Canchas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home me-1"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-info-circle me-1"></i> Acerca de</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-envelope me-1"></i> Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar with filters and field list -->
            <div class="col-md-4 col-lg-3 sidebar">
                <div class="filter-section">
                    <h5><i class="fas fa-filter me-2"></i>Filtros</h5>
                    <!-- New search box for name/description -->
                    <div class="search-box mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchField" placeholder="Buscar por nombre o descripción">
                            <button class="btn" type="button" id="searchBtn">
                                <i class="fas fa-search text-success"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="distanceRange" class="form-label">Distancia máxima: <span id="distanceValue">5</span> km</label>
                        <input type="range" class="form-range" id="distanceRange" min="1" max="20" value="5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tamaño de cancha:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="5" id="field5" checked>
                            <label class="form-check-label" for="field5">5 vs 5</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="7" id="field7" checked>
                            <label class="form-check-label" for="field7">7 vs 7</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="11" id="field11" checked>
                            <label class="form-check-label" for="field11">11 vs 11</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Calificación mínima:</label>
                        <select class="form-select" id="minRating">
                            <option value="1">1 estrella o más</option>
                            <option value="2">2 estrellas o más</option>
                            <option value="3" selected>3 estrellas o más</option>
                            <option value="4">4 estrellas o más</option>
                            <option value="5">5 estrellas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Servicios adicionales:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="parking" id="parking">
                            <label class="form-check-label" for="parking">Estacionamiento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="shower" id="shower">
                            <label class="form-check-label" for="shower">Duchas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="illumination" id="illumination">
                            <label class="form-check-label" for="illumination">Iluminación nocturna</label>
                        </div>
                    </div>
                    <button id="applyFilters" class="btn btn-success w-100">
                        <i class="fas fa-search me-2"></i>Aplicar filtros
                    </button>
                </div>

                <h5><i class="fas fa-list me-2"></i>Canchas cercanas</h5>
                <div id="fieldsList">
                    <!-- Fields will be loaded here -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Buscando canchas cercanas...</p>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="col-md-8 col-lg-9 p-0">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <h5 class="mt-3">Cargando mapa y buscando canchas cercanas...</h5>
            <p>Por favor, permite el acceso a tu ubicación</p>
        </div>
    </div>

    <!-- Field Detail Modal -->
    <div class="modal fade field-detail-modal" id="fieldDetailModal" tabindex="-1" aria-labelledby="fieldDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fieldDetailModalLabel">Detalles de la cancha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fieldDetailContent">
                    <!-- Field details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('reservar') }}" class="btn btn-success">
                        Reservar ahora
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Geolocation Error Modal -->
    <div class="modal fade" id="geolocationErrorModal" tabindex="-1" aria-labelledby="geolocationErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="geolocationErrorModalLabel">Error de ubicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>No se pudo acceder a tu ubicación. Para obtener mejores resultados, por favor permite el acceso a tu ubicación y recarga la página.</p>
                    <p>Mientras tanto, mostraremos canchas en una ubicación predeterminada.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- No Results Modal -->
    <div class="modal fade" id="noResultsModal" tabindex="-1" aria-labelledby="noResultsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="noResultsModalLabel">Sin resultados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>No se encontraron canchas que coincidan con tu búsqueda. Por favor, prueba con otros términos o amplía tus criterios de búsqueda.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default coordinates (will be replaced with user's location if available)
            let userLat = -12.046374;
            let userLng = -77.042793;
            let map = null;
            let userMarker = null;
            let fieldMarkers = [];
            let allFields = []; // Store all fields for filtering

            // Initialize distance range value display
            document.getElementById('distanceRange').addEventListener('input', function() {
                document.getElementById('distanceValue').textContent = this.value;
            });

            // Initialize map with user location
            function initMap() {
                // Get user's geolocation
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            userLat = position.coords.latitude;
                            userLng = position.coords.longitude;
                            setupMap();
                            loadNearbyFields();
                            hideLoadingOverlay();
                        },
                        function(error) {
                            console.error("Error getting geolocation:", error);
                            setupMap();
                            loadNearbyFields();
                            hideLoadingOverlay();
                            showGeolocationError();
                        }
                    );
                } else {
                    setupMap();
                    loadNearbyFields();
                    hideLoadingOverlay();
                    showGeolocationError();
                }
            }

            function setupMap() {
                // Create map centered at user's location
                map = L.map('map').setView([userLat, userLng], 14);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Add user marker
                const userIcon = L.icon({
                    iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                });

                userMarker = L.marker([userLat, userLng], {icon: userIcon}).addTo(map);
                userMarker.bindPopup('Tu ubicación actual').openPopup();
            }

            function loadNearbyFields() {
                // This is where the API call would go in the real app
                // For prototype, we'll use fake data
                
                setTimeout(() => {
                    // After "loading", display some demo fields
                    allFields = getDemoFields(); // Store all fields
                    displayFields(allFields);
                }, 1500);
            }

            function displayFields(fields) {
                const fieldsListContainer = document.getElementById('fieldsList');
                fieldsListContainer.innerHTML = '';

                // Clear existing markers
                fieldMarkers.forEach(marker => {
                    map.removeLayer(marker);
                });
                fieldMarkers = [];

                if (fields.length === 0) {
                    fieldsListContainer.innerHTML = '<div class="alert alert-info">No se encontraron canchas que coincidan con los filtros.</div>';
                    return;
                }

                // Get search term for highlighting
                const searchTerm = document.getElementById('searchField').value.trim().toLowerCase();

                fields.forEach((field, index) => {
                    // Prepare field name and description with highlighting if needed
                    let displayName = field.name;
                    let description = field.address;

                    if (searchTerm !== '') {
                        // Highlight matching text in name
                        if (field.name.toLowerCase().includes(searchTerm)) {
                            displayName = highlightText(field.name, searchTerm);
                        }
                        
                        // Highlight matching text in address
                        if (field.address.toLowerCase().includes(searchTerm)) {
                            description = highlightText(field.address, searchTerm);
                        }
                    }

                    // Create field card
                    const fieldCard = document.createElement('div');
                    fieldCard.className = 'field-card';
                    fieldCard.innerHTML = `
                        <div class="field-header d-flex justify-content-between">
                            <h6 class="mb-0">${displayName}</h6>
                            <div class="rating">
                                ${getRatingStars(field.rating)}
                            </div>
                        </div>
                        <div class="field-body">
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>${description}</p>
                            <p class="mb-1"><i class="fas fa-ruler me-2"></i>${field.size}</p>
                            <p class="mb-2"><i class="fas fa-walking me-2"></i>${field.distance} km de distancia</p>
                            <button class="btn btn-sm btn-outline-success view-details" data-field-id="${field.id}">
                                <i class="fas fa-info-circle me-1"></i>Ver detalles
                            </button>
                        </div>
                    `;
                    
                    fieldsListContainer.appendChild(fieldCard);

                    // Add marker to map
                    const fieldIcon = L.icon({
                        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });

                    const marker = L.marker([field.lat, field.lng], {icon: fieldIcon}).addTo(map);
                    marker.bindPopup(`
                        <strong>${field.name}</strong><br>
                        ${field.address}<br>
                        <small>${field.distance} km de distancia</small><br>
                        <button class="btn btn-sm btn-success mt-2 view-details-map" data-field-id="${field.id}">Ver detalles</button>
                    `);
                    fieldMarkers.push(marker);

                    // Add event listener to the marker popup
                    marker.on('popupopen', function() {
                        document.querySelectorAll('.view-details-map').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const fieldId = this.getAttribute('data-field-id');
                                showFieldDetails(fieldId);
                            });
                        });
                    });
                });

                // Add event listeners to view details buttons
                document.querySelectorAll('.view-details').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const fieldId = this.getAttribute('data-field-id');
                        showFieldDetails(fieldId);
                    });
                });
            }

            // Helper function to highlight search text
            function highlightText(text, searchTerm) {
                if (!searchTerm) return text;
                
                const regex = new RegExp('(' + searchTerm + ')', 'gi');
                return text.replace(regex, '<span class="highlight">$1</span>');
            }

            function showFieldDetails(fieldId) {
                // Find the field in our demo data
                const field = getDemoFields().find(f => f.id === fieldId);
                
                if (!field) return;
                
                // Populate the modal with field details
                const modalContent = document.getElementById('fieldDetailContent');
                
                // Get search term for highlighting
                const searchTerm = document.getElementById('searchField').value.trim().toLowerCase();
                
                // Prepare field name and description with highlighting if needed
                let displayName = field.name;
                let description = field.description || '';
                
                if (searchTerm !== '') {
                    // Highlight matching text in name
                    if (field.name.toLowerCase().includes(searchTerm)) {
                        displayName = highlightText(field.name, searchTerm);
                    }
                    
                    // Highlight matching text in description
                    if (description && description.toLowerCase().includes(searchTerm)) {
                        description = highlightText(description, searchTerm);
                    }
                }
                
                modalContent.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <img src="${field.image}" class="img-fluid rounded mb-3" alt="${field.name}">
                            <h5>${displayName}</h5>
                            <div class="rating mb-2">
                                ${getRatingStars(field.rating)} (${field.reviewCount} reseñas)
                            </div>
                            <p><i class="fas fa-map-marker-alt me-2"></i>${field.address}</p>
                            <p><i class="fas fa-phone me-2"></i>${field.phone}</p>
                            <p><i class="fas fa-globe me-2"></i><a href="${field.website}" target="_blank">Sitio web</a></p>
                            ${description ? `<p><i class="fas fa-info-circle me-2"></i>${description}</p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-info-circle me-2"></i>Información
                                </div>
                                <div class="card-body">
                                    <p><strong>Tipo de superficie:</strong> ${field.surface}</p>
                                    <p><strong>Tamaño:</strong> ${field.size}</p>
                                    <p><strong>Precio por hora:</strong> $${field.pricePerHour}</p>
                                    <p><strong>Horario:</strong> ${field.schedule}</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-star me-2"></i>Servicios
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        ${field.amenities.map(amenity => `
                                            <li class="list-group-item">
                                                <i class="fas fa-check-circle text-success me-2"></i>${amenity}
                                            </li>
                                        `).join('')}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Reseñas recientes</h5>
                            <hr>
                            ${field.reviews.map(review => `
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h6>${review.user}</h6>
                                            <div class="rating">
                                                ${getRatingStars(review.rating)}
                                            </div>
                                        </div>
                                        <p class="mb-0">${review.comment}</p>
                                        <small class="text-muted">${review.date}</small>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                
                // Show the modal
                const fieldDetailModal = new bootstrap.Modal(document.getElementById('fieldDetailModal'));
                fieldDetailModal.show();
            }

            function getRatingStars(rating) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        stars += '<i class="fas fa-star"></i>';
                    } else if (i - 0.5 <= rating) {
                        stars += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        stars += '<i class="far fa-star"></i>';
                    }
                }
                return stars;
            }

            function hideLoadingOverlay() {
                const overlay = document.getElementById('loadingOverlay');
                overlay.style.display = 'none';
            }

            function showGeolocationError() {
                const errorModal = new bootstrap.Modal(document.getElementById('geolocationErrorModal'));
                errorModal.show();
            }

            function getDemoFields() {
                // This is demo data for prototype purposes with descriptions added
                return [
                    {
                        id: '1',
                        name: 'Cancha El Golazo',
                        address: 'Av. Principal 123, San Isidro',
                        description: 'Cancha de césped sintético de última generación, perfecta para partidos amistosos y torneos de alto nivel.',
                        lat: userLat + 0.01,
                        lng: userLng + 0.005,
                        rating: 4.5,
                        reviewCount: 45,
                        distance: 1.2,
                        size: 'Cancha 7x7',
                        surface: 'Césped sintético',
                        pricePerHour: 80,
                        schedule: 'Lun-Dom: 8:00 - 22:00',
                        phone: '+51 987 654 321',
                        website: 'https://www.elgolazo.com',
                        image: 'https://via.placeholder.com/400x250?text=Cancha+El+Golazo',
                        amenities: [
                            'Iluminación nocturna', 
                            'Estacionamiento', 
                            'Duchas', 
                            'Camerinos', 
                            'Cafetería'
                        ],
                        reviews: [
                            {
                                user: 'Juan Pérez',
                                rating: 5,
                                comment: 'Excelente cancha, muy bien mantenida y el personal es muy amable.',
                                date: '15/10/2023'
                            },
                            {
                                user: 'Carlos Rodríguez',
                                rating: 4,
                                comment: 'Buena cancha, pero a veces está muy concurrida.',
                                date: '05/10/2023'
                            }
                        ]
                    },
                    {
                        id: '2',
                        name: 'Complejo Deportivo Campeones',
                        address: 'Jr. Los Deportistas 456, Miraflores',
                        description: 'Complejo deportivo profesional con canchas reglamentarias y servicios de primer nivel para equipos competitivos.',
                        lat: userLat - 0.008,
                        lng: userLng + 0.002,
                        rating: 4,
                        reviewCount: 32,
                        distance: 1.8,
                        size: 'Cancha 11x11',
                        surface: 'Césped natural',
                        pricePerHour: 120,
                        schedule: 'Lun-Sáb: 9:00 - 21:00, Dom: 10:00 - 18:00',
                        phone: '+51 999 888 777',
                        website: 'https://www.campeonesfc.com',
                        image: 'https://via.placeholder.com/400x250?text=Complejo+Deportivo+Campeones',
                        amenities: [
                            'Iluminación nocturna', 
                            'Estacionamiento amplio', 
                            'Duchas', 
                            'Camerinos', 
                            'Tienda deportiva', 
                            'Bar'
                        ],
                        reviews: [
                            {
                                user: 'Ana Gómez',
                                rating: 4,
                                comment: 'Buenas instalaciones y buena ubicación.',
                                date: '20/09/2023'
                            },
                            {
                                user: 'Roberto Sánchez',
                                rating: 5,
                                comment: 'El mejor complejo de la zona, lo recomiendo.',
                                date: '01/09/2023'
                            }
                        ]
                    },
                    {
                        id: '3',
                        name: 'Fulbito San Martín',
                        address: 'Calle Los Pinos 789, Surco',
                        description: 'Canchas pequeñas ideales para partidos rápidos de fútbol 5, ambiente familiar y precios accesibles.',
                        lat: userLat + 0.005,
                        lng: userLng - 0.01,
                        rating: 3.5,
                        reviewCount: 18,
                        distance: 2.5,
                        size: 'Cancha 5x5',
                        surface: 'Césped sintético',
                        pricePerHour: 60,
                        schedule: 'Lun-Dom: 10:00 - 20:00',
                        phone: '+51 912 345 678',
                        website: 'https://www.fulbitosanmartin.pe',
                        image: 'https://via.placeholder.com/400x250?text=Fulbito+San+Martin',
                        amenities: [
                            'Iluminación nocturna', 
                            'Kiosco', 
                            'Baños'
                        ],
                        reviews: [
                            {
                                user: 'Miguel Torres',
                                rating: 3,
                                comment: 'Cancha decente pero los camerinos necesitan mantenimiento.',
                                date: '25/08/2023'
                            },
                            {
                                user: 'Pedro Díaz',
                                rating: 4,
                                comment: 'Buena relación calidad-precio. Recomendado.',
                                date: '15/08/2023'
                            }
                        ]
                    },
                    {
                        id: '4',
                        name: 'Club de Fútbol Libertad',
                        address: 'Av. Libertadores 321, La Molina',
                        description: 'Club exclusivo con canchas de nivel FIFA, ideal para entrenamiento profesional y eventos deportivos importantes.',
                        lat: userLat - 0.012,
                        lng: userLng - 0.008,
                        rating: 5,
                        reviewCount: 62,
                        distance: 3.2,
                        size: 'Cancha 11x11',
                        surface: 'Césped natural profesional',
                        pricePerHour: 150,
                        schedule: 'Lun-Dom: 8:00 - 23:00',
                        phone: '+51 923 456 789',
                        website: 'https://www.cflibertad.com',
                        image: 'https://via.placeholder.com/400x250?text=Club+de+Futbol+Libertad',
                        amenities: [
                            'Iluminación profesional', 
                            'Estacionamiento VIP', 
                            'Duchas de lujo', 
                            'Camerinos privados', 
                            'Restaurante', 
                            'Gimnasio', 
                            'Fisioterapia'
                        ],
                        reviews: [
                            {
                                user: 'Luis Mendoza',
                                rating: 5,
                                comment: 'Instalaciones de primera categoría. Un lujo jugar aquí.',
                                date: '30/09/2023'
                            },
                            {
                                user: 'Javier Castillo',
                                rating: 5,
                                comment: 'La mejor cancha de Lima sin duda. Vale cada centavo.',
                                date: '22/09/2023'
                            }
                        ]
                    },
                    {
                        id: '5',
                        name: 'Mini Fútbol El Barrio',
                        address: 'Jr. Amistad 567, Breña',
                        description: 'Canchas para fulbito recreativo, perfectas para jugar con amigos o organizar pequeños torneos barriales.',
                        lat: userLat + 0.015,
                        lng: userLng + 0.012,
                        rating: 3,
                        reviewCount: 24,
                        distance: 4.1,
                        size: 'Cancha 5x5',
                        surface: 'Césped sintético',
                        pricePerHour: 50,
                        schedule: 'Lun-Vie: 15:00 - 22:00, Sáb-Dom: 10:00 - 22:00',
                        phone: '+51 934 567 890',
                        website: 'https://www.minifutbolelbarrio.com',
                        image: 'https://via.placeholder.com/400x250?text=Mini+Futbol+El+Barrio',
                        amenities: [
                            'Iluminación básica', 
                            'Baños', 
                            'Kiosco'
                        ],
                        reviews: [
                            {
                                user: 'Fernando López',
                                rating: 3,
                                comment: 'Buena para partidos informales. Precio accesible.',
                                date: '10/08/2023'
                            },
                            {
                                user: 'Martín Silva',
                                rating: 2,
                                comment: 'El césped está algo desgastado, pero la ubicación es buena.',
                                date: '02/08/2023'
                            }
                        ]
                    }
                ];
            }

            // Search functionality
            function searchFields() {
                const searchTerm = document.getElementById('searchField').value.trim().toLowerCase();
                
                if (searchTerm === '') {
                    // If search is empty, show all fields (possibly filtered by other criteria)
                    applyFilters();
                    return;
                }
                
                // Display loading state
                document.getElementById('fieldsList').innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Buscando "${searchTerm}"...</p>
                    </div>
                `;
                
                // Simulate search delay
                setTimeout(() => {
                    // Filter by name or description
                    const filteredFields = allFields.filter(field => {
                        return field.name.toLowerCase().includes(searchTerm) || 
                               (field.description && field.description.toLowerCase().includes(searchTerm)) ||
                               field.address.toLowerCase().includes(searchTerm);
                    });
                    
                    // Display results
                    if (filteredFields.length === 0) {
                        // Show no results message
                        document.getElementById('fieldsList').innerHTML = `
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No se encontraron canchas que coincidan con "<strong>${searchTerm}</strong>".
                                <button class="btn btn-sm btn-outline-secondary mt-2" id="clearSearch">
                                    <i class="fas fa-times me-1"></i>Limpiar búsqueda
                                </button>
                            </div>
                        `;
                        
                        // Add event listener to clear search button
                        document.getElementById('clearSearch').addEventListener('click', function() {
                            document.getElementById('searchField').value = '';
                            applyFilters(); // Reset to show all fields with current filters
                        });
                    } else {
                        displayFields(filteredFields);
                    }
                }, 800);
            }

            // Search button event listener
            document.getElementById('searchBtn').addEventListener('click', searchFields);
            
            // Search input enter key event listener
            document.getElementById('searchField').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    searchFields();
                }
            });

            // Apply filters button
            document.getElementById('applyFilters').addEventListener('click', applyFilters);
            
            function applyFilters() {
                // Get filter values
                const minRating = parseInt(document.getElementById('minRating').value);
                const maxDistance = parseInt(document.getElementById('distanceRange').value);
                const searchTerm = document.getElementById('searchField').value.trim().toLowerCase();
                
                // Get selected field sizes
                const selectedSizes = [];
                if (document.getElementById('field5').checked) selectedSizes.push('5');
                if (document.getElementById('field7').checked) selectedSizes.push('7');
                if (document.getElementById('field11').checked) selectedSizes.push('11');
                
                // Get selected amenities
                const selectedAmenities = [];
                if (document.getElementById('parking').checked) selectedAmenities.push('parking');
                if (document.getElementById('shower').checked) selectedAmenities.push('shower');
                if (document.getElementById('illumination').checked) selectedAmenities.push('illumination');
                
                // Display loading state
                document.getElementById('fieldsList').innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Aplicando filtros...</p>
                    </div>
                `;
                
                // Simulate API request delay
                setTimeout(() => {
                    // Filter demo data
                    let filteredFields = allFields.filter(field => {
                        // Filter by rating
                        if (field.rating < minRating) return false;
                        
                        // Filter by distance
                        if (field.distance > maxDistance) return false;
                        
                        // Simple size filtering (real app would be more sophisticated)
                        let sizeMatch = false;
                        for (const size of selectedSizes) {
                            if (field.size.includes(size)) {
                                sizeMatch = true;
                                break;
                            }
                        }
                        if (selectedSizes.length > 0 && !sizeMatch) return false;
                        
                        // Filter by search term if provided
                        if (searchTerm !== '') {
                            return field.name.toLowerCase().includes(searchTerm) || 
                                   (field.description && field.description.toLowerCase().includes(searchTerm)) ||
                                   field.address.toLowerCase().includes(searchTerm);
                        }
                        
                        return true;
                    });
                    
                    // Display filtered results
                    displayFields(filteredFields);
                }, 1000);
            }

            // Initialize the map when the page loads
            initMap();
        });
    </script>
</body>
</html>