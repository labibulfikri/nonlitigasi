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
            <!-- <button class="btn btn-primary rounded-2xl shadow-lg shadow-indigo-100 border-none normal-case font-bold px-6">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Data
            </button> -->
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

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6">
            <table class="laporan_nonlit table w-full border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-500 text-[11px] uppercase tracking-widest border-none">
                        <th class="bg-slate-50/50 py-4 pl-6 rounded-l-xl">No</th>
                        <th class="bg-slate-50/50 py-4">Informasi Perkara</th>
                        <th class="bg-slate-50/50 py-4">PIC / Jaksa</th>
                        <th class="bg-slate-50/50 py-4">Tanggal</th>
                        <th class="bg-slate-50/50 py-4">Bidang</th>
                        <th class="bg-slate-50/50 py-4">Status</th>
                        <th class="bg-slate-50/50 py-4 pr-6 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Ambil Token CSRF CodeIgniter
        const csrfToken = "<?php echo $this->security->get_csrf_hash(); ?>";

        // Inisialisasi Pertama
        fill_datatable();

        function fill_datatable(tahun = '', status = '', team = '', bidang = '', permohonan = '', pic = '') {
            $('.laporan_nonlit').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true, // Sangat penting agar filter bisa reload
                "responsive": true,
                "pageLength": 10,
                "order": [],
                "ajax": {
                    "url": "<?php echo base_url('laporan/fetch_nonlit'); ?>",
                    "type": "POST",
                    "data": {
                        "<?php echo $this->security->get_csrf_token_name(); ?>": csrfToken, // CSRF Protection
                        "tahun": tahun,
                        "status": status,
                        "team": team,
                        "bidang": bidang,
                        "permohonan_nonlit": permohonan,
                        "pic": pic
                    },
                },
                "columns": [{
                        "data": "no",
                        "className": "pl-6 font-medium text-slate-400 w-12"
                    },
                    {
                        "data": "permohonan_nonlit"
                    },
                    {
                        "data": "pic",
                        "render": function(data) {
                            if (!data) return `<span class='text-slate-300 italic'>-</span>`;
                            let initials = data.substring(0, 2).toUpperCase();
                            return `
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">${initials}</div>
                                <span class="font-semibold text-slate-600">${data}</span>
                            </div>`;
                        }
                    },
                    {
                        "data": "tgl_nonlit",
                        "className": "text-slate-500"
                    },
                    {
                        "data": "bidang"
                    },
                    {
                        "data": "status",
                        "render": function(data) {
                            let colorClass = data.toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-amber-500';
                            return `<span class="badge ${colorClass} border-none text-white text-[10px] font-bold px-3 py-2">${data.toUpperCase()}</span>`;
                        }
                    },
                    {
                        "data": "action",
                        "orderable": false
                    }
                ],
                "language": {
                    "processing": "<span class='loading loading-spinner loading-md text-primary'></span>",
                    "lengthMenu": "_MENU_ per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "paginate": {
                        "previous": "<i class='mdi mdi-arrow-left'></i>",
                        "next": "<i class='mdi mdi-arrow-right'></i>"
                    }
                },
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-6"l><"relative"tr><"flex flex-col md:flex-row justify-between items-center mt-8"ip>',
            });
        }

        // Listener Tombol Filter
        $('#filter').click(function(e) {
            e.preventDefault();
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
        const tahun = $("#nonlit_filter_bytahun").val();
        const status = $("#status").val();
        const team = $("#team_nonlit").val();
        const pic = $("#pic").val();

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

<style>
    /* Styling DataTables agar menyatu dengan DaisyUI */
    .dataTables_wrapper .dataTables_length select {
        @apply select select-bordered select-sm rounded-xl px-8;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply btn btn-sm btn-ghost rounded-lg mx-1 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply btn-primary text-white border-none shadow-md shadow-primary/20 !important;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    /* Baris Tabel Modern */
    .laporan_nonlit tbody tr {
        @apply hover:bg-slate-50 transition-colors cursor-default;
    }

    .laporan_nonlit td {
        @apply py-4 border-b border-slate-50 !important;
    }
</style>