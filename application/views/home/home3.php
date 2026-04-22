<style>
    /* Tambahkan di file CSS Anda jika belum ada */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
</style>
<div class="space-y-8 animate-fade-in p-2 lg:p-4">

    <div class="hero min-h-[300px] rounded-[2.5rem] bg-slate-900 overflow-hidden relative shadow-2xl border border-white/5">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/20 blur-[100px] rounded-full animate-pulse"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-600/20 blur-[100px] rounded-full animate-pulse" style="animation-delay: 2s"></div>
        </div>

        <div class="hero-content flex-col lg:flex-row-reverse p-8 lg:p-14 gap-12 relative z-10 w-full max-w-none justify-between">
            <div class="flex-1 flex justify-center lg:justify-end">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-sky-400 to-blue-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <img src="<?php echo base_url('assets/bpkad_ikon.png') ?>" class="max-w-[180px] md:max-w-sm drop-shadow-2xl relative transition-transform duration-500 hover:scale-105" />
                </div>
            </div>

            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/10 text-sky-300 mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                    </span>
                    <span class="text-[10px] font-black tracking-[0.3em] uppercase">E-NONLIT OFFICIAL SYSTEM</span>
                </div>
                <h1 class="text-2xl lg:text-2xl font-black text-white leading-tight mb-6 tracking-tighter">
                    Sistem Informasi <br />
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-sky-400 via-blue-200 to-white uppercase">Non-Litigasi</span>
                </h1>
                <p class="text-slate-300 text-sm lg:text-lg max-w-xl leading-relaxed font-light italic border-l-4 border-sky-500 pl-6 bg-white/5 py-2 rounded-r-xl">
                    Pusat kendali manajemen data bantuan hukum dan penanganan perkara Non-Litigasi Bidang Pengamanan dan Penyelesaian Sengketa BMD Kota Surabaya.
                </p>
            </div>
        </div>
    </div>
 
    <div class="card bg-white/90 backdrop-blur-2xl shadow-2xl border border-slate-200 -mt-12 mx-4 lg:mx-12 relative z-20 rounded-3xl transition-all hover:shadow-blue-500/10">
        <div class="card-body p-8">
            <div class="flex flex-col md:flex-row gap-6 items-end text-slate-700">
                <div class="form-control flex-1 w-full">
                    <?= crsf_ajax() ?>
                    <label class="label"><span class="label-text font-black text-slate-500 text-[10px] uppercase tracking-[0.2em]">Periode Tahun</span></label>
                    <div class="relative">
                        <i class="mdi mdi-calendar absolute left-4 top-3 text-xl text-blue-500"></i>
                        <select class="select select-bordered w-full bg-slate-50 pl-12 focus:ring-2 focus:ring-blue-500 border-slate-200 font-bold" id="filter_tahun">
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
                </div>
                <div class="form-control flex-1 w-full">
                    <label class="label"><span class="label-text font-black text-slate-500 text-[10px] uppercase tracking-[0.2em]">Status Berkas</span></label>
                    <div class="relative">
                        <i class="mdi mdi-filter-variant absolute left-4 top-3 text-xl text-blue-500"></i>
                        <select class="select select-bordered w-full bg-slate-50 pl-12 focus:ring-2 focus:ring-blue-500 border-slate-200 font-bold" id="filter_status">
                            <option value="all">Semua Status</option>
                            <option value="proses">Sedang Diproses</option>
                            <option value="selesai">Sudah Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="md:w-56 w-full">
                    <button type="button" id="btnFilter" class="btn btn-primary w-full shadow-xl shadow-blue-500/30 gap-3 font-black text-white rounded-xl hover:scale-[1.02] active:scale-95 transition-all h-12">
                        <i class="mdi mdi-magnify text-xl"></i> TAMPILKAN DATA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="stats bg-white shadow-xl border border-slate-100 group transition-all duration-500 hover:translate-y-[-5px]">
            <div class="stat p-8 relative overflow-hidden">
                <div class="stat-figure text-blue-500/10 absolute -right-4 -bottom-4 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-folder-multiple text-[120px]"></i>
                </div>
                <div class="stat-title font-bold text-slate-400 uppercase text-[10px] tracking-widest">Total Kasus</div>
                <div class="stat-value text-slate-800 text-6xl font-black tracking-tighter leading-none my-2" id="totalKasus">0</div>
                <div class="stat-desc font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-lg inline-block mt-2">ARSIP KESELURUHAN</div>
            </div>
        </div>

        <div class="stats bg-white shadow-xl border border-slate-100 group transition-all duration-500 hover:translate-y-[-5px]">
            <div class="stat p-8 relative overflow-hidden">
                <div class="stat-figure text-amber-500/10 absolute -right-4 -bottom-4 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-clock-alert text-[120px]"></i>
                </div>
                <div class="stat-title font-bold text-slate-400 uppercase text-[10px] tracking-widest">Dalam Proses</div>
                <div class="stat-value text-amber-500 text-6xl font-black tracking-tighter leading-none my-2" id="totalProses">0</div>
                <div class="stat-desc font-black text-amber-600 bg-amber-50 px-3 py-1 rounded-lg inline-block mt-2 tracking-tighter uppercase">Butuh Tindak Lanjut</div>
            </div>
        </div>

        <div class="stats bg-white shadow-xl border border-slate-100 group transition-all duration-500 hover:translate-y-[-5px]">
            <div class="stat p-8 relative overflow-hidden">
                <div class="stat-figure text-emerald-500/10 absolute -right-4 -bottom-4 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-check-decagram text-[120px]"></i>
                </div>
                <div class="stat-title font-bold text-slate-400 uppercase text-[10px] tracking-widest">Selesai</div>
                <div class="stat-value text-emerald-500 text-6xl font-black tracking-tighter leading-none my-2" id="totalSelesai">0</div>
                <div class="stat-desc font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg inline-block mt-2 tracking-tighter uppercase">Berhasil Verifikasi</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="card bg-white shadow-2xl border border-slate-100 overflow-hidden h-full">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="font-black text-slate-700 text-xl tracking-tight uppercase italic">Rekap Per Kejaksaan</h3>
                        <p class="text-[10px] text-slate-400 font-bold tracking-widest mt-1">DISTRIBUSI INSTANSI PENANGANAN</p>
                    </div>
                    <div class="badge badge-info bg-blue-600 border-none text-[9px] font-black text-white px-4 py-3 animate-pulse">
                        KLIK BATANG UNTUK DETAIL
                    </div>
                </div>
                <div class="card-body p-8">
                    <div class="h-[400px] relative">
                        <canvas id="kejaksaanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="card bg-white shadow-2xl border border-slate-100 overflow-hidden h-full">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-black text-slate-700 text-xl tracking-tight text-center uppercase italic">Rasio Penyelesaian</h3>
                </div>
                <div class="card-body p-8 flex flex-col items-center justify-center">
                    <div class="h-[300px] w-full relative">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="grid grid-cols-2 gap-4 w-full mt-8">
                        <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100 text-center transition-all hover:bg-emerald-100/50">
                            <span class="block text-[10px] font-black text-emerald-600 uppercase tracking-tighter mb-1">SELESAI</span>
                            <span id="pie_selesai_val" class="text-3xl font-black text-emerald-700 leading-none">0</span>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 text-center transition-all hover:bg-amber-100/50">
                            <span class="block text-[10px] font-black text-amber-600 uppercase tracking-tighter mb-1">PROSES</span>
                            <span id="pie_proses_val" class="text-3xl font-black text-amber-700 leading-none">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_detail" class="modal modal-bottom sm:modal-middle backdrop-blur-md transition-all duration-500">
    <div class="modal-box w-11/12 max-w-6xl bg-white p-0  rounded-[2rem] shadow-2xl border border-white/20">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-900 p-8 flex justify-between items-center text-white relative">
            <div class="absolute top-0 right-0 p-6 opacity-10 pointer-events-none">
                <i class="mdi mdi-database-search text-[150px]"></i>
            </div>
            <div class="relative z-10">
                <div class="badge badge-sky-400 bg-white/20 text-white font-black text-[10px] border-none px-4 py-3 mb-3">DATA EXPLORER</div>
                <h3 id="modal_title" class="font-black text-3xl tracking-tighter uppercase italic leading-none"></h3>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost bg-black/20 hover:bg-red-500 hover:text-white transition-all text-white border-none w-10 h-10">✕</button>
            </form>
        </div>

        <div class="p-8 bg-slate-50/50">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="table w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-800 text-white border-none">
                                <th class="py-5 px-6 font-black text-[11px] tracking-widest uppercase rounded-tl-3xl">NO</th>
                                <th class="py-5 px-6 font-black text-[11px] tracking-widest uppercase">Nama Permohonan</th>
                                <th class="py-5 px-6 font-black text-[11px] tracking-widest uppercase">Keterangan Perkara</th>
                                <th class="py-5 px-6 font-black text-[11px] tracking-widest uppercase text-center">Tanggal</th>
                                <th class="py-5 px-6 font-black text-[11px] tracking-widest uppercase text-center rounded-tr-3xl">Status</th>
                            </tr>
                        </thead>
                        <tbody id="detail_body" class="divide-y divide-slate-100">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-slate-900/80"><button>close</button></form>
