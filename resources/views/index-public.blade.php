@extends('layouts.template')

@section('styles')
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>
@endsection

@section('script')


    <script>
        // API Key untuk MapID

        // Inisialisasi peta dengan MapID sebagai basemap
        var map = L.map('map').setView([-6.175403054116954, 106.82717425766694], 12);

        var mapidBasemap = L.tileLayer('https://basemap.mapid.io/styles/street-2d-building/512/{z}/{x}/{y}.png?key=67d4e0d295c9d4d21029eabb', {
    attribution: '&copy; <a href="https://geo.mapid.io/">MAPID</a>',
    maxZoom: 22
}).addTo(map);


        var satelliteLayer = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '&copy; Esri'
            });

        // Layer Control
        var baseMaps = {
            "MapID": mapidBasemap,
            "Satellite": satelliteLayer
        };
        L.control.layers(baseMaps).addTo(map);

        // Fungsi untuk Menentukan Warna Berdasarkan Agama
        function getColor(religion) {
            switch (religion) {
                case 'Konghucu': return "#ff0000"; // Merah
                case 'Islam': return "#00ff00"; // Hijau
                case 'Hindu': return "#0fffff"; // Biru
                case 'Kristen': return "#000fff"; // Biru Gelap
                case 'Khatolik': return "#964b00"; // Coklat
                case 'Buddha': return "#fff000"; // Kuning
                default: return "#000000"; // Hitam
            }
        }

        /* GeoJSON Point */
        var point = L.geoJson(null, {
            pointToLayer: function(feature, latlng) {
                return L.circleMarker(latlng, {
                    radius: 8,
                    fillColor: getColor(feature.properties.religion),
                    color: "#000",
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                });
            },
            onEachFeature: function(feature, layer) {
                var popupContent = "<h4>" + feature.properties.name + "</h4>" + feature.properties.description +
                    "<br><img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "' class='img-thumbnail' alt='' style='max-height: 200px;'>";
                layer.bindPopup(popupContent);
                layer.bindTooltip(feature.properties.name);
            }
        });

        // Ambil data GeoJSON dari API
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        /* GeoJSON Polyline */
        var polyline = L.geoJson(null, {
            style: function() {
                return { color: "#ff0000", weight: 2, opacity: 1 };
            },
            onEachFeature: function(feature, layer) {
                var popupContent = "<h4>" + feature.properties.name + "</h4>" + feature.properties.description;
                layer.bindPopup(popupContent);
                layer.bindTooltip(feature.properties.name);
            }
        });

        $.getJSON("{{ route('api.polylines') }}", function(data) {
            polyline.addData(data);
            map.addLayer(polyline);
        });

        /* GeoJSON Polygon */
        var polygon = L.geoJson(null, {
            style: function() {
                return { color: "#fff000", fillColor: "#fff000", weight: 2, opacity: 1, fillOpacity: 0.2 };
            },
            onEachFeature: function(feature, layer) {
                var popupContent = "<h4>" + feature.properties.name + "</h4>" + feature.properties.description +
                    "<br><img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "' class='img-thumbnail' alt=''>";
                layer.bindPopup(popupContent);
                layer.bindTooltip(feature.properties.name);
            }
        });

        $.getJSON("{{ route('api.polygons') }}", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
        });

        /* GeoJSON Admin */
        var admin = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = feature.properties.kab_kota;
                layer.bindPopup(popupContent);
                layer.bindTooltip(feature.properties.kab_kota);
            }
        });

        $.getJSON("{{ route('api.admin') }}", function(data) {
            admin.addData(data);
            map.addLayer(admin);
        });

        // Layer Kontrol Tambahan
        var overlayMaps = {
            "Point": point,
            "Polyline": polyline,
            "Polygon": polygon,
            // "Administrasi": admin
        };

        L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);
    </script>
@endsection
