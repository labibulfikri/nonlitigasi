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
        // 1. Inisialisasi Peta
        var map = L.map('map', {
            zoomControl: false // Kita pindahkan zoom control ke kanan agar bersih
        }).setView([-7.2732, 112.7212], 13);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        // 2. Layer Peta
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

        // 3. Load GeoJSON Awal
        var polygons = <?php echo $polygons; ?>;
        var polygonsLayer = L.geoJSON(polygons, {
            style: {
                color: '#3b82f6',
                weight: 2,
                fillOpacity: 0.2
            },
            onEachFeature: function(feature, layer) {
                if (feature.properties && feature.properties.ALAMAT) {
                    let p = feature.properties;
                    let popupHtml = `
                        <div class="p-1 min-w-[250px]">
                            <div class="bg-primary text-white p-3 rounded-t-lg -m-4 mb-2">
                                <h4 class="font-black text-[10px] uppercase tracking-widest">Informasi Detail Aset</h4>
                            </div>
                            <div class="overflow-x-auto mt-4">
                                <table class="table table-xs w-full">
                                    <tr><th class="opacity-50">ALAMAT</th><td class="font-bold text-slate-700">${p.ALAMAT}</td></tr>
                                    <tr><th class="opacity-50">ID ASET</th><td>${p.id_aset}</td></tr>
                                    <tr><th class="opacity-50">WILAYAH</th><td>${p.KELURAHAN}, ${p.KECAMATAN}</td></tr>
                                    <tr><th class="opacity-50">SERTIFIKAT</th><td><span class="badge badge-ghost badge-sm font-mono">${p.NO_SERTIFI || '-'}</span></td></tr>
                                </table>
                            </div>
                        </div>
                    `;
                    layer.bindPopup(popupHtml);
                }
            }
        }).addTo(map);

        // 4. Autocomplete Logic
        $('#search').on('keyup', function() {
            var search = $(this).val();
            var suggBox = $('#suggestions');

            if (search.length >= 2) {
                $.ajax({
                    url: '<?= base_url('peta/search2'); ?>',
                    method: 'POST',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    success: function(data) {
                        suggBox.empty().removeClass('hidden');
                        if (data.length > 0) {
                            let list = $('<ul class="menu bg-base-100 w-full p-2 rounded-box border border-slate-200"></ul>');
                            $.each(data, function(key, value) {
                                list.append(`
                                    <li>
                                        <a class="py-3 hover:bg-slate-50 border-b border-slate-50 last:border-none item-suggestion">
                                            <i class="mdi mdi-map-marker-outline text-primary"></i>
                                            <span class="text-sm font-medium">${value.permohonan_nonlit}</span>
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

        // Click Suggestion
        $(document).on('click', '.item-suggestion', function() {
            var name = $(this).find('span').text();
            $('#search').val(name);
            $('#suggestions').addClass('hidden');
            $('#searchForm').submit();
        });

        // Close suggestion when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#searchForm').length) {
                $('#suggestions').addClass('hidden');
            }
        });

        // 5. Search Submission
        $('#searchForm').submit(function(e) {
            e.preventDefault();
            var searchVal = $('#search').val();

            $.ajax({
                url: '<?php echo site_url('peta/search'); ?>',
                type: 'GET',
                data: {
                    search: searchVal
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    polygonsLayer.clearLayers();

                    var geojsonData = {
                        "type": "FeatureCollection",
                        "features": result.map(function(record) {
                            return JSON.parse(record.kordinat);
                        }).flat()
                    };

                    polygonsLayer.addData(geojsonData);
                    if (geojsonData.features.length > 0) {
                        map.fitBounds(polygonsLayer.getBounds(), {
                            padding: [50, 50]
                        });
                    }
                }
            });
        });

        // Invalidate map size after 500ms for flex layout
        setTimeout(() => map.invalidateSize(), 500);
    });
</script>