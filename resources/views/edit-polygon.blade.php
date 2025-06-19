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
            background-color: rgba(52, 58, 64, 0.8); /* Dark header with transparency */
            color: white;
        }

        .modal-footer {
            background-color: #f1f1f1;
        }

        .modal-title {
            font-weight: bold;
        }

        .modal-content {
            background-image: url('https://png.pngtree.com/background/20230520/original/pngtree-plant-refinery-complex-industrial-structure-3d-render-photo-picture-image_2680075.jpg'); /* Add your background image */
            background-size: cover; /* Cover the entire modal */
            background-position: center; /* Center the image */
            color: white; /* Change text color to white for better contrast */
        }

        .modal-body {
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for the body */
            border-radius: 5px; /* Rounded corners */
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white background for input fields */
            color: #343a40; /* Dark text color */
        }

        .form-control::placeholder {
            color: #6c757d; /* Placeholder text color */
        }

        .popup-content {
            background-color: #ffffff; /* Light background for popup */
            color: #343a40; /* Dark text color */
            padding: 15px;
            border-radius: 5px;
        }

        .btn-custom {
            background-color: #343a40; /* Dark button */
            color: white;
        }

        .btn-custom:hover {
            background-color: #495057; /* Darker on hover */
        }
    </style>
@endsection


@section('content')
    <div id="map"></div>

    <!-- Modal Edit Polygon-->
    <div class="modal fade" id="editpolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Polygon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polygons.update', $id) }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="fill polygon name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
                console.log(drawnJSONObject);

                var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
                console.log(objectGeometry);

                // layer properties
                var properties = drawnJSONObject.properties;
                console.log(properties);

                drawnItems.addLayer(layer);

                //menampilkan data ke dalam modal
                $('#name').val(properties.name);
                $('#description').val(properties.description);
                $('#geom_polygon').val(objectGeometry);
                $('#preview-image-polygon').attr('src', "{{ asset('storage/images') }}/" + properties.image);

                //menampilkan modal edit polygon
                $('#editpolygonModal').modal('show');
            });

        });

        // GeoJSON Polygons
        var polygon = L.geoJson(null, {
            onEachFeature: function(feature, layer) {

                //Memasukan layer polygon ke dalam drawnItems
                drawnItems.addLayer(layer);

                var properties = feature.properties;
                var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);

                layer.on({
                    click: function(e) {
                        //menampilkan data ke dalam modal
                        $('#name').val(properties.name);
                        $('#description').val(properties.description);
                        $('#geom_polygon').val(objectGeometry);
                        $('#preview-image-polygon').attr('src', "{{ asset('storage/images') }}/" +
                            properties.image);

                        //menampilkan modal edit polygon
                        $('#editpolygonModal').modal('show');
                    },
                });
            },
        });
        $.getJSON("{{ route('api.polygon', $id) }}", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
            map.fitBounds(polygon.getBounds());
            padding: [150, 150]
        });
    </script>
@endsection
