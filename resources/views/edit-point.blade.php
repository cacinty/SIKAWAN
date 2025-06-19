@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }

        .modal-header {
            background-color: rgba(52, 58, 64, 0.8);
            /* Dark header with transparency */
            color: white;
        }

        .modal-footer {
            background-color: #f1f1f1;
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

        .popup-content {
            background-color: #ffffff;
            /* Light background for popup */
            color: #343a40;
            /* Dark text color */
            padding: 15px;
            border-radius: 5px;
        }

        .btn-custom {
            background-color: #343a40;
            /* Dark button */
            color: white;
        }

        .btn-custom:hover {
            background-color: #495057;
            /* Darker on hover */
        }
    </style>
@endsection


@section('content')
    <div id="map"></div>

    <!-- Modal Edit Point -->
    <div class="modal fade" id="editpointModal" tabindex="-1" aria-labelledby="editPointLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPointLabel">Edit Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('points.update', $id) }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')

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
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail mt-2"
                                width="400">
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

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: true
            }
        });

        map.addControl(drawControl);

        map.on('draw:edited', function(e) {
            var layers = e.layers;

            layers.eachLayer(function(layer) {
                var drawnJSONObject = layer.toGeoJSON();
                var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

                // layer properties
                var properties = drawnJSONObject.properties;

                drawnItems.addLayer(layer);

                // Display data in the modal
                $('#name').val(properties.name);
                $('#sektor').val(properties.sektor);
                $('#alamat').val(properties.alamat);
                $('#pekerja').val(properties.pekerja);
                $('#description').val(properties.description);
                $('#geom_point').val(objectGeometry);
                $('#preview-image-point').attr('src', "{{ asset('storage/images') }}/" + properties.image);

                // Show edit point modal
                $('#editpointModal').modal('show');
            });
        });

        // GeoJSON Points
        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                // Add layer point to drawnItems
                drawnItems.addLayer(layer);

                var properties = feature.properties;
                var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);

                layer.on({
                    click: function(e) {
                        // Display data in the modal
                        $('#name').val(properties.name);
                        $('#sektor').val(properties.sektor);
                        $('#alamat').val(properties.alamat);
                        $('#pekerja').val(properties.pekerja);
                        $('#description').val(properties.description);
                        $('#geom_point').val(objectGeometry);
                        $('#preview-image-point').attr('src', "{{ asset('storage/images') }}/" +
                            properties.image);

                        // Show edit point modal
                        $('#editpointModal').modal('show');
                    },
                });
            },
        });

        $.getJSON("{{ route('api.point', $id) }}", function(data) {
            point.addData(data);
            map.addLayer(point);
            map.fitBounds(point.getBounds());
        });
    </script>
@endsection
