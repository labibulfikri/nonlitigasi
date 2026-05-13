<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Geografis Aset - Surabaya</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- CSS & Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #map { height: 100vh; width: 100vw; z-index: 1; }
        
        /* Glassmorphism Effect */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .ui-panel { z-index: 1000; position: absolute; }
        
        /* Custom Scrollbar */
        #autocomplete-list::-webkit-scrollbar { width: 5px; }
        #autocomplete-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* Leaflet Legend Custom Styling */
        .leaflet-control-layers {
            border: none !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            border-radius: 16px !important;
            padding: 12px !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(8px);
        }

        /* Animated Search Ring */
        .search-container:focus-within {
            ring: 2px;
            ring-color: #3b82f6;
        }

        /* Popup Styling */
        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .leaflet-popup-content { margin: 0 !important; width: 280px !important; }
    </style>
</head>
<body class="bg-slate-50 overflow-hidden">

    <!-- SEARCH PANEL TOP LEFT -->
    <div class="ui-panel top-6 left-6 w-80 md:w-[400px]">
        <div class="card glass-panel shadow-2xl rounded-[24px] overflow-visible">
            <div class="p-4">
                <div class="flex items-center gap-3">
                    <div class="relative w-full group">
                        <span class="absolute inset-y-0 left-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" id="search" 
                            placeholder="Cari No. Register atau Nama Aset..." 
                            class="input input-bordered w-full pl-12 bg-white/50 border-slate-200 rounded-2xl focus:bg-white transition-all duration-300 placeholder:text-slate-400 placeholder:text-sm shadow-inner"
                            autocomplete="off" />
                    </div>
                    <button class="btn btn-primary btn-square rounded-2xl shadow-lg shadow-blue-200 border-none bg-gradient-to-tr from-blue-600 to-blue-400 text-white">
                        <i class="mdi mdi-filter-variant text-xl"></i>
                    </button>
                </div>

                <!-- AUTOCOMPLETE RESULTS -->
                <div id="autocomplete-list" class="absolute left-0 right-0 mt-3 glass-panel shadow-2xl rounded-3xl hidden z-[2000] max-h-[450px] overflow-y-auto border border-white/50 animate-in fade-in slide-in-from-top-2 duration-300">
                    <!-- Data will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <!-- MAP CONTAINER -->
    <div id="map"></div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // 1. Inisialisasi Peta
        var map = L.map('map', { 
            zoomControl: false,
            attributionControl: false 
        }).setView([-7.2575, 112.7521], 12);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Base Layers
        var satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { 
            subdomains: ['mt0','mt1','mt2','mt3'],
            maxZoom: 20
        }).addTo(map);
        
        var streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

        // Panes (Urutan Tumpukan Layer)
        map.createPane('pane_poly'); map.getPane('pane_poly').style.zIndex = 400;
        map.createPane('pane_line'); map.getPane('pane_line').style.zIndex = 500;
        map.createPane('pane_point'); map.getPane('pane_point').style.zIndex = 600;

        // 2. Render Bidang Tanah (Polygon)
        var polygons = L.geoJSON(<?php echo $polygons; ?>, {
            pane: 'pane_poly',
            style: function(f) {
                var isBermasalah = f.properties.status_aset === 'bermasalah';
                return {
                    color: isBermasalah ? '#f472b6' : '#fbbf24', // Pink vs Orange
                    fillColor: isBermasalah ? '#f472b6' : '#fbbf24',
                    weight: 2,
                    fillOpacity: 0.5
                };
            },
            onEachFeature: function(f, l) {
                var colorClass = f.properties.status_aset === 'bermasalah' ? 'bg-pink-500' : 'bg-amber-500';
                var textClass = f.properties.status_aset === 'bermasalah' ? 'text-pink-600' : 'text-amber-600';
                
                l.bindPopup(`
                    <div class="overflow-hidden">
                        <div class="${colorClass} p-4 text-white">
                            <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest">Register Aset</p>
                            <h3 class="text-lg font-extrabold leading-none">${f.properties.simbada}</h3>
                        </div>
                        <div class="p-4 bg-white space-y-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Status</p>
                                <p class="text-sm font-bold ${textClass}">${f.properties.status_aset.toUpperCase()}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Lokasi / Alamat</p>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">${f.properties.alamat || 'Tidak ada alamat terdaftar'}</p>
                            </div>
                        </div>
                    </div>
                `);
            }
        }).addTo(map);

        // 3. Render Titik Pengamanan (Point)
        var points = L.geoJSON(<?php echo $points; ?>, {
            pane: 'pane_point',
            pointToLayer: function(f, latlng) {
                return L.circleMarker(latlng, {
                    radius: 8,
                    fillColor: "#3b82f6",
                    color: "#ffffff",
                    weight: 3,
                    fillOpacity: 1
                });
            },
            onEachFeature: function(f, l) {
                l.bindPopup(`<div class="p-4 text-xs font-bold text-blue-600"><i class="mdi mdi-shield-check mr-2"></i> ${f.properties.nama}</div>`);
            }
        }).addTo(map);

        // 4. Render Batas Pagar (Line)
        var lines = L.geoJSON(<?php echo $lines; ?>, {
            pane: 'pane_line',
            style: { color: "#10b981", weight: 4, dashArray: '8, 12' },
            onEachFeature: function(f, l) {
                l.bindPopup(`<div class="p-4 text-xs font-bold text-green-600"><i class="mdi mdi-vector-line mr-2"></i> ${f.properties.nama}</div>`);
            }
        }).addTo(map);

        // 5. Legend Control
        L.control.layers(
            { '<span class="text-xs font-bold text-slate-600"> Satelit</span>': satellite, '<span class="text-xs font-bold text-slate-600"> Jalan</span>': streets },
            { 
                '<span class="text-xs font-bold text-slate-600"> Bidang Tanah</span>': polygons, 
                '<span class="text-xs font-bold text-slate-600"> Titik Pagar</span>': points, 
                '<span class="text-xs font-bold text-slate-600"> Garis Batas</span>': lines 
            },
            { collapsed: false, position: 'topright' }
        ).addTo(map);

        // 6. Search Logic
        $('#search').on('keyup', function() {
            let val = $(this).val().trim();
            if (val.length < 3) { $('#autocomplete-list').addClass('hidden'); return; }

            $.post("<?= base_url('peta/ajax_search') ?>", { keyword: val }, function(res) {
                let list = $('#autocomplete-list').empty().removeClass('hidden');
                let data = JSON.parse(res);
                
                if (data.length > 0) {
                    data.forEach(item => {
                        let statusColor = item.has_map ? 'bg-green-500' : 'bg-slate-300';
                        list.append(`
                            <div class="p-4 hover:bg-blue-50/50 cursor-pointer flex items-center gap-4 transition-all select-item group" data-reg="${item.register}" data-map="${item.has_map}">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center ${item.has_map ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-400'}">
                                    <i class="mdi ${item.has_map ? 'mdi-map-check' : 'mdi-map-marker-off'} text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-slate-800">${item.register}</div>
                                    <div class="text-[11px] text-slate-500 truncate font-medium">${item.permohonan}</div>
                                </div>
                                <div class="w-2 h-2 rounded-full ${statusColor} group-hover:scale-125 transition-transform"></div>
                            </div>
                        `);
                    });
                } else {
                    list.append('<div class="p-6 text-center text-slate-400 text-sm font-medium italic"><i class="mdi mdi-database-off-outline block text-2xl mb-2"></i> Data tidak ditemukan</div>');
                }
            });
        });

        // 7. Auto Zoom Action
        $(document).on('click', '.select-item', function() {
            let reg = $(this).data('reg').toString().trim();
            let hasMap = $(this).data('map');
            let found = false;

            if(hasMap == 1) {
                polygons.eachLayer(function(layer) {
                    if (layer.feature.properties.simbada === reg) {
                        map.flyToBounds(layer.getBounds(), { 
                            padding: [120, 120], 
                            maxZoom: 18, 
                            duration: 1.5 
                        });
                        setTimeout(() => { layer.openPopup(); }, 1600);
                        found = true;
                    }
                });
            } else {
                Swal.fire({
                    title: 'Batas Digital Belum Ada',
                    text: 'Aset terdaftar di sistem, namun koordinat peta (polygon) belum tersedia.',
                    icon: 'warning',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'rounded-[24px]' }
                });
            }
            $('#autocomplete-list').addClass('hidden');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#search, #autocomplete-list').length) {
                $('#autocomplete-list').addClass('hidden');
            }
        });
    });
    </script>
</body>
</html>