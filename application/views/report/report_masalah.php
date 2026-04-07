<<<<<<< HEAD
<div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase italic text-rose-600">Laporan Permasalahan</h1>
            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mt-1">
                <i class="fa-solid fa-house-chimney text-xs"></i>
                <span>Dashboard</span>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                <span class="text-rose-600">Laporan Permasalahan</span>
            </div>
        </div>
        <button type="button" id="btn-export-excel" class="btn btn-white bg-white border-slate-200 rounded-2xl shadow-sm hover:bg-slate-50 font-bold">
            <i class="fa-solid fa-file-excel text-emerald-500 mr-2"></i> Export Excel
        </button>
    </div>
    <?= crsf_ajax() ?>
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <div class="form-control">
                <input type="text" id="filter_keyword" class="input input-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm" placeholder="Cari Masalah / Alamat..." />
            </div>
            <div class="form-control">
                <select id="filter_tahun" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Tahun</option>
                    <?php for ($i = date('Y'); $i >= 2020; $i--) echo "<option value='$i'>$i</option>"; ?>
                </select>
            </div>
            <div class="form-control">
                <select id="filter_pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua PIC</option>
                    <?php foreach ($list_pic as $pic) : ?>
                        <option value="<?= $pic->pic_masalah ?>"><?= $pic->pic_masalah ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control">
                <select id="filter_status" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Status</option>
                    <option value="PROSES">PROSES</option>
                    <option value="SELESAI">SELESAI</option>
                </select>
            </div>
            <button type="button" id="btn-filter" class="btn bg-rose-600 hover:bg-rose-700 text-white rounded-xl border-none font-black">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> FILTER
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6">
            <table class="table_masalah table w-full border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-500 text-[11px] uppercase tracking-widest border-none text-black">
                        <th class="bg-slate-50/50 py-4 pl-6 rounded-l-xl">No</th>
                        <th class="bg-slate-50/50 py-4">Informasi Masalah</th>
                        <th class="bg-slate-50/50 py-4">Alamat</th>
                        <th class="bg-slate-50/50 py-4">PIC</th>
                        <th class="bg-slate-50/50 py-4">Status</th>
                        <th class="bg-slate-50/50 py-4 pr-6 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-black"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var token = $('#token').val(); // Ambil token CSRF dari input hidden
        fill_datatable();

        function fill_datatable() {
            $('.table_masalah').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "responsive": true,
                "ajax": {
                    "url": "<?php echo base_url('report_masalah/fetch_masalah'); ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.token = $('#token').val(); // Kirim token CSRF dengan setiap request
                        d.tahun = $("#filter_tahun").val();
                        d.status = $("#filter_status").val();
                        d.keyword = $("#filter_keyword").val();
                        d.pic = $("#filter_pic").val();
                        d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    }
                },
                "columns": [{
                        "data": "no",
                        "className": "pl-6 font-medium text-slate-400 w-12"
                    },
                    {
                        "data": "info_masalah"
                    },
                    {
                        "data": "alamat_masalah"
                    },
                    {
                        "data": "pic_masalah"
                    },
                    {
                        "data": "status_masalah",
                        "render": function(data) {
                            let color = data === 'SELESAI' ? 'bg-emerald-500' : 'bg-rose-500';
                            return `<span class="badge ${color} border-none text-white text-[10px] font-bold px-3 py-2">${data}</span>`;
                        }
                    },
                    {
                        "data": "action",
                        "className": "text-right pr-6"
                    }
                ],
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
                keyword: $("#filter_keyword").val()
            });
            window.open("<?= base_url('report_masalah/export_excel?') ?>" + param, '_blank');
        });
    });
=======
<div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase italic text-rose-600">Laporan Permasalahan</h1>
            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mt-1">
                <i class="fa-solid fa-house-chimney text-xs"></i>
                <span>Dashboard</span>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                <span class="text-rose-600">Laporan Permasalahan</span>
            </div>
        </div>
        <button type="button" id="btn-export-excel" class="btn btn-white bg-white border-slate-200 rounded-2xl shadow-sm hover:bg-slate-50 font-bold">
            <i class="fa-solid fa-file-excel text-emerald-500 mr-2"></i> Export Excel
        </button>
    </div>
    <?= crsf_ajax() ?>
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <div class="form-control">
                <input type="text" id="filter_keyword" class="input input-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm" placeholder="Cari Masalah / Alamat..." />
            </div>
            <div class="form-control">
                <select id="filter_tahun" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Tahun</option>
                    <?php for ($i = date('Y'); $i >= 2020; $i--) echo "<option value='$i'>$i</option>"; ?>
                </select>
            </div>
            <div class="form-control">
                <select id="filter_pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua PIC</option>
                    <?php foreach ($list_pic as $pic) : ?>
                        <option value="<?= $pic->pic_masalah ?>"><?= $pic->pic_masalah ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control">
                <select id="filter_status" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-bold text-sm">
                    <option value="">Semua Status</option>
                    <option value="PROSES">PROSES</option>
                    <option value="SELESAI">SELESAI</option>
                </select>
            </div>
            <button type="button" id="btn-filter" class="btn bg-rose-600 hover:bg-rose-700 text-white rounded-xl border-none font-black">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> FILTER
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6">
            <table class="table_masalah table w-full border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-500 text-[11px] uppercase tracking-widest border-none text-black">
                        <th class="bg-slate-50/50 py-4 pl-6 rounded-l-xl">No</th>
                        <th class="bg-slate-50/50 py-4">Informasi Masalah</th>
                        <th class="bg-slate-50/50 py-4">Alamat</th>
                        <th class="bg-slate-50/50 py-4">PIC</th>
                        <th class="bg-slate-50/50 py-4">Status</th>
                        <th class="bg-slate-50/50 py-4 pr-6 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-black"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var token = $('#token').val(); // Ambil token CSRF dari input hidden
        fill_datatable();

        function fill_datatable() {
            $('.table_masalah').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "responsive": true,
                "ajax": {
                    "url": "<?php echo base_url('report_masalah/fetch_masalah'); ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.token = $('#token').val(); // Kirim token CSRF dengan setiap request
                        d.tahun = $("#filter_tahun").val();
                        d.status = $("#filter_status").val();
                        d.keyword = $("#filter_keyword").val();
                        d.pic = $("#filter_pic").val();
                        d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                    }
                },
                "columns": [{
                        "data": "no",
                        "className": "pl-6 font-medium text-slate-400 w-12"
                    },
                    {
                        "data": "info_masalah"
                    },
                    {
                        "data": "alamat_masalah"
                    },
                    {
                        "data": "pic_masalah"
                    },
                    {
                        "data": "status_masalah",
                        "render": function(data) {
                            let color = data === 'SELESAI' ? 'bg-emerald-500' : 'bg-rose-500';
                            return `<span class="badge ${color} border-none text-white text-[10px] font-bold px-3 py-2">${data}</span>`;
                        }
                    },
                    {
                        "data": "action",
                        "className": "text-right pr-6"
                    }
                ],
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
                keyword: $("#filter_keyword").val()
            });
            window.open("<?= base_url('report_masalah/export_excel?') ?>" + param, '_blank');
        });
    });
>>>>>>> Initial commit dari server
</script>