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
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Inisialisasi peta
    //     var map = L.map('map3', {
    //         scrollWheelZoom: false
    //     }).setView([-7.273228, 112.721261], 13);

    //     let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    //         maxZoom: 30,
    //         subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    //     }).addTo(map);

    //     // Data GeoJSON dari Controller
    //     var geojsonData = <?php echo $polygon; ?>;
    //     var geojsonLayer;

    //     if (geojsonData && geojsonData.features && geojsonData.features.length > 0) {
    //         geojsonLayer = L.geoJSON(geojsonData, {
    //             style: function(feature) {
    //                 return {
    //                     color: '#2563eb', // Blue-600
    //                     weight: 3,
    //                     opacity: 0.8,
    //                     fillColor: '#3b82f6',
    //                     fillOpacity: 0.3
    //                 };
    //             },
    //             onEachFeature: function(feature, layer) {
    //                 let props = feature.properties;
    //                 let popupContent = `
    //                     <div class="p-2 min-w-[200px]">
    //                         <h4 class="font-black text-blue-600 border-b mb-2 uppercase text-xs">REG: ${props.register_baru}</h4>
    //                         </div>
    //                         `;
    //                         layer.bindPopup(popupContent);
    //                         // <table class="table table-xs w-full bg-transparent">
    //                         //     <tr><th class="bg-slate-100 p-1">ALAMAT</th><td class="p-1">${props.ALAMAT}</td></tr>
    //                         //     <tr><th class="bg-slate-100 p-1">SERTIFIKAT</th><td class="p-1">${props.NO_SERTIFI || '-'}</td></tr>
    //                         // </table>
    //             }
    //         }).addTo(map);

    //         // Fit semua polygon sekaligus
    //         setTimeout(() => {
    //             map.fitBounds(geojsonLayer.getBounds(), { padding: [30, 30] });
    //         }, 500);
    //     }

    //     // --- FUNGSI FOKUS SAAT NOMOR REGISTER DIKLIK ---
    //     $('.btn-focus-map').on('click', function() {
    // // Pastikan targetReg dikonversi ke string dengan aman
    // let targetReg = ($(this).data('reg') || "").toString().trim();
    // let found = false;

    // if (geojsonLayer) {
    //     geojsonLayer.eachLayer(function(layer) {
    //         // PROTEKSI: Cek apakah feature dan properties tersedia
    //         if (layer.feature && layer.feature.properties && layer.feature.properties.register_baru) {
                
    //             let currentReg = layer.feature.properties.register_baru.toString().trim();
                
    //             if (currentReg === targetReg) {
    //                 // Terbang ke polygon spesifik
    //                 map.flyToBounds(layer.getBounds(), { 
    //                     padding: [50, 50],
    //                     duration: 1.5 
    //                 });
    //                 layer.openPopup();
    //                 found = true;
    //             }
    //         }
    //     });
    // }

    // if (!found && targetReg !== "") {
    //     alert("Polygon untuk register " + targetReg + " tidak ditemukan di peta GIS.");
    // }
    //     });
    // });
 
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inisialisasi Peta (Zoom In/Out Aktif)
         var map = L.map('map3', { 
            zoomControl: false,
            attributionControl: false 
        }).setView([-7.2575, 112.7521], 12);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // --- BASE MAP DENGAN NAMA JALAN ---
        var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20, 
            subdomains: ['mt0','mt1','mt2','mt3']
        }).addTo(map);

        var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20, 
            subdomains: ['mt0','mt1','mt2','mt3']
        });
        // 2. Load Data GeoJSON Merah
        var geojsonData = <?php echo $polygon; ?>;
        var geojsonLayer;

        if (geojsonData && geojsonData.features && geojsonData.features.length > 0) {
            geojsonLayer = L.geoJSON(geojsonData, {
                style: function(f) {
                    return { 
                        color: '#ff0000', // Warna Merah Terang
                        fillColor: '#ff0000', 
                        weight: 4, 
                        fillOpacity: 0.6 
                    };
                },
                onEachFeature: function(f, l) {
                    let p = f.properties;
                    // Popup Cantik
                    let popupHtml = `
                        <div class="font-sans min-w-[220px]">
                            <div class="bg-red-700 text-white p-3 -m-4 mb-3 rounded-t-lg shadow-md">
                                <p class="text-[9px] font-bold opacity-70 uppercase tracking-tighter">Detail Aset GIS</p>
                                <p class="text-xs font-black">${p.register_baru}</p>
                            </div>
                            <div class="space-y-2 py-1 text-slate-700">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Alamat</p>
                                    <p class="text-[11px] leading-tight font-medium">${p.ALAMAT || '-'}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 border-t pt-2">
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">ID Aset</p>
                                        <p class="text-[10px] font-bold text-blue-600">${p.id_aset}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">Sertifikat</p>
                                        <p class="text-[10px] font-bold">${p.NO_SERTIFI || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    l.bindPopup(popupHtml);
                }
            }).addTo(map);

            // Fit Bounds Awal
            map.fitBounds(geojsonLayer.getBounds(), { padding: [40, 40] });
        }

        // --- FUNGSI KLIK REGISTER UNTUK ZOOM IN (FIXED) ---
        $('.btn-focus-map').on('click', function() {
            // Ambil data-reg dan bersihkan spasi
            let targetReg = $(this).data('reg').toString().trim();
            let found = false;

            if (geojsonLayer) {
                geojsonLayer.eachLayer(function(layer) {
                    // Cek ketersediaan properti sebelum perbandingan
                    if (layer.feature && layer.feature.properties && layer.feature.properties.register_baru) {
                        let currentReg = layer.feature.properties.register_baru.toString().trim();

                        if (currentReg === targetReg) {
                            // Zoom In Otomatis ke Bidang
                            map.flyToBounds(layer.getBounds(), { 
                                padding: [80, 80], 
                                maxZoom: 18, 
                                duration: 1.5 
                            });
                            layer.openPopup();
                            found = true;
                        }
                    }
                });
            }

           if (!found) {
                // Menggunakan SweetAlert2 (jika Anda menggunakannya)
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Data Spasial Tidak Ada',
                        text: 'Nomor Register ' + targetReg + ' tidak ditemukan dalam peta GIS (peta_gis).',
                        icon: 'warning',
                        confirmButtonColor: '#FF991C',
                        confirmButtonText: 'Tutup',
                        customClass: {
                popup: 'rounded-[24px]',
                confirmButton: 'btn btn-primary rounded-xl px-10' // Memaksa style tombol pakai DaisyUI
            },
            buttonsStyling: true
                    });
                } else {
                    // Alert standar browser sebagai fallback
                    alert("PERINGATAN: Nomor Register " + targetReg + " tidak ditemukan dalam sistem peta GIS (peta_gis). Pastikan data spasial sudah diinput.");
                }
                }
        });
    }); 
</script> 