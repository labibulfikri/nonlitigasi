<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<div class="p-6 md:p-10 bg-[#F8FAFC] min-h-screen font-sans">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">BPKAD <span class="text-blue-600"> Pemerintah Kota Surabaya</span></h1>
            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.4em] mt-2 flex items-center gap-3">
                <span class="w-12 h-[3px] bg-slate-900"></span>
                
            </p>
        </div>

        <div class="flex flex-wrap gap-4 bg-white p-3 rounded-3xl border border-slate-200 shadow-xl shadow-slate-100 w-full lg:w-auto">
            <div class="flex flex-col px-2">
                <label class="text-[9px] font-black text-slate-400 uppercase ml-1 mb-1">Periode</label>
                <select id="f-tahun" class="select select-ghost select-sm font-black text-xs rounded-xl focus:bg-slate-50">
                    <option value="">SEMUA TAHUN</option>
                    <option value="2026" selected>2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>
            </div>
            <div class="divider divider-horizontal mx-0 hidden md:flex"></div>
            <div class="flex flex-col px-2">
                <label class="text-[9px] font-black text-slate-400 uppercase ml-1 mb-1">Status Perkara</label>
                <select id="f-status" class="select select-ghost select-sm font-black text-xs rounded-xl focus:bg-slate-50">
                    <option value="">SEMUA STATUS</option>
                    <option value="proses">PROSES</option>
                    <option value="selesai">SELESAI</option>
                </select>
            </div>
            <button onclick="refreshDashboard()" class="btn btn-primary rounded-2xl px-8 italic font-black uppercase shadow-lg shadow-blue-200 ml-auto">
                Update
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden group shadow-2xl shadow-blue-100">
            <p class="text-[10px] font-black opacity-40 uppercase tracking-[0.5em] mb-2">Total Data</p>
            <h2 id="card-total" class="text-6xl font-black italic tracking-tighter">0</h2>
            <i class="mdi mdi-database absolute -right-8 -bottom-8 text-[12rem] opacity-5 group-hover:rotate-12 transition-transform duration-700"></i>
        </div>

        <div class="bg-white rounded-[3rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-2">Proses</p>
            <h2 id="card-proses" class="text-6xl font-black italic text-amber-500 tracking-tighter">0</h2>
            <div class="mt-6 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500 animate-ping"></span>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Penanganan Aktif</span>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] mb-2">Selesai</p>
            <h2 id="card-selesai" class="text-6xl font-black italic text-emerald-500 tracking-tighter">0</h2>
            <div class="mt-6 flex items-center gap-2 text-emerald-500">
                <i class="mdi mdi-check-decagram text-lg"></i>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Arsip Tuntas</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="bg-white rounded-[3.5rem] p-10 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shadow-inner ring-1 ring-red-100">
                    <i class="mdi mdi-police-badge text-3xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-sm uppercase italic text-slate-800">Laporan Polisi</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Yearly Performance</p>
                </div>
            </div>
            <div class="h-64"><canvas id="chartLP"></canvas></div>
        </div>

        <div class="bg-white rounded-[3.5rem] p-10 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center shadow-inner ring-1 ring-blue-100">
                    <i class="mdi mdi-scale-balance text-3xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-sm uppercase italic text-slate-800">Non-Litigasi</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Yearly Performance</p>
                </div>
            </div>
            <div class="h-64"><canvas id="chartNonlit"></canvas></div>
        </div>

        <div class="bg-white rounded-[3.5rem] p-10 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shadow-inner ring-1 ring-amber-100">
                    <i class="mdi mdi-alert-circle text-3xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-sm uppercase italic text-slate-800">Permasalahan</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Yearly Performance</p>
                </div>
            </div>
            <div class="h-64"><canvas id="chartMasalah"></canvas></div>
        </div>
    </div>
</div>

