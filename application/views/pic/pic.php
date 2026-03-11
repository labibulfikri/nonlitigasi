<div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight uppercase">Manajemen Master PIC</h1>
        <p class="text-slate-500 text-sm">Kelola data Person In Charge (PIC) untuk penanganan perkara non-litigasi.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-4">
            <div class="card bg-white shadow-sm border border-slate-200 rounded-[2rem] sticky top-8">
                <div class="card-body p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="mdi mdi-account-plus text-xl"></i>
                        </div>
                        <h3 class="font-black text-slate-700 uppercase tracking-widest text-sm" id="form-title">Tambah PIC Baru</h3>
                    </div>

                    <form id="form-pic" class="space-y-5">
                        <?= crsf_ajax() ?>
                        <input type="hidden" id="id" name="id">

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold text-slate-600">Nama Lengkap</span></label>
                            <input type="text" id="nama_pic" name="nama_pic" placeholder="Contoh: Andi Mardiyanto"
                                class="input input-bordered w-full rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-medium" required />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold text-slate-600">Bidang / Divisi</span></label>
                            <select id="bidang_pic" name="bidang_pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-medium" required>
                                <option value="">Pilih Bidang</option>
                                <option value="ppsbmd">PPSBMD</option>
                                <option value="pppbmd">P3BMD</option>
                            </select>
                        </div>

                        <!-- <div class="form-control">
                            <label class="label"><span class="label-text font-bold text-slate-600">No. Telepon / WhatsApp</span></label>
                            <input type="number" id="telepon_pic" name="telepon_pic" placeholder="081234567xxx"
                                class="input input-bordered w-full rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-medium" />
                        </div> -->

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold text-slate-600">Status</span></label>
                            <select id="status_pic" name="status_pic" class="select select-bordered w-full rounded-xl bg-slate-50 border-none font-medium">
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>

                        <div class="pt-4 flex gap-2">
                            <button type="submit" class="btn btn-indigo flex-1 bg-indigo-600 hover:bg-indigo-700 text-white border-none rounded-xl shadow-lg shadow-indigo-100 uppercase font-black">
                                <i class="mdi mdi-check-circle mr-2"></i> Simpan Data
                            </button>
                            <button type="button" id="btn-reset" class="btn btn-ghost rounded-xl uppercase font-bold text-slate-400">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                            <i class="mdi mdi-account-group text-xl"></i>
                        </div>
                        <h3 class="font-black text-slate-700 uppercase tracking-widest text-sm">Daftar PIC Terdaftar</h3>
                    </div>

                    <table id="table-pic" class="table w-full border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-slate-500 text-[11px] uppercase tracking-widest border-none">
                                <th class="bg-slate-50/50 py-4 pl-6 rounded-l-xl">No</th>
                                <th class="bg-slate-50/50 py-4">PIC</th>
                                <th class="bg-slate-50/50 py-4">Bidang</th>
                                <th class="bg-slate-50/50 py-4 text-center">Status</th>
                                <th class="bg-slate-50/50 py-4 pr-6 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTable
        const csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
        const table = $('#table-pic').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
            "ajax": {
                "url": "<?= base_url('pic/fetch_pic') ?>",
                "type": "POST",
                "data": function(d) {
                    // 2. Gunakan nama token dinamis, bukan string 'token'
                    // Ambil langsung nilainya dari input setiap kali tabel refresh
                    d[csrfName] = $('#token').val();
                },
                "error": function(xhr, error, thrown) {
                    console.error("DataTable Error: ", xhr.responseText);
                    // Ini akan membantu Anda melihat error asli di console browser
                }
            },
            "columns": [{
                    "data": "no",
                    "className": "pl-6 font-medium text-slate-400 w-12 text-center"
                },
                {
                    "data": "nama",
                    "render": function(data, type, row) {
                        if (!data) return '-';
                        let initials = data.substring(0, 2).toUpperCase();
                        return `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-black">${initials}</div>
                    <div class="text-left">
                        <div class="font-bold text-slate-700">${data}</div>
                        <div class="text-[10px] text-slate-400 uppercase font-bold">${row.telepon || '-'}</div>
                    </div>
                </div>`;
                    }
                },
                {
                    "data": "bidang",
                    "className": "font-semibold text-slate-600 text-left"
                },
                {
                    "data": "status",
                    "className": "text-center",
                    "render": function(data) {
                        let badge = data == 1 ? 'bg-emerald-500' : 'bg-slate-300';
                        let text = data == 1 ? 'AKTIF' : 'NON-AKTIF';
                        return `<span class="badge ${badge} border-none text-white text-[10px] font-bold px-3 py-2 rounded-lg">${text}</span>`;
                    }
                },
                {
                    "data": "action",
                    "orderable": false,
                    "className": "text-right pr-6"
                }
            ],
            "language": {
                "search": "Cari PIC:",
                "searchPlaceholder": "Ketik nama...",
                "paginate": {
                    "previous": "<i class='mdi mdi-chevron-left'></i>",
                    "next": "<i class='mdi mdi-chevron-right'></i>"
                }
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6"ip>'
        });

        // 2. Submit Form (Tambah/Edit)
        $('#form-pic').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Mohon Tunggu',
                text: 'Sedang menyimpan data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            let formData = new FormData(this);
            // var token = $('#token').val();
            // formData.append('token', token);
            var tokenName = "<?= $this->security->get_csrf_token_name(); ?>";
            var tokenValue = $('#token').val();
            formData.append(tokenName, tokenValue);
            $.ajax({
                url: "<?= base_url('pic/save_pic') ?>",
                type: "POST",
                data: formData, // Kirim objek formData langsung
                processData: false, // WAJIB: agar jQuery tidak mengubah data menjadi query string
                contentType: false, // WAJIB: agar browser mengatur boundary multipart/form-data
                dataType: "JSON",
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil!', res.message, 'success');
                        resetForm(); // Pastikan fungsi resetForm sudah ada
                        table.ajax.reload(); // Pastikan variabel table adalah instance DataTable Anda
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    // Jika error, cetak di console untuk debug
                    console.error(xhr.responseText);
                    Swal.fire('Error!', 'Terjadi kesalahan sistem atau Token Expired.', 'error');
                }
            });
        });

        // 3. Edit Data
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            // Ubah Judul Form
            $('#form-title').text('Edit Data PIC');

            // Tampilkan Loading saat ambil data
            Swal.fire({
                title: 'Mengambil data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= base_url('pic/get_pic_by_id/') ?>" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    Swal.close();
                    // Isi form dengan data dari database
                    $('#id').val(data.id);
                    $('#nama_pic').val(data.nama);
                    $('#bidang_pic').val(data.bidang);
                    $('#status_pic').val(data.status);

                    // Scroll ke atas agar user tahu form sudah terisi (opsional)
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal mengambil data.', 'error');
                }
            });
        });

        // 4. Reset Form
        $('#btn-reset').click(function() {
            resetForm();
        });

        function resetForm() {
            $('#form-pic')[0].reset();
            $('#id').val('');
            $('#form-title').text('Tambah PIC Baru');
        }
    });
</script>
<script>
    function deleteData(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data PIC yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#f43f5e',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('pic/delete_pic/') ?>" + id,
                    type: "POST",
                    data: {
                        "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"
                    },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Terhapus!', res.message, 'success');
                            // if ($.fn.DataTable.isDataTable('#table-pic')) {
                            //     table.ajax.reload(null, false);
                            // }
                            $('#table-pic').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal!', 'Data tidak bisa dihapus.', 'error');
                        }
                    }
                });
            }
        });
    }
</script>

<style>
    /* Styling DataTables agar menyatu dengan DaisyUI */
    .dataTables_filter input {
        @apply input input-sm input-bordered rounded-xl bg-slate-50 border-none ml-2 w-64 font-medium;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply btn btn-sm btn-ghost rounded-lg mx-1 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply btn-primary text-white border-none shadow-md !important;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    #table-pic tbody tr {
        @apply hover:bg-slate-50 transition-colors;
    }

    #table-pic td {
        @apply py-4 border-b border-slate-50 !important;
    }
</style>