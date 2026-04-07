<div class="container mx-auto p-4 mb-20 text-base-content">
    <div class="container mx-auto p-4 mb-8 text-base-content">
        <div class="card bg-base-100 shadow-xl border border-base-300 overflow-hidden">
            <div class="bg-primary p-1"></div>
            <div class="card-body p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8 w-full p-2">
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-4xl font-black text-primary uppercase tracking-tighter italic leading-none">
                                    <?= $masalah->nama_masalah ?>
                                </h1>
                                <div class="badge badge-primary border-none font-black text-[10px] tracking-[0.2em] uppercase px-4 py-3 shadow-md shadow-primary/20">
                                    <?= $masalah->status_masalah ?>
                                </div>
                            </div>

                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-base-200/50 rounded-lg border border-base-300/30">
                                <i class="mdi mdi-map-marker text-error animate-pulse"></i>
                                <p class="text-[11px] font-black opacity-70 uppercase tracking-widest leading-none">
                                    <?= $masalah->alamat_masalah ?>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 w-full lg:w-auto shrink-0">
                            <div class="relative bg-gradient-to-br from-base-100 to-base-200/50 p-4 rounded-2xl border border-base-300 shadow-sm min-w-[150px] overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-5 group-hover:opacity-10 transition-opacity">
                                    <i class="mdi mdi-account-tie text-4xl"></i>
                                </div>
                                <span class="text-[9px] font-black opacity-40 uppercase block mb-2 tracking-tighter">PIC Terkait</span>
                                <p class="text-xs font-black uppercase tracking-wide text-secondary truncate"><?= $masalah->pic_masalah ?></p>
                            </div>

                            <div class="relative bg-gradient-to-br from-base-100 to-base-200/50 p-4 rounded-2xl border border-base-300 shadow-sm min-w-[150px] overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-5 group-hover:opacity-10 transition-opacity">
                                    <i class="mdi mdi-archive text-4xl"></i>
                                </div>
                                <span class="text-[9px] font-black opacity-40 uppercase block mb-2 tracking-tighter">Posisi Arsip</span>
                                <p class="text-xs font-mono font-black uppercase tracking-widest text-primary"><?= $masalah->penyimpanan_rak ?></p>
                            </div>

                            <div class="relative bg-gradient-to-br from-base-100 to-base-200/50 p-4 rounded-2xl border border-base-300 shadow-sm min-w-[150px] overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-5 group-hover:opacity-10 transition-opacity">
                                    <i class="mdi mdi-calendar-check text-4xl"></i>
                                </div>
                                <span class="text-[9px] font-black opacity-40 uppercase block mb-2 tracking-tighter">Tgl. Registrasi</span>
                                <p class="text-xs font-black uppercase tracking-wide opacity-80"><?= date('d M Y', strtotime($masalah->tgl_masalah)) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-3">
            <div class="card bg-base-100 shadow-lg border border-base-300 sticky top-4 overflow-hidden">
                <div class="p-4 border-b border-base-300 flex justify-between items-center bg-base-200/50">
                    <h3 class="font-black opacity-70 uppercase text-[10px] tracking-widest">Daftar Kronologi</h3>
                    <button onclick="modal_tambah_det.showModal()" class="btn btn-circle btn-xs btn-primary shadow-lg">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div class="card-body p-2 max-h-[500px] overflow-y-auto">
                    <ul class="menu menu-vertical gap-2" id="menu_history">
                        <?php foreach ($details as $row) { ?>
                            <li>
                                <a onclick="loadContent(this)"
                                    data-id="<?= $row->id_masalah_det ?>"
                                    class="flex flex-col items-start p-4 border border-base-200 hover:bg-primary/5 hover:border-primary transition-all rounded-xl group bg-base-100">
                                    <span class="font-bold uppercase text-xs text-base-content group-hover:text-primary"><?= $row->judul_masalah_det ?></span>
                                    <span class="text-[10px] opacity-50 italic"><?= date('d M Y', strtotime($row->tgl_masalah_det)) ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:col-span-9">
            <div class="card bg-base-100 shadow-xl border border-base-300 min-h-[400px]">
                <div class="card-body" id="detail_content">
                    <div class="flex flex-col items-center justify-center h-full text-center py-20 opacity-30">
                        <div class="bg-base-200 w-24 h-24 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-mouse-pointer text-5xl text-primary"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase italic">Pilih Riwayat</h2>
                        <p class="text-xs">Klik salah satu daftar kronologi di samping untuk melihat detail notulensi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_tambah_det" class="modal">
    <div class="modal-box max-w-4xl p-0 rounded-3xl border-none shadow-2xl">
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <h3 class="font-black text-xl uppercase italic tracking-tighter text-white">Tambah Kronologi / Rapat</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
        </div>
        <form action="<?= base_url('masalah/simpan_detail') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Tanggal Kegiatan</span></label>
                    <input type="date" name="tgl_masalah_det" class="input input-bordered bg-base-200 rounded-xl" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Judul Kegiatan</span></label>
                    <input type="text" name="judul_masalah_det" class="input input-bordered bg-base-200 rounded-xl" placeholder="Contoh: Rapat Mediasi" required>
                </div>
            </div>
            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold uppercase text-xs">Uraian / Kesimpulan</span></label>
                <textarea name="deskripsi" class="textarea textarea-bordered h-40 bg-base-200 rounded-xl"></textarea>
            </div>
            <div class="form-control mb-8 p-6 border-2 border-dashed border-primary/20 rounded-2xl bg-primary/5">
                <label class="label"><span class="label-text font-bold uppercase text-xs italic">Upload Berkas Pendukung</span></label>
                <input type="file" name="berkas" class="file-input file-input-bordered file-input-primary w-full rounded-xl" />
            </div>
            <div class="modal-action">
                <button type="submit" class="btn btn-primary w-full shadow-lg rounded-xl uppercase text-white font-bold">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</dialog>
<dialog id="modal_edit_det" class="modal">
    <div class="modal-box max-w-4xl p-0 rounded-3xl border-none shadow-2xl">
        <div class="bg-warning p-6 text-warning-content flex justify-between items-center">
            <h3 class="font-black text-xl uppercase italic tracking-tighter">Edit Notulensi Rapat</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="<?= base_url('masalah/update_detail') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">
            <input type="hidden" name="id_masalah_det" id="edit_id_det">
            <input type="hidden" name="old_berkas" id="edit_old_berkas">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Tanggal Kegiatan</span></label>
                    <input type="date" name="tgl_masalah_det" id="edit_tgl" class="input input-bordered bg-base-200 rounded-xl" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Judul Kegiatan</span></label>
                    <input type="text" name="judul_masalah_det" id="edit_judul" class="input input-bordered bg-base-200 rounded-xl uppercase" required>
                </div>
            </div>
            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Uraian / Kesimpulan</span></label>
                <textarea name="deskripsi" id="edit_deskripsi" class="textarea textarea-bordered h-40 bg-base-200 rounded-xl"></textarea>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-bold uppercase text-xs italic text-warning">Ganti Berkas (Opsional)</span></label>
                <input type="file" name="berkas" id="input_file_edit" class="file-input file-input-bordered file-input-warning w-full rounded-xl" />
            </div>
            <div class="flex flex-col">
                <label class="label"><span class="label-text font-bold uppercase text-xs opacity-50">Pratinjau Berkas Saat Ini</span></label>
                <div id="preview_container" class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-full min-h-[300px] flex items-center justify-center overflow-hidden">
                    <iframe id="edit_preview_iframe" class="w-full h-full" src="" style="display: none;"></iframe>
                    <div id="no_preview" class="text-center opacity-30">
                        <i class="mdi mdi-file-hidden text-5xl"></i>
                        <p class="text-xs font-bold uppercase">Tidak ada berkas</p>
                    </div>
                </div>
            </div>
            <div class="modal-action flex gap-2">
                <button type="submit" class="btn btn-warning flex-1 shadow-lg rounded-xl uppercase font-black text-white">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</dialog>
<script>
    /**
     * AJAX Load Detail Content
     */
    function loadContent(element) {
        const activeEl = $(element);
        const id = activeEl.data('id');

        // UI State: Active Menu
        $('#menu_history a').removeClass('bg-primary text-white shadow-md scale-105 border-primary')
            .addClass('bg-base-100 text-base-content border-base-200');
        activeEl.addClass('bg-primary text-white shadow-md scale-105 border-primary')
            .removeClass('bg-base-100 text-base-content border-base-200');

        // Loading State
        $('#detail_content').html(`
            <div class="flex flex-col items-center justify-center min-h-[350px] opacity-50">
                <span class="loading loading-spinner loading-lg text-primary"></span>
                <p class="mt-4 text-[10px] font-black uppercase tracking-widest animate-pulse">Mengambil Data...</p>
            </div>
        `);

        // AJAX Request
        $.ajax({
            url: "<?= base_url('masalah/get_detail_content') ?>",
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                $('#detail_content').hide().html(response).fadeIn(300);
            },
            error: function() {
                $('#detail_content').html('<div class="alert alert-error">Gagal memuat data.</div>');
            }
        });
    }
