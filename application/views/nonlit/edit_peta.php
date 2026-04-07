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
                        <input type="hidden" name="id" id="id_nonlits" value="<?php echo $id ?>" />
                        <textarea name="polygon" id="kordinat" style="display:none;"><?php echo $list[0]['kordinat'] ?></textarea>
                        <br />
                        <button class="btn btn-sm btn-primary w-full font-bold italic" type="submit"> SIMPAN PERUBAHAN PETA </button>
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

<script>
    var map = L.map('map').fitWorld();

    let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
    let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 30,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    let basemapControl = {
        "Streets": streets,
        "Satellite": satellite,
    };
    L.control.layers(basemapControl).addTo(map);

    map.locate({ setView: true, maxZoom: 30 });

    var nama = $("[name=nama_gis]").val();
    var alamat = $("[name=alamat]").val();
    var latlngsRaw = $("#kordinat").val();

    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    if (latlngsRaw && latlngsRaw.trim() !== "") {
        try {
            var latlngs = JSON.parse(latlngsRaw);
            var polygon = L.polygon(latlngs, { color: 'red' }).addTo(drawnItems);
            polygon.bindPopup("<b>" + nama + "</b><br>" + alamat).openPopup();
            
            setTimeout(function() {
                map.fitBounds(polygon.getBounds());
            }, 500);
        } catch (e) {
            console.error("Invalid Polygon Data");
        }
    }

    var drawControl = new L.Control.Draw({
        draw: { polyline: false, rectangle: false, circle: false, circlemarker: false, marker: false },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    map.on('draw:created', function(e) {
        var layer = e.layer;
        var latLang = layer.getLatLngs();
        $("#kordinat").val(JSON.stringify(latLang[0]));
        drawnItems.addLayer(layer);
    });

    map.on('draw:edited', function(e) {
        var layers = e.layers;
        layers.eachLayer(function(layer) {
            var latLang = layer.getLatLngs()[0];
            $("#kordinat").val(JSON.stringify(latLang));
        });
    });

    map.on('draw:deleted', function() {
        $("#kordinat").val("");
    });

    // AJAX Update
    document.getElementById("formUpdate").addEventListener("submit", function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Simpan Peta?',
            text: "Koordinat polygon akan diperbarui!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Simpan'
        }).then((result) => {
            if (result.isConfirmed) {
                updateData($("#id_nonlits").val(), $("#kordinat").val());
            }
        });
    });

    function updateData(id, kordinat) {
        $.ajax({
            url: '<?php echo base_url('peta/update_peta') ?>',
            type: 'POST',
            data: { id: id, kordinat: kordinat },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    Swal.fire('Berhasil!', result.message, 'success');
                    setTimeout(() => { location.reload(); }, 1500);
                } else {
                    Swal.fire('Error', 'Gagal menyimpan', 'error');
                }
            }
        });
    }
</script>