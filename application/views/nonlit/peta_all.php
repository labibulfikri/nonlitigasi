<style>
    /* Ukuran Peta */
    #map {
        width: 100%;
        height: 600px;
        /* Disesuaikan agar pas di layar dashboard */
        z-index: 1;
    }

    /* Mempercantik Autocomplete dengan DaisyUI look */
    .autocomplete-suggestions {
        position: absolute;
        /* Naikkan z-index agar di atas Leaflet (Leaflet control biasanya 1000) */
        z-index: 9999 !important;
        width: 100%;
        max-height: 250px;
        overflow-y: auto;
        background-color: white !important;
        /* Paksa latar belakang putih */
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        margin-top: 4px;
    }

    /* Pastikan teks suggestion terlihat jelas */
    .item-suggestion span {
        color: #1e293b !important;
        /* Warna Slate-800 */
        font-weight: 500;
    }

    .item-suggestion:hover {
        background-color: #f1f5f9 !important;
    }

    /* Memastikan Leaflet Control tidak tertutup sidebar */
    .leaflet-control-container {
        z-index: 400;
    }

    /* Custom Scrollbar untuk Suggestion */
    .autocomplete-suggestions::-webkit-scrollbar {
        width: 4px;
    }

    .autocomplete-suggestions::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>

<div class="flex flex-col gap-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">E-Peta Nonlitigasi</h2>
            <p class="text-sm text-slate-500">Visualisasi data aset dan permohonan nonlitigasi Kota Surabaya</p>
        </div>

        <!-- <form id="searchForm" class="relative w-full md:w-auto">
            <div class="join w-full md:w-96 shadow-sm">
                <div class="relative flex-grow">
                    <input type="text"
                        class="input input-bordered join-item w-full focus:outline-none focus:border-primary"
                        id="search"
                        name="search"
                        placeholder="Cari alamat atau permohonan..."
                        autocomplete="off">

                    <div id="suggestions" class="autocomplete-suggestions mt-1 shadow-2xl bg-base-100 hidden">
                    </div>
                </div>
                <button class="btn btn-primary join-item px-6" type="submit">
                    <i class="mdi mdi-magnify text-xl"></i>
                </button>
            </div>
        </form> -->
        <form id="searchForm" class="w-full md:w-96 shadow-sm">
            <div class="join w-full">
                <div class="relative flex-grow">
                    <input type="text"
                        class="input input-bordered join-item w-full focus:outline-none focus:border-primary text-slate-800"
                        id="search"
                        name="search"
                        placeholder="Cari alamat atau permohonan..."
                        autocomplete="off">

                    <div id="suggestions" class="autocomplete-suggestions hidden shadow-2xl">
                    </div>
                </div>
                <button class="btn btn-primary join-item px-6" type="submit">
                    <i class="mdi mdi-magnify text-xl"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="card bg-base-100 shadow-xl border border-slate-200 overflow-hidden">
        <div class="relative group">
            <div class="absolute top-4 left-4 z-[500]">
                <div class="badge badge-neutral bg-slate-800/80 backdrop-blur-md border-none p-4 gap-2 shadow-lg">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-white">Live GIS Data</span>
                </div>
            </div>

            <div id='map' class="bg-slate-100"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // --- 1. INISIALISASI PETA ---
        var map = L.map('map', {
            zoomControl: false 
        }).setView([-7.2732, 112.7212], 13);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        // --- 2. LAYER PETA (STREETS & SATELLITE) ---
        let streets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        L.control.layers({
            "Peta Jalan": streets,
            "Satelit": satellite
        }, null, {
            position: 'topright'
        }).addTo(map);

        // --- 3. LOAD & STYLE GEOJSON ---
        var checkData = <?php echo $polygons; ?>;
    console.log("Data Peta:", checkData);
        var polygonsData = <?php echo $polygons; ?>;
        
        var polygonsLayer = L.geoJSON(polygonsData, {
            style: function(feature) {
                // WARNA MERAH: Jika ada di database nonlit (status_nonlit bermasalah)
                if (feature.properties.status_nonlit === 'bermasalah' || feature.properties.status === 'bermasalah') {
                    return { color: 'red', weight: 3, fillOpacity: 0.5 };
                }
                // WARNA BIRU: Standar aset sertifikasi
                return { color: '#3b82f6', weight: 2, fillOpacity: 0.2 };
            },
            onEachFeature: function(feature, layer) {
                let p = feature.properties;
                let popupContent = `
                    <div class="p-1">
                        <h5 class="font-bold uppercase text-xs">${p.alamat || 'Tanpa Alamat'}</h5>
                        <p class="text-[10px] opacity-70">Reg: ${p.register_b || p.register || '-'}</p>
                        ${p.status === 'bermasalah' ? '<span class="badge badge-error badge-xs mt-2 text-white">MASALAH NONLIT</span>' : ''}
                    </div>
                `;
                layer.bindPopup(popupContent);
            }
        }).addTo(map);

        // Zoom otomatis ke seluruh wilayah aset di awal
        if (polygonsData.features.length > 0) {
            map.fitBounds(polygonsLayer.getBounds());
        }

        // --- 4. LOGIKA PENCARIAN (FOKUS TANPA HAPUS PETA LAIN) ---
        $('#searchForm').submit(function(e) {
            e.preventDefault();
            var searchVal = $('#search').val().toLowerCase();
            var found = false;

            polygonsLayer.eachLayer(function(layer) {
                var prop = layer.feature.properties;
                
                // Cari berdasarkan alamat atau nomor register
                var matchesAlamat = prop.alamat && prop.alamat.toLowerCase().includes(searchVal);
                var matchesReg = (prop.register_b && prop.register_b.toLowerCase().includes(searchVal)) || 
                                 (prop.register && prop.register.toLowerCase().includes(searchVal));

                if (matchesAlamat || matchesReg) {
                    // Fokus ke lokasi
                    map.fitBounds(layer.getBounds(), { padding: [50, 50], maxZoom: 18 });
                    layer.openPopup();
                    
                    // Efek Highlight (Kuning) selama 3 detik
                    layer.setStyle({ color: 'yellow', weight: 6, fillOpacity: 0.7 }); 
                    setTimeout(() => { polygonsLayer.resetStyle(layer); }, 3000);
                    
                    found = true;
                }
            });

            if (!found) {
                Swal.fire({ icon: 'info', title: 'Info', text: 'Aset tidak ditemukan di peta.' });
            }
        });

        // --- 5. AUTOCOMPLETE LOGIC ---
        $('#search').on('keyup', function() {
            var search = $(this).val();
            var suggBox = $('#suggestions');

            if (search.length >= 2) {
                $.ajax({
                    url: '<?= base_url('peta/search'); ?>', // Gunakan endpoint pencarian Anda
                    method: 'POST',
                    data: { search: search },
                    dataType: 'json',
                    success: function(data) {
                        suggBox.empty().removeClass('hidden');
                        if (data.length > 0) {
                            let list = $('<ul class="menu bg-base-100 w-full p-2 rounded-box border border-slate-200 shadow-lg"></ul>');
                            $.each(data, function(key, value) {
                                list.append(`
                                    <li>
                                        <a class="py-3 hover:bg-slate-50 border-b border-slate-50 last:border-none item-suggestion">
                                            <i class="mdi mdi-map-marker-outline text-primary"></i>
                                            <span class="text-sm font-medium">${value.permohonan_nonlit || value.alamat}</span>
                                        </a>
                                    </li>
                                `);
                            });
                            suggBox.append(list);
                        } else {
                            suggBox.append('<div class="p-4 text-center text-sm text-slate-400">Data tidak ditemukan</div>');
                        }
                    }
                });
            } else {
                suggBox.empty().addClass('hidden');
            }
        });

        // Klik pada hasil saran autocomplete
        $(document).on('click', '.item-suggestion', function() {
            var name = $(this).find('span').text();
            $('#search').val(name);
            $('#suggestions').addClass('hidden');
            $('#searchForm').submit();
        });

        // Tutup saran jika klik di luar area
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#searchForm').length) {
                $('#suggestions').addClass('hidden');
            }
        });

        // Pastikan ukuran peta terhitung ulang (penting untuk layout responsif)
        setTimeout(() => map.invalidateSize(), 500);
    });
</script>