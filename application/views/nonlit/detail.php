<div class="container mx-auto p-4 mb-20 text-base-content">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-8 card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body p-4">
                <?= crsf_ajax() ?> <?php $this->load->view($peta) ?>
            </div>
        </div>
        <div class="lg:col-span-4 card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body p-6">
                <h2 class="card-title text-primary uppercase font-black tracking-widest mb-4">
                    <i class="mdi mdi-information-outline"></i> Detail
                </h2>
                <?php $this->load->view($tab) ?>
            </div>
        </div>
    </div>

    <div class="tabs tabs-boxed bg-base-200 p-2 mb-8 justify-center lg:justify-start">
        <a class="tab tab-lg flex-1 lg:flex-none font-bold <?= ($this->uri->segment(2) == 'detail') ? 'tab-active btn-primary shadow-lg' : '' ?>"
            href="<?= base_url('nonlit/detail/' . $id) ?>">Detail Perkara</a>
        <a class="tab tab-lg flex-1 lg:flex-none font-bold <?= ($this->uri->segment(2) == 'tab_kronologi') ? 'tab-active btn-primary shadow-lg' : '' ?>"
            href="<?= base_url('nonlit/tab_kronologi/' . $id) ?>">Berkas Pendukung</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-3">
            <div class="card bg-base-100 shadow-lg border border-base-300 sticky top-4 overflow-hidden">
                <div class="p-4 border-b border-base-300 flex justify-between items-center bg-base-200/50">
                    <h3 class="font-black opacity-70 uppercase text-[10px] tracking-widest">History Rapat</h3>
                    <button onclick="modal_tambah_rapat.showModal()" class="btn btn-circle btn-xs btn-primary shadow-lg">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>

                <div class="card-body p-2 max-h-[500px] overflow-y-auto">
                    <ul class="menu menu-vertical gap-2" id="menu">
                        <?php foreach ($det as $key) { ?>
                            <li>
                                <a onclick="setActiveMenu(this)" id="<?= $key->id ?>"
                                    class="flex flex-col items-start p-4 border border-base-200 hover:bg-primary/5 hover:border-primary transition-all rounded-xl group bg-base-100">
                                    <span class="font-bold uppercase text-xs text-base-content group-hover:text-primary"><?= $key->judul_rapat ?></span>
                                    <span class="text-[10px] opacity-50 italic"><?= date('d M Y', strtotime($key->tgl_rapat)) ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:col-span-9">
            <div class="card bg-base-100 shadow-xl border border-base-300 min-h-[400px]">
                <div class="card-body" id="content">
                    <div class="flex flex-col items-center justify-center h-full text-center py-20 opacity-30">
                        <div class="bg-base-200 w-24 h-24 rounded-full flex items-center justify-center mb-6">
                            <i class="mdi mdi-gesture-tap text-5xl text-primary"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase italic">Pilih History Rapat</h2>
                        <p class="text-xs">Klik salah satu daftar rapat di samping untuk melihat notulensi detail</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <dialog id="modal_tambah_rapat" class="modal">
    <div class="modal-box max-w-4xl p-0 rounded-3xl border-none">
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <h3 class="font-black text-xl uppercase tracking-tighter italic">Tambah Data Rapat</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>

        <form action="<?= base_url('nonlit/upload_berkas') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf(); ?>
            <input type="hidden" name="id_nonlit" value="<?= $id ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Tanggal Rapat</span></label>
                    <input type="date" name="tgl_rapat" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Acara / Judul Rapat</span></label>
                    <input type="text" name="judul_rapat" class="input input-bordered bg-base-200 rounded-xl uppercase" placeholder="Contoh: Rapat Koordinasi Aset" required>
                </div>
            </div>

            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold uppercase text-xs">Kesimpulan Rapat</span></label>
                <textarea name="kesimpulan" class="ckeditor"></textarea>
            </div>

            <div class="form-control mb-8 p-6 border-2 border-dashed border-base-300 rounded-2xl bg-primary/10">
                <label class="label"><span class="label-text font-bold uppercase text-xs italic">Upload Berkas Pendukung (PDF/IMG)</span></label>
                <input type="file" name="file" class="file-input file-input-bordered file-input-primary w-full rounded-xl" />
            </div>

            <div class="modal-action flex gap-2">
                <button class="btn btn-ghost w-full rounded-xl">Batal</button>
                <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-blue-200 rounded-xl uppercase">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</dialog> -->
<dialog id="modal_tambah_rapat" class="modal">
    <div class="modal-box max-w-4xl p-0 rounded-3xl border-none shadow-2xl">
        <div class="bg-primary p-6 text-primary-content flex justify-between items-center">
            <h3 class="font-black text-xl uppercase italic tracking-tighter">Tambah Data Rapat</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="<?= base_url('nonlit/upload_berkas') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf(); ?>
            <input type="hidden" name="id_nonlit" value="<?= $id ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Tanggal Rapat</span></label>
                    <input type="date" name="tgl_rapat" class="input input-bordered bg-base-200 rounded-xl" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Judul Rapat</span></label>
                    <input type="text" name="judul_rapat" class="input input-bordered bg-base-200 rounded-xl uppercase" placeholder="Contoh: Rapat Koordinasi" required>
                </div>
            </div>
            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold uppercase text-xs">Kesimpulan Rapat</span></label>
                <textarea name="kesimpulan" class="ckeditor"></textarea>
            </div>
            <div class="form-control mb-8 p-6 border-2 border-dashed border-primary/20 rounded-2xl bg-primary/5">
                <label class="label"><span class="label-text font-bold uppercase text-xs italic">Upload Berkas (PDF/IMG)</span></label>
                <input type="file" name="file" class="file-input file-input-bordered file-input-primary w-full rounded-xl" />
            </div>
            <div class="modal-action flex gap-2">
                <form method="dialog" class="flex-1"><button class="btn btn-ghost w-full rounded-xl">Batal</button></form>
                <button type="submit" class="btn btn-primary flex-[2] shadow-lg rounded-xl uppercase text-white">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="modal_edit_nonlit_det" class="modal">
    <div class="modal-box max-w-5xl p-0 rounded-3xl border-none shadow-2xl">
        <div class="bg-primary p-6 text-primary-content flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="mdi mdi-pencil-box-outline text-2xl"></i>
                <h3 class="font-black text-xl uppercase">Edit Data Rapat</h3>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="<?= base_url('nonlit/update_nonlit_det') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf() ?>
            <input type="hidden" name="id_nonlit" id="edit_id_nonlit">
            <input type="hidden" name="id" id="edit_id_det">
            <input type="hidden" name="old_image" id="edit_berkas_old">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs">Tanggal Rapat</span></label>
                        <input type="date" name="tgl_rapat" id="edit_tgl_rapat" class="input input-bordered bg-base-200 rounded-xl" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs">Judul Rapat</span></label>
                        <input type="text" name="judul_rapat" id="edit_judul_rapat" class="input input-bordered bg-base-200 rounded-xl" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs">Kesimpulan</span></label>
                        <textarea name="kesimpulan" id="edit_kesimpulan" class="textarea textarea-bordered h-40 bg-base-200 rounded-xl"></textarea>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-primary">Ganti Berkas (Opsional)</span></label>
                        <input type="file" name="new_image" class="file-input file-input-bordered file-input-primary w-full rounded-xl">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="label"><span class="label-text font-bold uppercase text-xs opacity-50 text-center block w-full">Pratinjau Berkas</span></label>
                    <div class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-full min-h-[400px] overflow-hidden">
                        <iframe id="edit_berkas" class="w-full h-full" src="" style="display: none;"></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-action mt-8 flex gap-3 border-t pt-6 border-base-200">
                <form method="dialog" class="flex-1"><button class="btn btn-ghost w-full rounded-xl">Batal</button></form>
                <button type="submit" class="btn btn-primary flex-[2] text-white shadow-lg rounded-xl uppercase font-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</dialog>

<!-- MODAL edit data MASTER -->
<!-- <dialog id="modal_edit_nonlit_det" class="modal">
    <div class="modal-box max-w-4xl p-0  rounded-3xl border-none shadow-2xl">

        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="mdi mdi-pencil-box-outline text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-xl uppercase tracking-tighter">Edit Data Rapat</h3>
                    <p class="text-[10px] opacity-70 uppercase font-bold tracking-widest">Pembaharuan Notulensi & Berkas</p>
                </div>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
            </form>
        </div>

        <form action="<?= base_url('nonlit/update_nonlit_det') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf() ?>

            <input type="hidden" name="id_nonlit" id="edit_id_nonlit">
            <input type="hidden" name="id" id="edit_id_det">
            <input type="hidden" name="old_image" id="edit_berkas_old">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Tanggal Rapat</span></label>
                        <input type="date" name="tgl_rapat" id="edit_tgl_rapat" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Judul / Agenda Rapat</span></label>
                        <input type="text" name="judul_rapat" id="edit_judul_rapat" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary w-full" placeholder="Masukkan judul rapat..." required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Kesimpulan</span></label>
                        <textarea name="kesimpulan" id="edit_kesimpulan" class="textarea textarea-bordered h-48 bg-base-200 rounded-xl focus:ring-2 focus:ring-primary text-sm leading-relaxed" placeholder="Tuliskan poin-poin penting hasil rapat..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Ganti Berkas (PDF/Gambar)</span></label>
                        <input type="file" name="new_image" class="file-input file-input-bordered file-input-primary w-full rounded-xl">
                        <label class="label"><span class="label-text-alt text-error italic">*Kosongkan jika tidak ingin mengubah berkas</span></label>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Preview Berkas Saat Ini</span></label>
                    <div class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-[450px] overflow-hidden flex items-center justify-center">
                        <iframe id="edit_berkas" class="w-full h-full rounded-xl shadow-inner" src="" style="display: none;"></iframe>
                         
                    </div>
                </div>
            </div>

            <div class="modal-action mt-8 flex gap-3 border-t pt-6 border-base-200">
                <button type="button" onclick="document.getElementById('modal_edit_nonlit_det').close()" class="btn btn-primary flex-1 text-white shadow-lg shadow-blue-100 rounded-xl uppercase font-bold" id="btnCloseDet">Batal</button>
                <button type="submit" class="btn btn-primary flex-1 text-white shadow-lg shadow-blue-100 rounded-xl uppercase font-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</dialog> -->



<script>
    function setActiveMenu(element) {
        const activeEl = $(element);
        const id = activeEl.attr('id');
        const token = $('#token').val();

        // 1. Reset & Set State Menu UI
        $('#menu a').removeClass('bg-primary text-primary-content shadow-md scale-105 border-primary')
            .addClass('bg-base-100 text-base-content border-base-200');
        activeEl.addClass('bg-primary text-primary-content shadow-md scale-105 border-primary')
            .removeClass('bg-base-100 text-base-content border-base-200');

        // 2. Loading Animation
        $('#content').html(`
        <div class="flex flex-col items-center justify-center min-h-[350px] opacity-50">
            <span class="loading loading-spinner loading-lg text-primary"></span>
            <p class="mt-4 text-[10px] font-black uppercase tracking-[0.2em] animate-pulse">Memproses Notulensi...</p>
        </div>
    `);

        // 3. AJAX Request
        $.ajax({
            url: "<?= base_url('nonlit/get_content') ?>",
            type: "POST",
            data: {
                id: id,
                token: token
            },
            success: function(response) {
                $('#content').hide().html(response).fadeIn(300);
                // Auto-scroll ke konten jika di layar kecil
                if (window.innerWidth < 1024) {
                    document.getElementById('content').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            },
            error: function() {
                $('#content').html(`
                <div class="alert alert-error shadow-lg rounded-2xl">
                    <i class="mdi mdi-alert-circle"></i>
                    <span class="text-xs font-bold uppercase">Gagal mengambil data. Coba lagi.</span>
                </div>
            `);
            }
        });
    }

    /**
     * Handle Modal Edit - Mengambil data dari tombol ke Modal
     */
    $(document).on('click', '#btnEditDet', function() {
        const d = $(this).data();

        $('#edit_id_nonlit').val(d.idnonlit);
        $('#edit_id_det').val(d.id);
        $('#edit_tgl_rapat').val(d.tglrapat);
        $('#edit_judul_rapat').val(d.judulrapat);
        $('#edit_berkas_old').val(d.berkas);

        // Sync ke CKEditor jika tersedia
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['edit_kesimpulan']) {
            CKEDITOR.instances['edit_kesimpulan'].setData(d.kesimpulan);
        } else {
            $('#edit_kesimpulan').val(d.kesimpulan);
        }

        // Handle Iframe Preview
        const iframe = $('#edit_berkas');
        if (d.berkas) {
            iframe.attr('src', "<?= base_url() ?>assets/berkas_nonlit/" + d.berkas).show();
        } else {
            iframe.hide();
        }

        document.getElementById('modal_edit_nonlit_det').showModal();
    });

    /**
     * Fungsi Hapus dengan SweetAlert2
     */
    $(document).on('click', '.hapus_det', function() {
        const id = $(this).attr("id");
        const id_nonlit = $(this).data("idnonlit");
        const token = $('#token').val();

        Swal.fire({
            title: 'Hapus Data?',
            text: "Notulensi dan berkas terkait akan dihapus permanen.",
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
                    url: "<?= base_url('nonlit/hapus_det'); ?>",
                    method: "POST",
                    data: {
                        id: id,
                        id_nonlit: id_nonlit,
                        token: token
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menghapus...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function() {
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                        setTimeout(() => location.reload(), 1200);
                    }
                });
            }
        });
    });
</script>