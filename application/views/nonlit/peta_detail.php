<style>
    #map3 {
        width: 100%;
        height: 450px; /* Sedikit lebih tinggi agar lega */
        z-index: 1;
    }
    /* Mempercantik Popup Leaflet agar sesuai tema DaisyUI */
    .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        padding: 5px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .leaflet-popup-content table {
        margin: 0;
        font-size: 11px;
    }
</style>

<div class="relative group">
    <div class="absolute top-4 left-12 z-[1000]">
        <div class="badge badge-primary gap-2 p-3 shadow-lg font-bold italic">
            <i class="mdi mdi-map-marker-radius"></i>
            AREA ASET
        </div>
    </div>
    
    <div id='map3' class="rounded-2xl border-4 border-white shadow-inner overflow-hidden"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi peta
        var map = L.map('map3', {
            scrollWheelZoom: false // Mencegah zoom tidak sengaja saat scroll halaman
        }).setView([-7.273228079811691, 112.721261602061], 13);

        // Layer Control
        let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 30,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        L.control.layers({ "Streets": streets, "Satellite": satellite }).addTo(map);

        // Data GeoJSON
        var geojsonData = <?php echo $polygon; ?>;

        if (geojsonData && geojsonData.geometry) {
            var geojsonLayer = L.geoJSON(geojsonData, {
                style: function(feature) {
                    return {
                        color: '#2563eb', // Blue-600
                        weight: 3,
                        opacity: 0.8,
                        fillColor: '#3b82f6',
                        fillOpacity: 0.3
                    };
                },
                onEachFeature: function(feature, layer) {
                    let props = feature.properties;
                    let popupContent = `
                        <div class="p-2">
                            <h4 class="font-black text-blue-600 border-b mb-2 uppercase text-xs">Detail Aset GIS</h4>
                            <table class="table table-compact w-full bg-transparent">
                                <tr><th class="bg-slate-100">ID ASET</th><td>${props.id_aset}</td></tr>
                                <tr><th class="bg-slate-100">ALAMAT</th><td>${props.ALAMAT}</td></tr>
                                <tr><th class="bg-slate-100">KEL/KEC</th><td>${props.KELURAHAN} / ${props.KECAMATAN}</td></tr>
                                <tr><th class="bg-slate-100">SERTIFIKAT</th><td>${props.NO_SERTIFI || '-'}</td></tr>
                            </table>
                        </div>
                    `;
                    layer.bindPopup(popupContent, { maxWidth: 300 });
                }
            }).addTo(map);

            // Fit Bounds dengan animasi
            setTimeout(() => {
                map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
            }, 500);
        }
    });
</script>