</dialog>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .loading-state {
        opacity: 0.3;
        pointer-events: none;
        filter: blur(2px);
        transition: all 0.4s ease;
    }

    /* Table Styling */
    .table tbody tr:hover {
        background-color: #f8fafc;
        cursor: default;
    }

    .badge-success {
        background-color: #10b981;
        border: none;
        color: white;
    }

    .badge-warning {
        background-color: #f59e0b;
        border: none;
        color: white;
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
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
                    $('#btnFilter').prop('disabled', isLoading).html(isLoading ? '<span class="loading loading-spinner"></span>' : '<i class="mdi mdi-magnify text-xl"></i> TAMPILKAN DATA');
                }

                function loadDashboardData() {
                    toggleLoading(true);
                    const param = {
                        tahun: $('#filter_tahun').val(),
                        status: $('#filter_status').val(),
                        token: $('#token').val()
                    };

                    $.ajax({
                        url: "<?= base_url('home/get_data_chart') ?>",
                        type: "POST",
                        data: param,
                        dataType: "JSON",
                        success: function(res) {
                            // Update Stat Cards
                            $('#totalKasus').fadeOut(200, function() {
                                $(this).text(res.total || 0).fadeIn(200);
                            });
                            $('#totalProses').fadeOut(200, function() {
                                $(this).text(res.total_proses || 0).fadeIn(200);
                            });
                            $('#totalSelesai').fadeOut(200, function() {
                                $(this).text(res.total_selesai || 0).fadeIn(200);
                            });

                            $('#pie_selesai_val').text(res.total_selesai || 0);
                            $('#pie_proses_val').text(res.total_proses || 0);

                            renderPieChart(res.pie);
                            renderKejaksaanChart(res.bar);
                        },
                        complete: function() {
                            toggleLoading(false);
                        }
                    });
                }

                function renderKejaksaanChart(dataBar) {
                    if (kejaksaanChart) kejaksaanChart.destroy();
                    const ctx = document.getElementById('kejaksaanChart').getContext('2d');

                    const labels = ['kejati', 'kejari_sby', 'kejari_perak'];
                    const findVal = (team, status) => {
                        const row = dataBar.find(i => i.team_nonlit === team);
                        if (!row) return 0;
                        return parseInt(status === 'selesai' ? row.selesai : row.proses);
                    };

                    kejaksaanChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'Selesai',
                                    data: labels.map(l => findVal(l, 'selesai')),
                                    backgroundColor: '#10b981',
                                    borderRadius: 12,
                                    borderSkipped: false,
                                    barThickness: 45
                                },
                                {
                                    label: 'Proses',
                                    data: labels.map(l => findVal(l, 'proses')),
                                    backgroundColor: '#f59e0b',
                                    borderRadius: 12,
                                    borderSkipped: false,
                                    barThickness: 45
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                }
                            },
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
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        drawBorder: false,
                                        color: '#f1f5f9'
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            weight: '800'
                                        },
                                        callback: function(v) {
                                            return this.getLabelForValue(v).replace('_', ' ').toUpperCase();
                                        }
                                    }
                                }
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
                                backgroundColor: ['#10b981', '#f59e0b'],
                                borderWidth: 0,
                                hoverOffset: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '80%',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                function showDetailModal(team, status) {
                    const tahun = $('#filter_tahun').val();
                    const token = $('#token').val();

                    $('#modal_title').html(`<span class="text-blue-300 font-normal">TIM</span> ${team.replace('_', ' ')} <span class="mx-2 text-white/30">|</span> <span class="text-amber-400 italic">${status}</span>`);
                    $('#detail_body').html('<tr><td colspan="5" class="text-center py-20"><span class="loading loading-spinner loading-lg text-primary"></span><p class="mt-4 text-slate-400 font-bold animate-pulse">MEMUAT DATA PERKARA...</p></td></tr>');
                    document.getElementById('modal_detail').showModal();

                    $.post("<?= base_url('home/get_data_detail') ?>", {
                        tahun: tahun,
                        status: status,
                        team_nonlit: team,
                        token: token
                    }, function(data) {
                        let html = '';
                        if (data && data.length > 0) {
                            data.forEach((item, i) => {
                                html += `
                            <tr class="hover:bg-blue-50/50 transition-all border-b border-slate-50">
                                <td class="font-black text-slate-400 px-6 py-4">${i+1}</td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-blue-700 leading-tight uppercase tracking-tight">${item.permohonan_nonlit}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[11px] font-semibold text-slate-500 max-w-xs leading-relaxed italic">${item.keterangan || '-'}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-600">${item.tgl_nonlit}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="badge ${item.status=='selesai'?'badge-success':'badge-warning'} font-black text-[9px] px-3 py-3 uppercase tracking-tighter">
                                        ${item.status}
                                    </span>
                                </td>
                            </tr>`;
                            });
                        } else {
                            html = '<tr><td colspan="5" class="text-center py-20 text-slate-400 font-black uppercase tracking-[0.2em] opacity-50">TIDAK ADA DATA DITEMUKAN</td></tr>';
                        }
                        $('#detail_body').html(html);
                    }, "JSON");
                }

                $('#btnFilter').on('click', loadDashboardData);
                loadDashboardData();
            });
        } else {
            setTimeout(initDashboard, 50);
        }
    }
    initDashboard();
</script>