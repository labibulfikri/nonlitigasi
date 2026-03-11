<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="p-6 bg-base-200 min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800 uppercase tracking-tight">Update Area Peta</h1>
            <p class="text-slate-500 text-xs font-medium">Visualisasi data aset dan permohonan nonlitigasi Kota Surabaya</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('nonlit') ?>" class="btn btn-sm btn-ghost bg-white border-slate-200 shadow-sm rounded-lg normal-case">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
            <div id="status-polygon" class="badge badge-primary py-4 px-4 font-bold uppercase text-[10px]">
                Mengecek Data...
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="card bg-base-100 shadow-xl border border-base-300 rounded-2xl overflow-hidden">
                <div class="card-body p-0">
                    <div id="map2" class="w-full h-[750px] z-10"></div>
                    <input type="hidden" value="<?php echo $id ?>" id="id_nonlits">
                </div>

                <div class="bg-slate-50 p-3 flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase border-t border-base-300">
                    <div class="flex items-center gap-1"><i class="mdi mdi-mouse"></i> Klik 'Finish' untuk mengakhiri gambar</div>
                    <div class="flex items-center gap-1 text-primary"><i class="mdi mdi-check-circle"></i> Dialog simpan akan muncul otomatis</div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-4">
            <div class="card bg-base-100 shadow-xl border border-base-300 rounded-2xl">
                <div class="card-body p-5">
                    <h3 class="text-sm font-black text-slate-800 mb-4 flex items-center gap-2 uppercase tracking-tighter border-b pb-2">
                        <i class="mdi mdi-information-outline text-primary text-lg"></i> Detail Informasi
                    </h3>
                    <div class="max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        <?php $this->load->view($tab) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const id_nonlits = document.getElementById('id_nonlits').value;
        const geojsonData = <?php echo $polygon; ?>;
        const statusEl = document.getElementById('status-polygon');

        // 1. Inisialisasi Peta
        var map = L.map('map2').setView([-7.273, 112.721], 13);

        let streets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        L.control.layers({
            "Jalan": streets,
            "Satelit": satellite
        }, null, {
            position: 'topright'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // 2. Load Data Existing
        if (geojsonData && geojsonData.geometry && geojsonData.geometry.coordinates.length > 0) {
            L.geoJSON(geojsonData, {
                onEachFeature: function(feature, layer) {
                    drawnItems.addLayer(layer);
                }
            });
            map.fitBounds(drawnItems.getBounds());
            statusEl.innerHTML = "AREA TERSEDIA";
        } else {
            statusEl.innerHTML = "KOORDINAT KOSONG";
            statusEl.classList.replace('badge-primary', 'badge-ghost');
        }

        // 3. Toolbar Konfigurasi
        var drawControl = new L.Control.Draw({
            draw: {
                polygon: {
                    allowIntersection: false,
                    shapeOptions: {
                        color: '#3b82f6'
                    }
                },
                polyline: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: true
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        map.addControl(drawControl);

        // 4. Fungsi Simpan yang Diperbaiki
        function saveToServer(data, endpoint, confirmText) {
            Swal.fire({
                title: 'Konfirmasi',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-ghost'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`<?php echo base_url() ?>peta/${endpoint}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                geojson: data,
                                id: id_nonlits
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.status === 'success') {
                                Swal.fire('Berhasil!', 'Data telah diperbarui.', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', res.message || 'Terjadi kesalahan.', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                        });
                } else {
                    // Jika batal, reload agar tampilan peta kembali ke kondisi database terakhir
                    location.reload();
                }
            });
        }

        // --- PERBAIKAN EVENT LISTENERS ---

        // A. SAAT SELESAI MENGGAMBAR BARU
        map.on(L.Draw.Event.CREATED, function(e) {
            var layer = e.layer;
            drawnItems.addLayer(layer);
            // Mengirim objek GeoJSON tunggal
            saveToServer(layer.toGeoJSON(), 'save_new_data', 'Simpan area baru ini?');
        });

        // B. SAAT SELESAI EDIT (MENGGESER TITIK)
        map.on(L.Draw.Event.EDITED, function(e) {
            var layers = e.layers;
            layers.eachLayer(function(layer) {
                // PENTING: Mengirim koordinat terbaru setelah digeser
                saveToServer(layer.toGeoJSON(), 'save_edited_data', 'Simpan perubahan koordinat ini?');
            });
        });

        // C. SAAT MENGHAPUS AREA
        map.on(L.Draw.Event.DELETED, function(e) {
            // Jika semua layer dihapus, kirim data kosong/null ke server
            if (drawnItems.getLayers().length === 0) {
                saveToServer(null, 'delete_data', 'Hapus permanen koordinat dari database?');
            }
        });

        setTimeout(() => {
            map.invalidateSize();
        }, 500);
    });
</script>

<style>
    .leaflet-draw-toolbar a {
        background-color: white !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>