<input type="hidden" id="token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
<dialog id="modal_detail_chart" class="modal">
    <div class="modal-box w-11/12 max-w-5xl rounded-[3rem] p-10 border border-slate-100 shadow-2xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center shadow-lg">
                    <i class="mdi mdi-format-list-bulleted text-2xl"></i>
                </div>
                <div>
                    <h3 id="modal_title" class="font-black text-2xl italic uppercase text-slate-800 tracking-tighter leading-none">Detail Perkara</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-widest">Data hasil filter grafik</p>
                </div>
            </div>

            <div class="relative w-full md:w-64">
                <i class="mdi mdi-magnify absolute left-3 top-2.5 text-slate-400"></i>
                <input type="text" id="search-modal" placeholder="Cari di tabel ini..." 
                       class="input input-bordered input-sm w-full pl-10 rounded-xl font-bold text-xs focus:ring-2 focus:ring-blue-500 bg-slate-50 border-none">
            </div>
        </div>
        
        <div class="overflow-x-auto max-h-[50vh] scrollbar-thin">
            <table class="table table-zebra w-full" id="table-modal-data">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] uppercase tracking-[0.2em]">
                        <th class="rounded-l-2xl py-4">No</th>
                        <th>Permohonan / Perkara</th>
                        <th>PIC</th>
                        <th>Instansi Terkait</th>
                        <th>Status</th>
                        <th class="rounded-r-2xl text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table_detail_body" class="font-bold text-[11px] uppercase">
                    </tbody>
            </table>
        </div>

        <div class="modal-action mt-8">
            <form method="dialog">
                <button class="btn btn-ghost rounded-2xl font-black italic uppercase text-xs px-8">Tutup</button>
            </form>
        </div>
    </div>
</dialog>

<script>
let charts = {};
Chart.register(ChartDataLabels);

