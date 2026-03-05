<style>
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #map {
        width: 100%;
        height: 800px;
    }

    .autocomplete-suggestions {
        border: 1px solid #ddd;
        background-color: #fff;
        position: absolute;
        z-index: 1000;
        width: 70%;
        max-height: 200px;
        overflow-y: auto;
    }

    .autocomplete-suggestion {
        padding: 10px;
        cursor: pointer;
    }

    .autocomplete-suggestion:hover {
        background-color: #f0f0f0;
    }
</style>
<!-- Tambahkan CSS Leaflet --><!-- CSS Leaflet -->
<link rel="stylesheet" href="https://opengeo.tech/maps/leaflet-search/src/leaflet-search.css" />
<!-- Tambahkan CSS Leaflet -->
<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" /> -->
<!-- Tambahkan CSS Leaflet Control Geocoder -->
<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" /> -->




<div class="container" style="margin-top: 100px;">

    <h2>Peta Nonlit</h2>


    <form id="searchForm">
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" id="search" name="search" placeholder="Masukkan karakter">
                <div id="suggestions" class="autocomplete-suggestions"></div>

            </div>
            <div class="col-md-2">
                <button class="btn btn-xl btn-primary" type="submit"> Cari</button>
            </div>
            <!-- <input type="text"  placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2"> -->
            <!-- <input class="form-control" type="text" id="alamat" name="alamat" placeholder="Masukkan Permohonan Nonlitigasi " required> -->
        </div>
        <!-- <button class="btn btn-outline-secondary" type="button" id="button-addon2">Button</button> -->
    </form>
    <br />
    <div id='map'></div>
    <br />
    <br />

</div>
<!-- JS Leaflet -->

<!-- JS Leaflet -->
<script src="https://opengeo.tech/maps/leaflet-search/dist/leaflet-search.src.js"></script>
<!-- <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> -->
<!-- Leaflet Control Search JS -->

<!-- <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> -->
<!-- Tambahkan JS Leaflet Control Geocoder -->
<!-- <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script> -->
<script>
    var map = L.map('map').setView([-7.273228079811691, 112.721261602061], 13);
    // Tambahkan layer peta dasar
    let streets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);;
    //  let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    // let satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
    let basemapControl = {
        "Streets": streets,
        "Satellite": satellite,
        // "tes": tes,
    };
    L.control.layers(basemapControl).addTo(map);

    map.locate({
        setView: true,
        maxZoom: 30
    });
    // Data GeoJSON yang dikirim dari controller
    var polygons = <?php echo $polygons; ?>;

    // var polygonsLayer = L.geoJSON(polygons).addTo(map); // Layer kosong untuk menyimpan hasil pencarian


    // Buat layer grup untuk semua poligon
    var polygonsLayer = L.geoJSON(polygons, {
        onEachFeature: function(feature, layer) {
            // Setiap poligon diberi popup
            // console.log(feature.properties);
            if (feature.properties && feature.properties.ALAMAT) {
                layer.bindPopup('<table id="myTable2" data-lat="" class="table table-hover table-success table-striped" style="width:100%"><tr><th>ALAMAT </th><td> : ' + feature.properties.ALAMAT + '</td></tr><th>ID aset</th><td> : ' + feature.properties.id_aset + '</td></tr><tr><th>KECAMATAN </th><td> : ' + feature.properties.KECAMATAN + '</td></tr> <tr><th>KELURAHAN </th><td> : ' + feature.properties.KELURAHAN + '</td></tr> <tr><th>NO SIMBADA  </th><td> : ' + feature.properties.REGSIMBADA + '</td></tr> <tr><th>SERTIFIKAT  </th><td> : ' + feature.properties.SERTIFIKAT + '</td></tr><tr><th>NO SERTIFIKAT  </th><td> : ' + feature.properties.NO_SERTIFI + '</td> </tr> </table>');
            }
        }
    }).addTo(map);
    // // Tambahkan control pencarian ke peta
    // var searchControl = new L.Control.Search({
    //     layer: polygonsLayer,
    //     propertyName: 'ALAMAT', // Cari berdasarkan properti 'name'
    //     marker: false,
    //     initial: false,
    //     zoom: 16,
    //     textPlaceholder: 'Cari ...',
    // });

    // // Event untuk menampilkan popup saat hasil pencarian ditemukan dan diklik
    // searchControl.on('search:locationfound', function(e) {
    //     e.layer.openPopup();
    // });

    // map.addControl(searchControl);


    ///////////////////////////////

    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var search = $('#search').val();

        // AJAX request
        $.ajax({
            url: '<?php echo site_url('peta/search'); ?>',
            type: 'GET',
            data: {
                search: search
            },
            success: function(response) {
                var polygons = JSON.parse(response);

                // Bersihkan layer sebelumnya
                polygonsLayer.clearLayers();

                // Konversi data GeoJSON
                var geojsonData = {
                    "type": "FeatureCollection",
                    "features": polygons.map(function(record) {
                        return JSON.parse(record.kordinat);
                    }).flat()
                };

                // Tambahkan hasil pencarian ke peta
                polygonsLayer.addData(geojsonData);

                // Zoom ke hasil pencarian pertama jika ada
                if (geojsonData.features.length > 0) {
                    map.fitBounds(polygonsLayer.getBounds());
                }
            }
        });
    });
