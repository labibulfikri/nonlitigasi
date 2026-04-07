<style>
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #map {
        width: 100%;
        height: 400px;
    }
</style>

<br />
<br />

<div class="container">
    <div class="row">
        <h1> UPDATE PETA </h1>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> Peta </h5>
                </div>
                <div class="card-body">
                    <div id='map'></div>

                    <form id="formUpdate">
                        <input type="text" name="id" id="id_nonlits" value="<?php echo $id ?>" />

                        <textarea name="polygon" id="kordinat"> <?php echo $list[0]['kordinat'] ?></textarea>
                        <br />
                        <button class="btn btn-sm btn-primary" type="submit"> SIMPAN </button>
                        <br />
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title">Detail</h5>
                </div>
                <div class="card-body">
                    <?php $this->load->view($tab) ?>

                </div>
            </div>
        </div>

    </div>
</div>
<br />
<br />
<!-- </div> -->


<!-- <script>
    // Inisialisasi peta dengan view default
    var map = L.map('map').setView([0, 0], 2);

    // Tambahkan layer peta dasar
    let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    let satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
    let basemapControl = {
        "Streets": streets,
        "Satellite": satellite
    };
    L.control.layers(basemapControl).addTo(map);

    // Data poligon dari PHP ke JavaScript
    var polygonData = <?= $polygon ?>;

    if (polygonData) {
        // Parse data poligon
        var latlngs = JSON.parse(polygonData);

        // Buat poligon dan tambahkan ke peta
        var polygon = L.polygon(latlngs, {
            color: 'red',
            weight: 2,
            opacity: 0.80
        }).addTo(map);

        // Pusatkan peta berdasarkan bounds dari poligon
        map.fitBounds(polygon.getBounds());
    }
