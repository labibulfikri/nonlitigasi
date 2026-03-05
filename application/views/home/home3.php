<div class="space-y-8 animate-fade-in">
    <div class="hero bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 rounded-3xl text-primary-content overflow-hidden shadow-xl border border-white/10">
        <div class="hero-content flex-col lg:flex-row-reverse p-8 lg:p-12 gap-10">
            <div class="flex-1 flex justify-center relative">
                <div class="absolute w-64 h-64 bg-sky-400/20 blur-3xl rounded-full -top-10 -right-10 animate-pulse"></div>
                <img src="<?php echo base_url('assets/bpkad_ikon.png') ?>" class="max-w-xs md:max-w-sm drop-shadow-[0_20px_50px_rgba(0,0,0,0.4)] hover:scale-105 transition-all duration-700 relative z-10" />
            </div>
            <div class="flex-1 text-center lg:text-left z-10">
                <div class="badge badge-sky-400 bg-sky-400/20 border-sky-400/30 text-sky-200 font-extrabold mb-4 px-4 py-3 tracking-widest text-[10px]">E-NONLIT SYSTEM</div>
                <h1 class="text-4xl lg:text-6xl font-extrabold leading-[1.1] mb-4 tracking-tighter">
                    Sistem Informasi <br/>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-sky-300 to-white">Non-Litigasi</span>
                </h1>
                <p class="py-6 text-base lg:text-lg opacity-80 font-medium max-w-lg leading-relaxed">
                    Pusat kendali manajemen data bantuan hukum dan penanganan perkara Non-Litigasi Bidang Pengamanan dan Penyelesaian Sengketa Barang Milik  Daerah Kota Surabaya.
                </p>
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-sm border border-slate-200/60">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="form-control md:col-span-4">
                    <?= crsf_ajax() ?>
                    <label class="label py-1"><span class="label-text font-bold text-slate-600 text-xs">Periode Tahun</span></label>
                    <select class="select select-bordered w-full bg-slate-50" id="filter_tahun">
                        <?php
                        $mulai = date('Y') - 5;
                        for ($i = $mulai; $i <= date('Y') + 1; $i++) {
                            $sel = $i == date('Y') ? ' selected' : '';
                            echo '<option value="' . $i . '"' . $sel . '>' . $i . '</option>';
                        }
                        ?>
                        <option value="all">Semua Tahun</option>
                    </select>
                </div>
                <div class="form-control md:col-span-5">
                    <label class="label py-1"><span class="label-text font-bold text-slate-600 text-xs">Status Berkas</span></label>
                    <select class="select select-bordered w-full bg-slate-50" id="filter_status">
                        <option value="all">Semua Status</option>
                        <option value="proses">Sedang Diproses</option>
                        <option value="selesai">Sudah Selesai</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <button type="button" id="btnFilter" class="btn btn-primary w-full shadow-lg gap-2 font-bold">
                        <i class="mdi mdi-magnify"></i> Tampilkan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stats bg-white border border-slate-200 shadow-sm overflow-hidden group">
            <div class="stat p-6 relative">
                <div class="stat-title font-bold text-slate-400 uppercase text-[10px] tracking-[0.2em]">Total Kasus</div>
                <div class="stat-value text-slate-800 text-4xl font-black my-1" id="totalKasus">0</div>
                <!-- <div class="stat-desc font-semibold text-slate-400">Arsip keseluruhan</div> -->
            </div>
        </div>
        <div class="stats bg-white border border-slate-200 shadow-sm overflow-hidden group">
            <div class="stat p-6 relative">
                <div class="stat-title font-bold text-slate-400 uppercase text-[10px] tracking-[0.2em]">Dalam Proses</div>
                <div class="stat-value text-amber-500 text-4xl font-black my-1" id="totalProses">0</div>
                <!-- <div class="stat-desc font-semibold text-amber-600/70">Butuh tindak lanjut</div> -->
            </div>
        </div>
        <div class="stats bg-white border border-slate-200 shadow-sm overflow-hidden group">
            <div class="stat p-6 relative">
                <div class="stat-title font-bold text-slate-400 uppercase text-[10px] tracking-[0.2em]">Selesai</div>
                <div class="stat-value text-emerald-500 text-4xl font-black my-1" id="totalSelesai">0</div>
                <!-- <div class="stat-desc font-semibold text-emerald-600/70">Berhasil diverifikasi</div> -->
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="card bg-white shadow-sm border border-slate-200 overflow-hidden h-full">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="mdi mdi-bank text-indigo-600"></i> Rekap Per Kejaksaan
                    </h3>
                    <div class="badge badge-info text-white font-bold text-[9px] animate-pulse">KLIK BATANG UNTUK DETAIL</div>
                </div>
                <div class="card-body p-6">
                    <div class="h-[400px]">
                        <canvas id="kejaksaanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="card bg-white shadow-sm border border-slate-200 overflow-hidden h-full">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="mdi mdi-chart-pie text-blue-600"></i> Rasio Penyelesaian
                    </h3>
                </div>
                <div class="card-body p-6 flex flex-col justify-center">
                    <div class="h-[300px]">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-500">SELESAI</span>
                            <span id="pie_selesai_val" class="text-emerald-600">0</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-500">PROSES</span>
                            <span id="pie_proses_val" class="text-amber-600">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_detail" class="modal">
  <div class="modal-box w-11/12 max-w-5xl bg-white p-0   shadow-2xl">
    <div class="bg-blue-600 p-4 flex justify-between items-center text-white">
        <h3 class="font-bold text-lg flex items-center gap-3">
            <i class="mdi mdi-database-search"></i>
            <span id="modal_title" class="font-black tracking-tight uppercase"></span>
        </h3>
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
        </form>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="w-10">No</th>
                        <th>Nama Permohonan</th>
                        <th>Keterangan Perkara</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="detail_body"></tbody>
            </table>
        </div>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    .loading-state { opacity: 0.4; pointer-events: none; filter: grayscale(1); transition: all 0.4s; }
    canvas { cursor: pointer; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function initDashboard() {
    if (window.jQuery && window.Chart) {
        $(document).ready(function() {
            let pieChart, kejaksaanChart;

            function toggleLoading(isLoading) {
                const targets = $('.stats, .card, #btnFilter');
                isLoading ? targets.addClass('loading-state') : targets.removeClass('loading-state');
                $('#btnFilter').prop('disabled', isLoading).html(isLoading ? '<span class="loading loading-spinner"></span>' : '<i class="mdi mdi-magnify"></i> Tampilkan Data');
            }

            function loadDashboardData() {
                toggleLoading(true);
                const param = {
                    tahun: $('#filter_tahun').val(),
                    status: $('#filter_status').val(),
                    token : $('#token').val()
                };

                $.ajax({
                    url: "<?= base_url('home/get_data_chart') ?>",
                    type: "POST",
                    data: param,
                    dataType: "JSON",
                    success: function(res) {
                        $('#totalKasus').text(res.total || 0);
                        $('#totalProses').text(res.total_proses || 0);
                        $('#totalSelesai').text(res.total_selesai || 0);
                        
                        $('#pie_selesai_val').text(res.total_selesai || 0);
                        $('#pie_proses_val').text(res.total_proses || 0);

                        renderPieChart(res.pie);
                        renderKejaksaanChart(res.bar); // Menggunakan res.bar sebagai sumber data utama
                    },
                    complete: function() { toggleLoading(false); }
                });
            }

            function renderKejaksaanChart(dataBar) {
                if (kejaksaanChart) kejaksaanChart.destroy();
                const ctx = document.getElementById('kejaksaanChart').getContext('2d');
                
                // Definisi label tetap
                const labels = ['kejati', 'kejari_sby', 'kejari_perak'];
                
                // Mencari data di res.bar secara dinamis
                const findVal = (team, status) => {
                    const row = dataBar.find(i => i.team_nonlit === team);
                    if(!row) return 0;
                    return parseInt(status === 'selesai' ? row.selesai : row.proses);
                };

                kejaksaanChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Selesai',
                                data: labels.map(l => findVal(l, 'selesai')),
                                backgroundColor: '#10b981', borderRadius: 6
                            },
                            {
                                label: 'Proses',
                                data: labels.map(l => findVal(l, 'proses')),
                                backgroundColor: '#f59e0b', borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (e, el) => {
                            if (el.length > 0) {
                                const idx = el[0].index;
                                const dsIdx = el[0].datasetIndex;
                                const team = kejaksaanChart.data.labels[idx];
                                const status = kejaksaanChart.data.datasets[dsIdx].label.toLowerCase();
                                showDetailModal(team, status);
                            }
                        },
                        scales: { 
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { ticks: { callback: function(v) { return this.getLabelForValue(v).replace('_', ' ').toUpperCase(); } } }
                        }
                    }
                });
            }

            function renderPieChart(dataPie) {
                if (pieChart) pieChart.destroy();
                const ctx = document.getElementById('pieChart').getContext('2d');
                pieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Proses'],
                        datasets: [{
                            data: [parseInt(dataPie.selesai || 0), parseInt(dataPie.proses || 0)],
                            backgroundColor: ['#10b981', '#f59e0b']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%' }
                });
            }

            function showDetailModal(team, status) {
                const tahun = $('#filter_tahun').val();
                const token = $('#token').val()
                $('#modal_title').text(`${team.replace('_', ' ')} (${status})`);
                $('#detail_body').html('<tr><td colspan="5" class="text-center py-10"><span class="loading loading-spinner"></span></td></tr>');
                document.getElementById('modal_detail').showModal();

                $.post("<?= base_url('home/get_data_detail') ?>", {
                    tahun: tahun, status: status, team_nonlit: team,
                    token:token
                }, function(data) {
                    let html = '';
                    if(data && data.length > 0) {
                        data.forEach((item, i) => {
                            html += `<tr>
                                <td class="font-bold">${i+1}</td>
                                <td class="text-blue-700 font-semibold">${item.permohonan_nonlit}</td>
                                <td class="text-xs italic">${item.keterangan || '-'}</td>
                                <td>${item.tgl_nonlit}</td>
                                <td class="text-center"><span class="badge ${item.status=='selesai'?'badge-success':'badge-warning'} text-white font-bold text-[10px] uppercase">${item.status}</span></td>
                            </tr>`;
                        });
                    } else { html = '<tr><td colspan="5" class="text-center py-10">Data Kosong</td></tr>'; }
                    $('#detail_body').html(html);
                }, "JSON");
            }

            $('#btnFilter').on('click', loadDashboardData);
            loadDashboardData();
        });
    } else { setTimeout(initDashboard, 50); }
}
initDashboard();
</script>