</script>

<!-- <script>
    var map = L.map('map').setView([-7.232508107999934, 112.73466806700003], 13); // Default koordinat

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    console.log("Data JSON from PHP:", <?php echo $polygons; ?>);
    var initialPolygons = <?php echo isset($polygons) ? $polygons : '[]'; ?>;

    // Debugging: Cek data yang diterima
    console.log("Initial Polygons Data:", initialPolygons);

    if (initialPolygons !== null && Array.isArray(initialPolygons)) {
        try {
            var geojsonData = {
                "type": "FeatureCollection",
                "features": initialPolygons.map(function(record) {
                    console.log("Record:", record); // Tambahkan log untuk melihat record yang sedang diproses
                    console.log("Kordinat:", record.kordinat); // Pastikan kordinat bukan undefined
                    return JSON.parse(record.kordinat); // Parsing setiap kordinat dalam JSON
                }).flat()
            };
            polygonsLayer.addData(geojsonData);

            // Zoom ke bounds dari poligon yang ditambahkan
            if (geojsonData.features.length > 0) {
                map.fitBounds(polygonsLayer.getBounds());
            }
        } catch (e) {
            console.error("Error parsing JSON or adding data to the map: ", e.message);
        }
    } else {
        console.error("Initial Polygons is null, undefined, or not an array.");
    }

    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var alamat = $('#alamat').val();

        $.ajax({
            url: '<?php echo site_url('peta/search'); ?>',
            type: 'GET',
            data: {
                alamat: alamat
            },
            success: function(response) {
                // Periksa apakah response bukan undefined atau kosong
                if (response) {
                    try {
                        var polygons = JSON.parse(response);

                        // Bersihkan layer sebelumnya
                        polygonsLayer.clearLayers();

                        // Konversi data GeoJSON
                        var geojsonData = {
                            "type": "FeatureCollection",
                            "features": polygons.map(function(record) {
                                return JSON.parse(record.kordinat); // Parsing setiap kordinat dalam JSON
                            }).flat()
                        };

                        // Tambahkan hasil pencarian ke peta
                        polygonsLayer.addData(geojsonData);

                        // Zoom ke hasil pencarian pertama jika ada
                        if (geojsonData.features.length > 0) {
                            map.fitBounds(polygonsLayer.getBounds());
                        }
                    } catch (e) {
                        console.error("JSON Parse Error: ", e.message);
                    }
                } else {
                    console.error("Received empty or invalid response from server.");
                }
            }
        });
    });
</script> -->


<script type="text/javascript">
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var search = $(this).val();

            if (search.length >= 2) { // Hanya cari ketika input lebih dari 2 karakter
                $.ajax({
                    url: '<?= base_url('peta/search2'); ?>', // Ganti dengan URL controller
                    method: 'POST',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#suggestions').empty();
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                $('#suggestions').append('<div class="autocomplete-suggestion" data-id="' + value.id + '">' + value.permohonan_nonlit + '</div>');
                            });
                        } else {
                            $('#suggestions').append('<div class="autocomplete-suggestion">Tidak ditemukan</div>');
                        }
                    }
                });
            } else {
                $('#suggestions').empty(); // Bersihkan jika input kurang dari 2 karakter
            }
        });

        // Klik salah satu suggestion
        $(document).on('click', '.autocomplete-suggestion', function() {
            var name = $(this).text();
            $('#search').val(name);
            $('#suggestions').empty(); // Bersihkan setelah memilih
        });
    });
</script>