</script> -->
<!-- <script>
    var map = L.map('map').fitWorld();

    // L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibGFiaWJ1bGZpa3JpIiwiYSI6ImNrbnM5cTU2ZDA0N3IycXMxbnZwNzU0MmEifQ.8aSIgdT7J9VJlOtqqpfjUA', {
    //     maxZoom: 20,
    //     id: 'mapbox/streets-v11',
    //     tileSize: 512,
    //     zoomOffset: -1
    // }).addTo(map);

    let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map)

    // create a satellite imagery layer
    // let satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}')
    let satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}')




    // create an object to hold layer names as you want them to appear in the basemap switcher list
    let basemapControl = {
        "Streets": streets,
        "Satellite": satellite,
    }

    // display the control (switcher list) on the map, by default in the top right corner
    L.control.layers(basemapControl).addTo(map)

    // var osm = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"),
    //     mqi = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}", {
    //         subdomains: ['otile1', 'otile2', 'otile3', 'otile4']
    //     });

    // var baseMaps = {
    //     "OpenStreetMap": osm,
    //     "MapQuestImagery": mqi
    // };

    // var overlays = { //add any overlays here

    // };

    // L.control.layers(baseMaps, overlays, {
    //     position: 'bottomleft'
    // }).addTo(map);

    // function onLocationFound(e) {
    //     var radius = e.accuracy / 2;

    //     L.marker(e.latlng).addTo(map)
    //         .bindPopup("You are within " + radius + " meters from this point").openPopup();
    //     // L.polygon.bindPopup("I am a polygon.");
    //     L.circle(e.latlng, radius).addTo(map);
    // }

    // function onLocationError(e) {
    //     alert(e.message);
    // }

    // map.on('locationfound', onLocationFound);
    // map.on('locationerror', onLocationError);

    map.locate({
        setView: true,
        maxZoom: 13
    });
    // get coordinate
    // map.on('click',
    //     function(e) {
    //         var coord = e.latlng.toString().split(',');
    //         var lat = coord[0].split('(');
    //         var lng = coord[1].split(')');
    //         alert("You clicked the map at latitude: " + lat[1] + " and longitude:" + lng[0]);
    //     });


    var nama = $("[name=nama_gis]").val();
    var alamat = $("[name=alamat]").val();
    var kelurahan = $("[name=kelurahan]").val();
    var latlngs = $("[name=polygon]").val();


    //edit polygon
    if (latlngs != " ") {

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var latlngs = JSON.parse($("[name=polygon]").val());
        var polygon = L.polygon(latlngs, {
                color: 'red'
            }).addTo(drawnItems)
            .bindPopup("Nama =" + nama + "<br /> Alamat = " + alamat + " <br /> Kelurahan =" + kelurahan).openPopup();
        map.fitBounds(polygon.getBounds());
        var drawControl = new L.Control.Draw({
            draw: {
                polyline: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);
        map.on('draw:created', function(e) {
            console.log('created');
            var type = e.layerType,
                layer = e.layer;
            var latLang = layer.getLatLngs();
            $("[name=polygon]").val(JSON.stringify(latLang[0]));
            drawnItems.addLayer(layer);
        });

        map.on('draw:edited', function(e) {
            console.log('edited');
            var latLang = e.layers.getLayers()[0].getLatLngs()[0];
            $("[name=polygon]").val(JSON.stringify(latLang));
            // drawnItems.addLayer(layer);
        });

        map.on('draw:deleted', function(e) {
            console.log('deleted');
            $("[name=polygon]").val("");
            // drawnItems.addLayer(layer);
        });
    } else {

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var drawControl = new L.Control.Draw({
            draw: {
                polyline: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });

        map.addControl(drawControl)
        map.on('draw:created', function(e) {
            console.log('created');
            var type = e.layerType,
                layer = e.layer;
            var latLang = layer.getLatLngs();
            $("[name=polygon]").val(JSON.stringify(latLang[0]));
            drawnItems.addLayer(layer);
        });
        map.on('draw:edited', function(e) {
            console.log('edited');
            var latLang = e.layers.getLayers()[0].getLatLngs()[0];
            $("[name=polygon]").val(JSON.stringify(latLang));
            // drawnItems.addLayer(layer);
        });

        map.on('draw:deleted', function(e) {
            console.log('deleted');
            $("[name=polygon]").val("");
            // drawnItems.addLayer(layer);
        });
    }
</script> -->

<script>
    var map = L.map('map').fitWorld();

    // Tambahkan layer peta dasar
    let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
    let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 30,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);
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

    var nama = $("[name=nama_gis]").val();
    var alamat = $("[name=alamat]").val();
    var kelurahan = $("[name=kelurahan]").val();
    var latlngs = $("[name=polygon]").val();

    // Edit polygon
    if (latlngs != " ") {
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var latlngs = JSON.parse(latlngs); // Pastikan data poligon sudah di-parse
        var polygon = L.polygon(latlngs, {
                color: 'red'
            }).addTo(drawnItems)
            .bindPopup("Nama =" + nama + "<br /> Alamat = " + alamat + " <br /> Kelurahan =" + kelurahan).openPopup();
        setTimeout(function() {
            map.fitBounds(polygon.getBounds());
        }, 100);

        // Memusatkan peta pada polygon yang ada
        // map.fitBounds(polygon.getBounds());

        var drawControl = new L.Control.Draw({
            draw: {
                polyline: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            console.log('created');
            var type = e.layerType,
                layer = e.layer;
            var latLang = layer.getLatLngs();
            $("[name=polygon]").val(JSON.stringify(latLang[0]));
            drawnItems.addLayer(layer);
        });

        map.on('draw:edited', function(e) {
            console.log('edited');
            var latLang = e.layers.getLayers()[0].getLatLngs()[0];
            $("[name=polygon]").val(JSON.stringify(latLang));
        });

        map.on('draw:deleted', function(e) {
            console.log('deleted');
            $("[name=polygon]").val("");
        });
    } else {
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var drawControl = new L.Control.Draw({
            draw: {
                polyline: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            console.log('created');
            var type = e.layerType,
                layer = e.layer;
            var latLang = layer.getLatLngs();
            $("[name=polygon]").val(JSON.stringify(latLang[0]));
            drawnItems.addLayer(layer);
        });

        map.on('draw:edited', function(e) {
            console.log('edited');
            var latLang = e.layers.getLayers()[0].getLatLngs()[0];
            $("[name=polygon]").val(JSON.stringify(latLang));
        });

        map.on('draw:deleted', function(e) {
            console.log('deleted');
            $("[name=polygon]").val("");
        });
    }
</script>

<script>
    document.getElementById("formUpdate").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah pengiriman formulir

        // Validasi formulir
        // var token = document.getElementById("token").value;
        // var permohonan_nonlit = document.getElementById("permohonan_nonlit").value;
        // var register_baru = document.getElementById("register_baru").value;
        // var tgl_nonlit = document.getElementById("tgl_nonlit").value;
        // var status = document.getElementById("status").value;
        // var bidang = document.getElementById("bidang").value;
        var id = document.getElementById("id_nonlits").value;
        var kordinat = document.getElementById("kordinat").value;


        // if (!permohonan_nonlit || !bidang || !tgl_nonlit || !team_nonlit || status) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: 'Harus mengisi semua field!'
        //     });
        //     return;
        // }

        // Tampilkan pesan konfirmasi menggunakan SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simpan data menggunakan AJAX
                updateData(id, kordinat);
            }
        });
    });

    // Fungsi untuk menyimpan data menggunakan AJAX
    function updateData(id, kordinat) {
        // Data yang akan dikirim
        var data = {
            id: id,
            kordinat: kordinat
            // token: token,
        };



        // Lakukan permintaan AJAX
        $.ajax({
            url: '<?php echo base_url('peta/update_peta') ?>',
            type: 'POST',
            // contentType: 'application/json',
            // data: data,
            data: {
                id: data.id,
                kordinat: data.kordinat,
                // token: data.token,
            },
            success: function(response) {
                // Tanggapi hasil dari server
                var result = JSON.parse(response);
                if (result.status === 'success') {

                    Swal.fire(
                        'Berhasil!',
                        result.message,
                        'success'
                    );
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // 2000 milidetik = 2 detik
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan data!'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Tangani kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menyimpan data!'
                });
            }
        });
    }
</script>