</script>


<script>
    function editDet(id) {
        $.getJSON("<?= base_url('masalah/get_det_by_id/') ?>" + id, function(data) {
            // 1. Isi field teks
            $('#edit_id_det').val(data.id_masalah_det);
            $('#edit_old_berkas').val(data.berkas);
            $('#edit_tgl').val(data.tgl_masalah_det);
            $('#edit_judul').val(data.judul_masalah_det);
            $('#edit_deskripsi').val(data.deskripsi);

            // 2. Logic Pratinjau Berkas
            const iframe = $('#edit_preview_iframe');
            const noPreview = $('#no_preview');

            if (data.berkas) {
                // Path sesuaikan dengan folder upload Anda
                const fileUrl = "<?= base_url('assets/berkas_permasalahan/') ?>" + data.berkas;
                iframe.attr('src', fileUrl).show();
                noPreview.hide();
            } else {
                iframe.hide().attr('src', '');
                noPreview.show();
            }

            // 3. Tampilkan Modal
            document.getElementById('modal_edit_det').showModal();
        });
    }

    // 3. Tambahan: Preview instan saat user memilih file baru di komputer
    $('#input_file_edit').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#edit_preview_iframe').attr('src', e.target.result).show();
                $('#no_preview').hide();
            }
            reader.readAsDataURL(file);
        }
    });
</script>
<script>
    // Fungsi Trigger Hapus dengan SweetAlert2
    function hapusDet(id) {
        Swal.fire({
            theme: 'auto',
            title: 'Hapus Data?',
            text: "Catatan dan berkas ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('masalah/hapus_det'); ?>",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function() {
                        Swal.fire({
                            theme: 'auto',
                            title: 'Terhapus!',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 1100);
                    }
                });
            }
        });
    }
</script>