<div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">LAPORAN NON-LITIGASI</h1>
            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mt-1">
                <i class="fa-solid fa-house-chimney text-xs"></i>
                <span>Dashboard</span>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                <span class="text-indigo-600">Laporan</span>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button type="button" id="btn-export-excel" class="btn btn-white bg-white border-slate-200 rounded-2xl shadow-sm hover:bg-slate-50 normal-case font-bold">
    <i class="fa-solid fa-file-excel text-emerald-500 mr-2"></i> Export Excel
</button>
            <button class="btn btn-primary rounded-2xl shadow-lg shadow-indigo-100 border-none normal-case font-bold px-6">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Data
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-sliders text-sm"></i>
            </div>
            <h3 class="font-black text-slate-700 uppercase tracking-widest text-xs">Opsi Penyaringan</h3>
        </div>

        <?= crsf_ajax() ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <div class="form-control">
                <input type="text" id="permohonan_nonlit" class="input input-bordered w-full rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm" placeholder="Cari Permohonan..." />
            </div>

            <div class="form-control">
                <select id="team_nonlit" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Team</option>
                    <option value="kejati">Kejati Jatim</option>
                    <option value="kejari_sby">Kejari Surabaya</option>
                    <option value="kejari_perak">Kejari Tg Perak</option>
                </select>
            </div>

            <div class="form-control">
                <select id="pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua PIC</option>
                    <option value="cavita">CAVITA</option>
                    <option value="rendy">RENDY BAMBANG</option>
                </select>
            </div>

            <div class="form-control">
                <select id="nonlit_filter_bytahun" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <?php
                    $mulai = date('Y') - 5;
                    for ($i = $mulai; $i <= date('Y') + 2; $i++) {
                        $sel = $i == date('Y') ? 'selected' : '';
                        echo "<option value='$i' $sel>$i</option>";
                    }
                    ?>
                    <option value="all">Semua Tahun</option>
                </select>
            </div>

            <div class="form-control">
                <select id="status" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Status</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <button type="button" id="filter" class="btn btn-indigo bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl border-none font-black shadow-md shadow-indigo-100">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> FILTER
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="table w-full laporan_nonlit">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase text-[11px] tracking-[0.2em] font-black">
                        <th class="bg-white pb-6">No</th>
                        <th class="bg-white pb-6">Permohonan Non-Litigasi</th>
                        <th class="bg-white pb-6">Penanggung Jawab</th>
                        <th class="bg-white pb-6">Tgl Masuk</th>
                        <th class="bg-white pb-6">Bidang</th>
                        <th class="bg-white pb-6">Status</th>
                        <!-- <th class="bg-white pb-6 text-right">Opsi</th> -->
                    </tr>
                </thead>
                <tbody class="text-slate-600 font-bold text-sm">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var token = $('#token').val()
    fill_datatable();

    function fill_datatable(tahun = '', status = '', team = '', bidang = '', permohonan = '', pic = '') {
        $('.laporan_nonlit').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "dom": '<"flex justify-between items-center mb-6"l><"relative"tr><"flex justify-between items-center mt-8"ip>',
            "ajax": {
                url: "<?php echo base_url('laporan/fetch_nonlit'); ?>",
                type: "POST",
                data: { token:token,tahun, status, team, bidang, permohonan_nonlit: permohonan, pic },
            },
            "columns": [
                { "data": "no", "className": "text-slate-400 font-medium" },
                { 
                    "data": "permohonan_nonlit",
                    "render": (data) => `<div class="font-bold text-slate-800 line-clamp-2 min-w-[200px]">${data}</div>`
                },
                { 
                    "data": "pic",
                    "render": function(data) {
                        if(!data) return `<span class="text-slate-300 italic">N/A</span>`;
                        let ini = data.substring(0,2).toUpperCase();
                        return `<div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black">${ini}</div>
                                    <span class="whitespace-nowrap">${data}</span>
                                </div>`;
                    }
                },
                { "data": "tgl_nonlit", "className": "whitespace-nowrap opacity-60" },
                { "data": "bidang", "className": "text-center uppercase" },
                { 
                    "data": "status",
                    "render": function(data) {
                        let style = data.toLowerCase() === 'selesai' ? 'badge-success' : 'badge-warning';
                        return `<div class="badge ${style} badge-sm border-none font-black text-[10px] text-white py-3 px-4">${data.toUpperCase()}</div>`;
                    }
                },
                // { 
                //     "data": "id",
                //     "className": "text-right",
                //     "render": (data) => `
                //         <div class="flex justify-end gap-2">
                //             <button class="btn btn-square btn-ghost btn-sm hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition-all rounded-xl"><i class="fa-solid fa-eye text-xs"></i></button>
                //             <button class="btn btn-square btn-ghost btn-sm hover:bg-slate-100 text-slate-400 hover:text-emerald-600 transition-all rounded-xl"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                //         </div>`
                // }
            ]
        });
    }

    $('#filter').click(function() {
        fill_datatable(
            $("#nonlit_filter_bytahun").val(),
            $("#status").val(),
            $("#team_nonlit").val(),
            $("#bidang").val(),
            $("#permohonan_nonlit").val(),
            $("#pic").val()
        );
    });
});
</script>

<script>
    $('#btn-export-excel').click(function() {
    // Ambil nilai filter saat ini
    const tahun  = $("#nonlit_filter_bytahun").val();
    const status = $("#status").val();
    const team   = $("#team_nonlit").val();
    const pic    = $("#pic").val();

    // Bangun URL export dengan parameter filter
    const exportUrl = "<?= base_url('laporan/export_excel') ?>?" + 
                      "tahun=" + tahun + 
                      "&status=" + status + 
                      "&team=" + team + 
                      "&pic=" + pic;

    // Arahkan browser untuk download berkas
    window.location.href = exportUrl;
});
</script>