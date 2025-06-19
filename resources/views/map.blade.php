@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        body {
            background-color: #121212;
            color: #121212;
        }

        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }

        .modal-header {
            background-color: #1f1f1f;
            color: #ffffff;
            border-bottom: 1px solid #444444;
        }

        .modal-footer {
            background-color: #1f1f1f;
            border-top: 1px solid #444444;
        }

        .modal-title {
            font-weight: bold;
        }

        .modal-content {
            background-image: url('https://png.pngtree.com/background/20230520/original/pngtree-plant-refinery-complex-industrial-structure-3d-render-photo-picture-image_2680075.jpg');
            /* Add your background image */
            background-size: cover;
            /* Cover the entire modal */
            background-position: center;
            /* Center the image */
            color: white;
            /* Change text color to white for better contrast */
        }

        .modal-body {
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background for the body */
            border-radius: 5px;
            /* Rounded corners */
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            /* Slightly transparent white background for input fields */
            color: #343a40;
            /* Dark text color */
        }

        .form-control::placeholder {
            color: #6c757d;
            /* Placeholder text color */
        }

        .img-thumbnail {
            border-radius: 5px;
        }

        .popup-content {
            text-align: center;
            background-color: #2c2c2c;
            /* Darker background for popups */
            color: #ffffff;
            padding: 15px;
            /* Increased padding for better spacing */
            border-radius: 10px;
            /* Rounded corners */
            box-shadow: 0 15px 25px rgba(121, 116, 116, 0.7);
            /* Subtle shadow for depth */
        }

        .popup-content h5 {
            font-size: 1.5em;
            /* Larger title font size */
            margin-bottom: 10px;
            /* Space below title */
        }

        .popup-content p {
            margin: 5px 0;
            /* Space between paragraphs */
        }

        .popup-content img {
            width: 100%;
            /* Responsive image */
            max-width: 250px;
            /* Max width for images */
            border-radius: 10px;
            /* Rounded corners for images */
        }

        .btn {
            margin: 5px;
            /* Space between buttons */
        }

        .btn-primary,
        .btn-success,
        .btn-warning,
        .btn-danger {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-warning:hover,
        .btn-danger:hover {
            opacity: 0.9;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Create Point-->
    <div class="modal fade" id="createpointModal" tabindex="-1" aria-labelledby="createPointLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createPointLabel">Create Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Industry Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter industry name" required>
                        </div>

                        <div class="mb-3">
                            <label for="sektor" class="form-label">Sector</label>
                            <textarea class="form-control" id="sektor" name="sektor" rows="2" placeholder="Enter sector" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Address</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Enter address" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="pekerja" class="form-label">Number of Workers</label>
                            <textarea class="form-control" id="pekerja" name="pekerja" rows="1" placeholder="Enter number of workers"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail mt-2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save <i class="fa-solid fa-check"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Polyline-->
    <div class="modal fade" id="createpolylineModal" tabindex="-1" aria-labelledby="createPolylineLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createPolylineLabel">Create Polyline</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polylines.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter polyline name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polyline" name="geom_polyline" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polyline" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail mt-2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save <i class="fa-solid fa-check"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Polygon-->
    <div class="modal fade" id="createpolygonModal" tabindex="-1" aria-labelledby="createPolygonLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createPolygonLabel">Create Polygon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polygons.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter polygon name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail mt-2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save <i class="fa-solid fa-check"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        var map = L.map('map').setView([-7.602623, 111.900982], 13);

        // Base layers
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var cartoDBPositron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        });

        var terrain = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://opentopomap.org/copyright">OpenTopoMap</a> contributors'
        });

        // Add base layers to the map
        osm.addTo(map); // Default base layer

        // Control Layer
        var baseMaps = {
            "OSM": osm,
            "CartoDB Positron": cartoDBPositron,
            "Terrain": terrain
        };

        var overlayMaps = {
            "Points": new L.FeatureGroup(),
            "Polylines": new L.FeatureGroup(),
            "Polygons": new L.FeatureGroup()
        };

        var controlLayer = L.control.layers(baseMaps, overlayMaps, {
            collapsed: false
        });

        controlLayer.addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: true,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            if (type === 'polyline') {
                $('#geom_polyline').val(objectGeometry);
                $('#createpolylineModal').modal('show');
            } else if (type === 'polygon' || type === 'rectangle') {
                $('#geom_polygon').val(objectGeometry);
                $('#createpolygonModal').modal('show');
            } else if (type === 'marker') {
                $('#geom_point').val(objectGeometry);
                $('#createpointModal').modal('show');
            }

            drawnItems.addLayer(layer);
            overlayMaps[type.charAt(0).toUpperCase() + type.slice(1)].addLayer(layer); // Add to overlay map
        });

        // Load GeoJSON data for points, polylines, and polygons
        var pointLayer = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('points.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('points.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = `
                    <div class="popup-content">
                        <h5>Industry Name: ${feature.properties.name}</h5>
                        <p><strong>Sector:</strong> ${feature.properties.sektor}</p>
                        <p><strong>Address:</strong> ${feature.properties.alamat}</p>
                        <p><strong>Number of Workers:</strong> ${feature.properties.pekerja}</p>
                        <p><strong>Description:</strong> ${feature.properties.description}</p>
                        <img src='{{ asset('storage/images/') }}/${feature.properties.image}' alt='Image' class='img-fluid'>
                        <div class='mt-2'>
                            <a href='${routeedit}' class='btn btn-warning btn-sm'>
                                <i class='fa-solid fa-pen-to-square'></i> Edit
                            </a>
                            <form method='POST' action='${routedelete}' style='display:inline;'>
                                @csrf @method('DELETE')
                                <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>
                                    <i class='fa-solid fa-trash-can'></i> Delete
                                </button>
                            </form>
                        </div>
                        <p class='mt-2'><strong>Created by:</strong> ${feature.properties.user_created}</p>
                    </div>
                `;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    },
                });
            },
        });

        $.getJSON("{{ route('api.points') }}", function(data) {
            pointLayer.addData(data);
            overlayMaps["Points"].addLayer(pointLayer);
            map.addLayer(pointLayer);
        });

        var polylineLayer = L.geoJson(null, {
            style: function(feature) {
                return {
                    color: "#007bff",
                    weight: 3,
                    opacity: 1,
                };
            },
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polylines.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('polylines.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = `
                    <div class="popup-content">
                        <h5>${feature.properties.name}</h5>
                        <p>${feature.properties.description}</p>
                        <p>Length (m): ${feature.properties.length_m.toFixed(2)}</p>
                        <p>Length (km): ${feature.properties.length_km.toFixed(2)}</p>
                        <img src='{{ asset('storage/images') }}/${feature.properties.image}' alt='Image' class='img-fluid'>
                        <div class='mt-2'>
                            <a href='${routeedit}' class='btn btn-warning btn-sm'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                            <form method='POST' action='${routedelete}' style='display:inline;'>
                                @csrf @method('DELETE')
                                <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'><i class='fa-solid fa-trash-can'></i> Delete</button>
                            </form>
                        </div>
                        <p class='mt-2'>Created by: ${feature.properties.user_created}</p>
                    </div>
                `;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    },
                });
            },
        });

        $.getJSON("{{ route('api.polylines') }}", function(data) {
            polylineLayer.addData(data);
            overlayMaps["Polylines"].addLayer(polylineLayer);
            map.addLayer(polylineLayer);
        });

        var polygonLayer = L.geoJson(null, {
            style: function(feature) {
                return {
                    color: "#FF1493",
                    fillColor: "#FFB6C1",
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.2,
                };
            },
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polygons.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('polygons.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = `
                    <div class="popup-content">
                        <h5>${feature.properties.name}</h5>
                        <p>${feature.properties.description}</p>
                        <p>Area (m²): ${feature.properties.area_m2.toFixed(2)}</p>
                        <p>Area (km²): ${feature.properties.area_km2.toFixed(2)}</p>
                                                <img src='{{ asset('storage/images') }}/${feature.properties.image}' alt='Image' class='img-fluid'>
                        <div class='mt-2'>
                            <a href='${routeedit}' class='btn btn-warning btn-sm'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                            <form method='POST' action='${routedelete}' style='display:inline;'>
                                @csrf @method('DELETE')
                                <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'><i class='fa-solid fa-trash-can'></i> Delete</button>
                            </form>
                        </div>
                        <p class='mt-2'>Created by: ${feature.properties.user_created}</p>
                    </div>
                `;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    },
                });
            },
        });

        $.getJSON("{{ route('api.polygons') }}", function(data) {
            polygonLayer.addData(data);
            overlayMaps["Polygons"].addLayer(polygonLayer);
            map.addLayer(polygonLayer);
        });

        // Set default checked layers
        overlayMaps["Points"].addTo(map);
        overlayMaps["Polylines"].addTo(map);
        overlayMaps["Polygons"].addTo(map);
    </script>
@endsection