$(document).ready(function() {
    initCharts();
    refreshDashboard();
}); 

 $(document).ready(function() {
    $("#search-modal").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_detail_body tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
// Fitur Search di Modal
$(document).ready(function() {
    $("#search-modal").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#table_detail_body tr").each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

function showDetailModalInstansi(instansi, status, jenis) {
    const tahun = $('#f-tahun').val();
    const token = $('#token').val();
    
    $('#search-modal').val(''); // Reset search
    $('#table_detail_body').html('<tr><td colspan="5" class="text-center py-20"><span class="loading loading-spinner text-primary"></span></td></tr>');
    
    $('#modal_title').text(`${jenis.replace('_', ' ').toUpperCase()} : ${instansi}`);
    modal_detail_chart.showModal();

    $.ajax({
        url: "<?= base_url('home/get_detail_instansi') ?>",
        type: "POST",
        data: { instansi, status, jenis, tahun, token },
        dataType: "json",
        success: function(res) {
            let html = '';
            if (res.length > 0) {
                res.forEach((i, idx) => {
                    const badgeSt = i.status === 'selesai' ? 'bg-emerald-500 shadow-emerald-100' : 'bg-amber-400 shadow-amber-100';
                    const teamLabel = i.team_nonlit ? i.team_nonlit.replace('_', ' ').toUpperCase() : 'INTERNAL BPKAD';

                    html += `
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="text-slate-300 font-mono text-[10px]">${idx + 1}</td>
                        <td class="max-w-sm">
                            <div class="font-black text-slate-700 text-[11px] uppercase italic">${i.permohonan_nonlit}</div>
                            <div class="text-[9px] text-slate-400 font-bold mt-1 tracking-tighter italic">REG: ${i.tgl_nonlit}</div>
                        </td>
                        <td class="text-[10px] font-bold text-blue-600 uppercase italic">${i.pic}</td>
                        <td>
                            <span class="px-3 py-1 bg-slate-100 border border-slate-200 rounded-lg text-[9px] font-black text-slate-500 uppercase italic">
                                <i class="mdi mdi-shield-check-outline mr-1"></i> ${teamLabel}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="px-4 py-1.5 rounded-xl ${badgeSt} text-white text-[9px] font-black italic uppercase shadow-lg">
                                ${i.status}
                            </span>
                        </td>

                        <td class="text-center">
            <a href="<?= base_url('nonlit/detail/') ?>${i.id}" 
               class="btn btn-square btn-sm rounded-xl bg-white border-slate-200 hover:bg-slate-900 hover:text-white transition-all shadow-sm group">
                <i class="mdi mdi-eye-outline text-lg group-hover:scale-110"></i>
            </a>
        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="5" class="text-center py-20 font-black text-slate-200 uppercase italic tracking-widest">Tidak ada data ditemukan</td></tr>';
            }
            $('#table_detail_body').html(html);
        }
    });
}

function updateBarInstansi(chartObj, dbData) {
    // Ambil nama instansi untuk label bawah (X-Axis)
    const labels = dbData.map(item => item.instansi.replace('_', ' ').toUpperCase());
    const proses = dbData.map(item => parseInt(item.proses));
    const selesai = dbData.map(item => parseInt(item.selesai));

    chartObj.data.labels = labels;
    chartObj.data.datasets[0].data = proses;
    chartObj.data.datasets[1].data = selesai;
    chartObj.update();
}
 
// Sesuaikan fungsi refreshDashboard
function refreshDashboard() {
    const tahun = $('#f-tahun').val();
    const status = $('#f-status').val();
    const token = $('#token').val();

    $.ajax({
        url: "<?= base_url('home/get_chart_data') ?>",
        type: "POST",
        data: { tahun, status, token },
        dataType: "json",
        success: function(res) {
            $('#card-total').text(res.totals);
            $('#card-proses').text(res.count_proses);
            $('#card-selesai').text(res.count_selesai);

            // Update menggunakan fungsi instansi yang baru
            updateBarInstansi(charts.lp, res.bar_lp);
            updateBarInstansi(charts.nonlit, res.bar_nonlit);
            updateBarInstansi(charts.masalah, res.bar_masalah);
        }
    });
}

// Sesuaikan initCharts agar label awalnya kosong
function initCharts() {
    Chart.register(ChartDataLabels);

    const chartConfig = (jenis, colorProses, colorSelesai) => ({
        type: 'bar',
        data: {
            labels: [], // Kosongkan, akan diisi dinamis dari database
            datasets: [
                { label: 'proses', data: [], backgroundColor: colorProses, borderRadius: 5 },
                { label: 'selesai', data: [], backgroundColor: colorSelesai, borderRadius: 5 }
            ]
        },
        options: {
            maintainAspectRatio: false,
            indexAxis: 'y', // OPSIONAL: Ubah ke 'y' jika ingin bar horizontal agar nama instansi tidak bertumpuk
            plugins: {
                legend: { display: false },
                datalabels: {
                    anchor: 'end', align: 'right', color: '#64748b', font: { weight: 'bold', size: 10 },
                    formatter: (v, ctx) => {
                        let dataset = ctx.chart.data.datasets;
                        if (ctx.datasetIndex === dataset.length - 1) {
                            let total = (dataset[0].data[ctx.dataIndex] || 0) + (dataset[1].data[ctx.dataIndex] || 0);
                            return total > 0 ? total : '';
                        }
                        return '';
                    }
                }
            },
            scales: {
                x: { stacked: true, beginAtZero: true },
                y: { stacked: true, grid: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } }
            },
            onClick: (e, activeEls, chart) => {
                if (activeEls.length > 0) {
                    const idx = activeEls[0].index;
                    const instansiName = chart.data.labels[idx];
                    const datasetIdx = activeEls[0].datasetIndex;
                    const statusClicked = chart.data.datasets[datasetIdx].label;
                    
                    showDetailModalInstansi(instansiName, statusClicked, jenis);
                }
            }
        }
    });

    charts.lp = new Chart(document.getElementById('chartLP'), chartConfig('laporan_polisi', '#fca5a5', '#ef4444'));
    charts.nonlit = new Chart(document.getElementById('chartNonlit'), chartConfig('nonlit', '#93c5fd', '#3b82f6'));
    charts.masalah = new Chart(document.getElementById('chartMasalah'), chartConfig('permasalahan', '#fcd34d', '#f59e0b'));
}
</script>