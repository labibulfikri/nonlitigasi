<style>
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #map3 {
        width: 100%;
        height: 400px;
    }
</style>
<div id='map3'></div>

<textarea name="polygon" id="kordinat" hidden> <?php echo $list[0]['kordinat'] ?></textarea>
<!-- <script>
    // var map = L.map('map2').fitWorld();
    var map = L.map('map2').setView([-7.232508107999934, 112.73466806700003], 13);


    // Tambahkan layer peta dasar
    let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
    let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 30,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);
    let basemapControl = {
        "Streets": streets,
        "Satellite": satellite
    };
    L.control.layers(basemapControl).addTo(map);

    map.locate({
        setView: true,
        maxZoom: 13
    });

    var nama = $("[name=nama_gis]").val();
    var alamat = $("[name=alamat]").val();
    var kelurahan = $("[name=kelurahan]").val();
    var latlngs = $("[name=polygon]").val();

    if (latlngs != " ") {
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var latlngs = JSON.parse(latlngs);
        var polygon = L.polygon(latlngs, {
                color: 'red'
            }).addTo(drawnItems)
            .bindPopup("Nama =" + nama + "<br /> Alamat = " + alamat + " <br /> Kelurahan =" + kelurahan).openPopup();

        // Memusatkan peta pada polygon
        setTimeout(function() {
            map.fitBounds(polygon.getBounds());
        }, 100);

    } else {
        // Hanya menampilkan peta tanpa kontrol menggambar
    }
</script> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi peta
        var map = L.map('map3').setView([-7.273228079811691, 112.721261602061], 13);

        let streets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
             maxZoom: 20,
             subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
         }).addTo(map);;
         //  let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
         let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
             maxZoom: 20,
             subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
         });
        let basemapControl = {
            "Streets": streets,
            "Satellite": satellite
        };
        L.control.layers(basemapControl).addTo(map);

        // Mendapatkan data GeoJSON dari PHP
        var geojsonData = <?php echo $polygon; ?>;

        // Debug data GeoJSON
        console.log('GeoJSON Data:', geojsonData);

        // Cek apakah data GeoJSON valid
        if (geojsonData && geojsonData.geometry && geojsonData.geometry.coordinates) {
            // Membuat layer GeoJSON dan menambahkannya ke peta
            var geojsonLayer = L.geoJSON(geojsonData, {
                style: function(feature) {
                    return {
                        color: '#FF0000', // Warna garis tepi
                        weight: 2, // Ketebalan garis
                        opacity: 1, // Opasitas garis
                        fillColor: '#FFAAAA', // Warna pengisian
                        fillOpacity: 0.5 // Opasitas pengisian
                    };
                },
                onEachFeature: function(feature, layer) {
                    if (feature.properties && feature.properties.NAMA_ASET) {
                        layer.bindPopup('<table id="myTable2" data-lat="" class="table table-hover table-success table-striped" style="width:100%"><tr><th>ALAMAT </th><td> : ' + feature.properties.ALAMAT + '</td></tr><th>ID aset</th><td> : ' + feature.properties.id_aset + '</td></tr><tr><th>KECAMATAN </th><td> : ' + feature.properties.KECAMATAN + '</td></tr> <tr><th>KELURAHAN </th><td> : ' + feature.properties.KELURAHAN + '</td></tr> <tr><th>NO SIMBADA  </th><td> : ' + feature.properties.REGSIMBADA + '</td></tr> <tr><th>SERTIFIKAT  </th><td> : ' + feature.properties.SERTIFIKAT + '</td></tr><tr><th>NO SERTIFIKAT  </th><td> : ' + feature.properties.NO_SERTIFI + '</td> </tr> </table>');

                    }
                }
            }).addTo(map);

            // Mendapatkan batas dari layer GeoJSON
            var bounds = geojsonLayer.getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds);
            } else {
                console.error('Invalid bounds:', bounds);
            }
        } else {
            console.error('Invalid GeoJSON data:', geojsonData);
        }
    });
</script>