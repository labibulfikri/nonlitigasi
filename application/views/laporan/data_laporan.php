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

    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/60 border border-slate-100 mb-10 transition-all hover:shadow-2xl">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-filter text-sm"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm">Filter</h3>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Filter data perkara secara spesifik</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            <!-- Cari Perkara -->
            <div class="relative group">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block tracking-widest">Cari Perkara</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors z-10"></i>
                    <input type="text" id="permohonan_nonlit"
                        class="input input-bordered w-full pl-12 rounded-2xl bg-slate-50 border-none focus:bg-white focus:ring-4 focus:ring-indigo-50 font-bold text-xs h-12"
                        placeholder="Nama Permohonan..." />
                </div>
            </div>

            <!-- Multi-Select Team / Instansi -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block tracking-widest">Instansi/Team</label>
                <select id="team_nonlit" multiple="multiple" class="select-multi w-full rounded-2xl bg-slate-50 border-none font-bold text-xs">
                    <option value="kejati">KEJATI JATIM</option>
                    <option value="kejari_sby">KEJARI SURABAYA</option>
                    <option value="kejari_perak">KEJARI TG PERAK</option>
                    <option value="polda">POLDA JATIM</option>
                    <option value="polrestabes">POLRESTABES SURABAYA</option>
                    <option value="polres_perak">POLRES TANJUNG PERAK</option>
                </select>
            </div>

            <!-- Multi-Select Status -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block tracking-widest">Status</label>
                <select id="status" multiple="multiple" class="select-multi w-full rounded-2xl bg-slate-50 border-none font-bold text-xs">
                    <option value="selesai">SELESAI</option>
                    <option value="proses">PROSES</option>
                </select>
            </div>

            <!-- Multi-Select PIC -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block tracking-widest">Penanggung Jawab</label>
                <select id="pic" multiple="multiple" class="select-multi w-full rounded-2xl bg-slate-50 border-none font-bold text-xs">
                    <?php foreach ($list_pic as $pic) : ?>
                        <option value="<?= $pic->nama_pic ?>"><?= strtoupper($pic->nama_pic) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Tahun -->
            <!-- <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block tracking-widest">Tahun</label>
                <select id="nonlit_filter_bytahun" class="select select-bordered w-full rounded-2xl bg-slate-50 border-none font-black text-xs h-12 focus:bg-white focus:ring-4 focus:ring-indigo-50">
                    <option value="all">SEMUA TAHUN</option>
                    <?php
                    for ($i = date('Y') - 3; $i <= date('Y') + 1; $i++) {
                        $sel = $i == date('Y') ? 'selected' : '';
                        echo "<option value='$i' $sel>$i</option>";
                    }
                    ?>
                </select>
            </div> -->

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block tracking-widest">Tahun</label>
                <select id="nonlit_filter_bytahun" multiple="multiple" class="select-multi w-full rounded-2xl bg-slate-50 border-none font-bold text-xs">
                    <?php
                    for ($i = date('Y') - 3; $i <= date('Y') + 1; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="flex items-end gap-2 xl:col-span-5 justify-end mt-2">
                <button type="button" id="filter" class="btn btn-indigo bg-indigo-600 hover:bg-indigo-800 text-white rounded-2xl border-none font-black shadow-lg shadow-indigo-200 h-12 italic uppercase text-xs tracking-tighter px-8">
                    <i class="fa-solid fa-filter mr-2"></i> Terapkan Filter
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-8">
                <table class="laporan_nonlit table w-full border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] border-none italic">
                            <th class="bg-transparent py-4 pl-8">#</th>
                            <th class="bg-transparent py-4">Informasi Perkara</th>
                            <th class="bg-transparent py-4">SIMBADA</th>
                            <th class="bg-transparent py-4">Tim Kejaksaan</th>
                            <th class="bg-transparent py-4">PIC </th>
                            <th class="bg-transparent py-4">Tanggal</th>
                            <th class="bg-transparent py-4 text-center">Status</th>
                            <th class="bg-transparent py-4 pr-8 text-right font-black">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                    </tbody>
                </table>
            </div>
        </div>

        <dialog id="modal_detail_laporan" class="modal">
            <div class="modal-box w-11/12 max-w-5xl rounded-[2.5rem] p-10 border border-slate-50 shadow-2xl">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                            <i class="fa-solid fa-file-lines text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-2xl italic uppercase text-slate-800 tracking-tighter leading-none">Detail Arsip Laporan</h3>
                            <p id="detail_instansi_title" class="text-[10px] font-bold text-slate-400 uppercase mt-2 tracking-[0.3em]"></p>
                        </div>
                    </div>
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 bg-slate-50 p-8 rounded-3xl border border-slate-100">
                    <div class="space-y-4">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama Permohonan</label>
                            <p id="det_permohonan" class="font-black text-slate-700 uppercase italic leading-tight"></p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal Registrasi</label>
                            <p id="det_tanggal" class="font-bold text-slate-600"></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Penanggung Jawab (PIC)</label>
                            <p id="det_pic" class="font-black text-indigo-600 uppercase"></p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status Terakhir</label>
                            <div id="det_status_badge"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn btn-ghost rounded-2xl font-black italic uppercase text-xs px-8">Tutup</button>
                    </form>
                    <a id="btn_link_detail" href="#" class="btn btn-indigo bg-indigo-600 border-none rounded-2xl font-black italic uppercase text-xs px-8 shadow-lg shadow-indigo-100">Lihat Full Detail</a>
                </div>
            </div>
        </dialog>
    </div>
    <script>
        $(document).ready(function() {
            const token = $('#token').val();

            // Inisialisasi Multi Select2
            $('.select-multi').select2({
                placeholder: "Pilih...",
                allowClear: true,
                width: '100%'
            });

            fill_datatable();

            function fill_datatable() {
                $('.laporan_nonlit').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "destroy": true,
                    "responsive": true,
                    "pageLength": 10,
                    "order": [],
                    "ajax": {
                        "url": "<?php echo base_url('laporan/fetch_nonlit'); ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.token = token;
                            d.tahun = $("#nonlit_filter_bytahun").val();
                            // Nilai dari multi-select otomatis menghasilkan Array
                            d.status = $("#status").val();
                            d.team = $("#team_nonlit").val();
                            d.pic = $("#pic").val();
                            d.permohonan_nonlit = $("#permohonan_nonlit").val();
                        },
                        "dataSrc": function(json) {
                            if (json.summary) {
                                $('#card_total').text(json.recordsTotal);
                                $('#card_filtered').text(json.recordsFiltered);
                                $('#card_proses').text(json.summary.proses);
                                $('#card_selesai').text(json.summary.selesai);
                            }
                            return json.data;
                        }
                    },
                    "columns": [{
                            "data": "no",
                            "className": "pl-6 font-medium text-slate-400 w-12"
                        },
                        {
                            "data": "permohonan_nonlit"
                        },
                        {
                            "data": "register_baru",
                        },
                        {
                            "data": "team_nonlit",

                        },
                        {
                            "data": "pic",
                            "render": function(data) {
                                if (!data) return '-';
                                let initials = data.substring(0, 2).toUpperCase();
                                return `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black border border-indigo-100 shadow-sm">${initials}</div>
                            <span class="font-black text-slate-600 text-[11px] uppercase">${data}</span>
                        </div>`;
                            }
                        },
                        {
                            "data": "tgl_nonlit",
                            "className": "font-bold text-slate-500 text-xs"
                        },

                        {
                            "data": "status",
                            "render": function(data) {
                                if (!data) return '-';
                                let st = data.toLowerCase();
                                let config = st === 'selesai' ? 'bg-emerald-500 shadow-emerald-100' : 'bg-amber-400 shadow-amber-100';
                                return `<span class="badge ${config} border-none text-white text-[9px] font-black px-3 py-3 shadow-lg italic uppercase">${data}</span>`;
                            }
                        },
                        {
                            "data": "action"
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
                    "dom": '<"flex flex-col md:flex-row justify-between items-center mb-6"l><"relative"tr><"flex flex-col md:flex-row justify-between items-center mt-8"ip>'
                });
            }

            $('#filter').click(function(e) {
                e.preventDefault();
                $('.laporan_nonlit').DataTable().ajax.reload();
            });
        });
    </script>

    <script>
        $('#btn-export-excel').click(function(e) {
            e.preventDefault();

            // Ambil nilai filter (Multi-Select akan menghasilkan Array)
            const tahun = $("#nonlit_filter_bytahun").val();
            const status = $("#status").val(); // Array
            const team = $("#team_nonlit").val(); // Array
            const pic = $("#pic").val(); // Array
            const permohonan = $("#permohonan_nonlit").val();

            // Bangun URL dengan query string ($.param otomatis menangani Array)
            const baseUrl = "<?= base_url('laporan/export_excel') ?>";
            const params = $.param({
                tahun: tahun,
                status: status,
                team: team,
                pic: pic,
                permohonan_nonlit: permohonan
            });

            // Arahkan ke URL download
            window.location.href = baseUrl + '?' + params;
        });
    </script>

    <style>
        /* Baris Tabel yang Terpisah (Floating Row Effect) */
        .laporan_nonlit tbody tr {
            @apply bg-white transition-all duration-300;
            box-shadow: 0 2px 10px -5px rgba(0, 0, 0, 0.05);
        }

        .laporan_nonlit tbody tr:hover {
            @apply shadow-xl shadow-slate-200/50 -translate-y-0.5;
            background: linear-gradient(to right, #ffffff, #fdfdff);
        }

        .laporan_nonlit td {
            @apply py-5 border-none !important;
        }

        /* Styling Pagination DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-indigo-600 text-white border-none rounded-xl font-black shadow-lg shadow-indigo-200 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            @apply bg-slate-100 border-none rounded-xl !important;
        }
    </style>