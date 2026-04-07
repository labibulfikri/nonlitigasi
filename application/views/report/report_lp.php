<<<<<<< HEAD
<div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase italic">Laporan Polisi</h1>
            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mt-1">
                <i class="fa-solid fa-house-chimney text-xs"></i>
                <span>Dashboard</span>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                <span class="text-indigo-600">Laporan Polisi</span>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" id="btn-export-excel" class="btn btn-white bg-white border-slate-200 rounded-2xl shadow-sm hover:bg-slate-50 normal-case font-bold">
                <i class="fa-solid fa-file-excel text-emerald-500 mr-2"></i> Export Excel
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <div class="form-control">
                <input type="text" id="filter_nomor_polisi" class="input input-bordered w-full rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm" placeholder="Cari No. Polisi / Judul..." />
            </div>

            <div class="form-control">
                <select id="filter_tahun" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Tahun</option>
                    <?php
                    for ($i = date('Y'); $i >= 2020; $i--) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-control">
                <select id="filter_pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua PIC</option>
                    <?php foreach ($list_pic as $pic) : ?>
                        <option value="<?= $pic->pic_laporan_polisi ?>"><?= $pic->pic_laporan_polisi ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-control">
                <select id="filter_status" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Status</option>
                    <option value="PROSES">PROSES</option>
                    <option value="SELESAI">SELESAI</option>
                    <option value="SP3">SP3</option>
                </select>
            </div>

            <button type="button" id="btn-filter" class="btn btn-indigo bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl border-none font-black shadow-md shadow-indigo-100">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> FILTER
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 text-black">
            <table class="table_lp table w-full border-separate border-spacing-y-2 text-black">
                <thead>
                    <tr class="text-slate-500 text-[11px] uppercase tracking-widest border-none">
                        <th class="bg-slate-50/50 py-4 pl-6 rounded-l-xl">No</th>
                        <th class="bg-slate-50/50 py-4">No. Polisi / Judul</th>
                        <th class="bg-slate-50/50 py-4">Pelapor</th>
                        <th class="bg-slate-50/50 py-4">Kepolisian</th>
                        <th class="bg-slate-50/50 py-4">PIC</th>
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
        var token = $('#token').val(); // Ambil token CSRF dari input hidden
        fill_datatable();

        function fill_datatable() {
            $('.table_lp').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "responsive": true,
                "ajax": {
                    "url": "<?php echo base_url('report_lp/fetch_lp'); ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.token = token; // Kirim token CSRF dengan setiap request
                        d.tahun = $("#filter_tahun").val();
                        d.status = $("#filter_status").val();
                        d.nomor = $("#filter_nomor_polisi").val();
                        d.pic = $("#filter_pic").val();
                        d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    }
                },
                "columns": [{
                        "data": "no",
                        "className": "pl-6 font-medium text-slate-400 w-12"
                    },
                    {
                        "data": "nomor_judul"
                    },
                    {
                        "data": "pelapor"
                    },
                    {
                        "data": "team_polisi"
                    },
                    {
                        "data": "pic_laporan_polisi"
                    },
                    {
                        "data": "status_laporan_polisi",
                        "render": function(data) {
                            let color = data === 'SELESAI' ? 'bg-emerald-500' : (data === 'SP3' ? 'bg-slate-500' : 'bg-amber-500');
                            return `<span class="badge ${color} border-none text-white text-[10px] font-bold px-3 py-2">${data}</span>`;
                        }
                    },
                    {
                        "data": "action",
                        "orderable": false,
                        "className": "text-right pr-6"
                    }
                ],
                "language": {
                    "processing": "<span class='loading loading-spinner text-primary'></span>",
                },
                "dom": '<"flex justify-between items-center mb-6"l>tr<"flex justify-between items-center mt-8"ip>',
            });
        }

        $('#btn-filter').click(function() {
            fill_datatable();
        });

        $('#btn-export-excel').click(function() {
            const param = $.param({
                tahun: $("#filter_tahun").val(),
                status: $("#filter_status").val(),
                pic: $("#filter_pic").val(),
                nomor: $("#filter_nomor_polisi").val()
            });
            window.open("<?= base_url('report_lp/export_excel?') ?>" + param, '_blank');
        });
    });
</script>

<style>
    /* Styling DataTables kustom agar mirip Nonlit */
    .dataTables_wrapper .dataTables_length select {
        @apply select select-bordered select-sm rounded-xl px-8;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply btn btn-sm btn-ghost rounded-lg mx-1 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply btn-primary text-white border-none shadow-md !important;
    }

    .table_lp tbody tr {
        @apply hover:bg-slate-50 transition-colors bg-white;
    }

    .table_lp td {
        @apply py-4 border-b border-slate-50 !important;
    }
=======
<div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase italic">Laporan Polisi</h1>
            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mt-1">
                <i class="fa-solid fa-house-chimney text-xs"></i>
                <span>Dashboard</span>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                <span class="text-indigo-600">Laporan Polisi</span>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" id="btn-export-excel" class="btn btn-white bg-white border-slate-200 rounded-2xl shadow-sm hover:bg-slate-50 normal-case font-bold">
                <i class="fa-solid fa-file-excel text-emerald-500 mr-2"></i> Export Excel
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <div class="form-control">
                <input type="text" id="filter_nomor_polisi" class="input input-bordered w-full rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm" placeholder="Cari No. Polisi / Judul..." />
            </div>

            <div class="form-control">
                <select id="filter_tahun" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Tahun</option>
                    <?php
                    for ($i = date('Y'); $i >= 2020; $i--) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-control">
                <select id="filter_pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua PIC</option>
                    <?php foreach ($list_pic as $pic) : ?>
                        <option value="<?= $pic->pic_laporan_polisi ?>"><?= $pic->pic_laporan_polisi ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-control">
                <select id="filter_status" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Status</option>
                    <option value="PROSES">PROSES</option>
                    <option value="SELESAI">SELESAI</option>
                    <option value="SP3">SP3</option>
                </select>
            </div>

            <button type="button" id="btn-filter" class="btn btn-indigo bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl border-none font-black shadow-md shadow-indigo-100">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> FILTER
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 text-black">
            <table class="table_lp table w-full border-separate border-spacing-y-2 text-black">
                <thead>
                    <tr class="text-slate-500 text-[11px] uppercase tracking-widest border-none">
                        <th class="bg-slate-50/50 py-4 pl-6 rounded-l-xl">No</th>
                        <th class="bg-slate-50/50 py-4">No. Polisi / Judul</th>
                        <th class="bg-slate-50/50 py-4">Pelapor</th>
                        <th class="bg-slate-50/50 py-4">Kepolisian</th>
                        <th class="bg-slate-50/50 py-4">PIC</th>
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
        var token = $('#token').val(); // Ambil token CSRF dari input hidden
        fill_datatable();

        function fill_datatable() {
            $('.table_lp').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "responsive": true,
                "ajax": {
                    "url": "<?php echo base_url('report_lp/fetch_lp'); ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.token = token; // Kirim token CSRF dengan setiap request
                        d.tahun = $("#filter_tahun").val();
                        d.status = $("#filter_status").val();
                        d.nomor = $("#filter_nomor_polisi").val();
                        d.pic = $("#filter_pic").val();
                        d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    }
                },
                "columns": [{
                        "data": "no",
                        "className": "pl-6 font-medium text-slate-400 w-12"
                    },
                    {
                        "data": "nomor_judul"
                    },
                    {
                        "data": "pelapor"
                    },
                    {
                        "data": "team_polisi"
                    },
                    {
                        "data": "pic_laporan_polisi"
                    },
                    {
                        "data": "status_laporan_polisi",
                        "render": function(data) {
                            let color = data === 'SELESAI' ? 'bg-emerald-500' : (data === 'SP3' ? 'bg-slate-500' : 'bg-amber-500');
                            return `<span class="badge ${color} border-none text-white text-[10px] font-bold px-3 py-2">${data}</span>`;
                        }
                    },
                    {
                        "data": "action",
                        "orderable": false,
                        "className": "text-right pr-6"
                    }
                ],
                "language": {
                    "processing": "<span class='loading loading-spinner text-primary'></span>",
                },
                "dom": '<"flex justify-between items-center mb-6"l>tr<"flex justify-between items-center mt-8"ip>',
            });
        }

        $('#btn-filter').click(function() {
            fill_datatable();
        });

        $('#btn-export-excel').click(function() {
            const param = $.param({
                tahun: $("#filter_tahun").val(),
                status: $("#filter_status").val(),
                pic: $("#filter_pic").val(),
                nomor: $("#filter_nomor_polisi").val()
            });
            window.open("<?= base_url('report_lp/export_excel?') ?>" + param, '_blank');
        });
    });
</script>

<style>
    /* Styling DataTables kustom agar mirip Nonlit */
    .dataTables_wrapper .dataTables_length select {
        @apply select select-bordered select-sm rounded-xl px-8;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply btn btn-sm btn-ghost rounded-lg mx-1 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply btn-primary text-white border-none shadow-md !important;
    }

    .table_lp tbody tr {
        @apply hover:bg-slate-50 transition-colors bg-white;
    }

    .table_lp td {
        @apply py-4 border-b border-slate-50 !important;
    }
>>>>>>> Initial commit dari server
</style>