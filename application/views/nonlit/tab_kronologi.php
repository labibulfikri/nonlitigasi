<div class="container mx-auto p-4 mb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-8 card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-4">
                <?php $this->load->view($peta) ?>
            </div>
        </div>
        <div class="lg:col-span-4 card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-6">
                <h2 class="card-title text-primary uppercase font-black tracking-widest mb-4">
                    <i class="mdi mdi-information-outline"></i> Detail
                </h2>
                <?php $this->load->view($tab) ?>
            </div>
        </div>
    </div>

    <div class="tabs tabs-boxed bg-base-200 p-2 mb-8 justify-center lg:justify-start">
        <a class="tab tab-lg flex-1 lg:flex-none font-bold" href="<?= base_url('nonlit/detail/' . $id) ?>">Detail Perkara</a>
        <a class="tab tab-lg flex-1 lg:flex-none font-bold tab-active btn-primary shadow-lg" href="<?= base_url('nonlit/tab_kronologi/' . $id) ?>">Berkas Pendukung</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-4">
            <div class="card bg-base-100 shadow-lg border border-base-200 sticky top-4">
                <div class="p-6 border-b border-base-200 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                    <h3 class="font-black text-slate-700 uppercase text-sm tracking-widest">Daftar Lampiran</h3>
                    <button onclick="modal_tambah_lampiran.showModal()" class="btn btn-circle btn-sm btn-warning shadow-lg">
                        <i class="mdi mdi-plus text-xl text-white"></i>
                    </button>
                </div>

                <div class="card-body p-4 max-h-[600px] overflow-y-auto">
                    <?= crsf_ajax() ?>
                    <ul class="menu menu-vertical gap-3" id="menu_berkas">
                        <?php foreach ($lampiran as $key) { ?>
                            <li>
                                <a onclick="setActiveMenuKronologi(this)" id="<?= $key->id ?>"
                                    class="flex items-center gap-4 p-4 border border-base-200 hover:bg-primary hover:text-white transition-all rounded-2xl group shadow-sm">
                                    <div class="bg-primary group-hover:bg-white/20 p-3 rounded-xl">
                                        <i class="mdi mdi-file-document-outline text-2xl"></i>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <span class="font-bold text-black uppercase text-xs block truncate"><?= $key->judul_berkas ?></span>
                                        <span class="text-black opacity-60">ID Berkas: #<?= $key->id ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="card bg-base-100 shadow-2xl border border-base-200 min-h-[500px]">
                <div class="card-body p-0 flex items-center justify-center" id="content_lampiran">
                    <div class="text-center opacity-30 p-10">
                        <div class="bg-slate-100 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="mdi mdi-file-search text-6xl"></i>
                        </div>
                        <h2 class="text-2xl font-black uppercase italic tracking-tighter">Preview Dokumen</h2>
                        <p class="text-sm">Silakan pilih berkas di samping untuk menampilkan isi dokumen</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<dialog id="modal_tambah_lampiran" class="modal">
    <div class="modal-box max-w-2xl p-0 overflow-hidden rounded-3xl border-none">
        <div class="bg-warning p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="mdi mdi-file-plus text-2xl"></i>
                <h3 class="font-black text-xl uppercase tracking-tighter">Tambah Lampiran Baru</h3>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>

        <form action="<?= base_url('nonlit/upload_berkas_lampiran'); ?>" method="post" enctype="multipart/form-data" class="p-8">
            <?= crsf() ?>
            <input type="hidden" name="id_nonlit" value="<?= $id ?>">

            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Judul Berkas / Nama Dokumen</span></label>
                <input type="text" name="judul_berkas" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-warning" placeholder="Contoh: Sertifikat Aset A" required>
            </div>

            <div class="form-control mb-8">
                <div class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-warning/30 rounded-2xl bg-warning/5 hover:bg-warning/10 transition-colors">
                    <i class="mdi mdi-cloud-upload text-4xl text-warning mb-2"></i>
                    <p class="text-xs text-slate-500 mb-4">Pilih file (PDF, JPG, PNG)</p>
                    <input type="file" name="file" class="file-input file-input-bordered file-input-warning w-full rounded-xl" required />
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog" class="flex-1"><button class="btn btn-ghost w-full rounded-xl uppercase">Batal</button></form>
                <button type="submit" class="btn btn-warning flex-1 text-white shadow-lg shadow-orange-100 rounded-xl uppercase">Upload Berkas</button>
            </div>
        </form>
    </div>
</dialog>
<dialog id="modal_edit_lampiran" class="modal">
    <div class="modal-box max-w-4xl p-0 rounded-3xl border-none shadow-2xl bg-base-100">

        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="mdi mdi-file-edit text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-xl uppercase tracking-tighter">Edit Lampiran Berkas</h3>
                    <p class="text-[10px] opacity-70 uppercase font-bold tracking-widest">Pembaruan Dokumen Pendukung</p>
                </div>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
            </form>
        </div>

        <form action="<?= base_url('nonlit/update_berkas_lampiran') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf() ?>

            <input type="hidden" name="id_nonlit" id="edit_id_nonlit_berkas">
            <input type="hidden" name="id" id="edit_id_det_berkas">
            <input type="hidden" name="old_image" id="edit_lampiran_old">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold uppercase text-xs text-slate-500">Judul Berkas</span>
                        </label>
                        <input type="text" name="judul_berkas" id="edit_judul_berkas"
                            class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary w-full font-semibold"
                            placeholder="Contoh: Surat Perjanjian Kerjasama" required>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-bold uppercase text-xs text-slate-500">Ganti Berkas (PDF/Gambar)</span>
                        </label>
                        <div class="group relative">
                            <input type="file" name="new_image"
                                class="file-input file-input-bordered file-input-primary w-full rounded-xl transition-all" />
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-error italic font-medium">*Kosongkan jika tidak ingin mengganti file</span>
                        </label>
                    </div>

                    <div class="alert alert-info bg-blue-50 border-none rounded-2xl p-4">
                        <i class="mdi mdi-information-outline text-blue-600"></i>
                        <span class="text-xs text-blue-700 leading-tight">Pastikan format file sesuai (PDF/JPG/PNG) dengan ukuran maksimal yang ditentukan.</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="label text-center block">
                        <span class="label-text font-bold uppercase text-xs text-slate-500">Pratinjau Berkas Lama</span>
                    </label>
                    <div class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-[400px] overflow-hidden flex items-center justify-center relative shadow-inner">
                        <iframe id="edit_lampiran" class="w-full h-full rounded-xl" src="" style="display: none;"></iframe>
                        <div id="no_preview_lampiran" class="text-center opacity-30">
                            <i class="mdi mdi-file-question text-6xl"></i>
                            <p class="text-xs font-bold uppercase mt-2">Pratinjau tidak tersedia</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-action mt-10 flex gap-3 border-t pt-6 border-base-200">
                <button type="button" onclick="document.getElementById('modal_edit_lampiran').close()"
                    class="btn btn-ghost flex-1 rounded-xl uppercase font-bold text-slate-500">Batal</button>
                <button type="submit"
                    class="btn btn-primary flex-[2] text-white shadow-lg shadow-blue-200 rounded-xl uppercase font-bold tracking-wider">
                    <i class="mdi mdi-check-all mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</dialog>
<script>
    function setActiveMenuKronologi(element) {
        const activeEl = $(element);
        const id = activeEl.attr('id');
        const token = $('#token').val();

        // Ambil Nama Token & Hash secara dinamis dari CI
        const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        const csrfHash = $('#token').val();

        // UI Feedback: Set Active State di Sidebar
        $('#menu_berkas a').removeClass('bg-primary text-white shadow-xl translate-x-2').addClass('bg-base-100 text-slate-700');
        activeEl.addClass('bg-primary text-white shadow-xl translate-x-2').removeClass('bg-base-100 text-slate-700');

        // Tampilkan Loading secara instan tanpa fadeOut lama-lama
        $('#content_lampiran').html(`
        <div class="flex flex-col items-center justify-center p-20 w-full text-center animate-pulse">
            <span class="loading loading-ring loading-lg text-primary scale-150"></span>
            <h3 class="mt-6 font-black uppercase italic text-slate-400 tracking-widest">Menyiapkan Dokumen...</h3>
        </div>
    `);

        // AJAX Request
        $.ajax({
            url: '<?= base_url('nonlit/get_content_berkas'); ?>',
            type: 'POST',
            data: {
                id: id,
                token: token // Mengirimkan token dengan key yang benar
            },
            success: function(response) {
                // Sembunyikan dulu lalu munculkan dengan data baru
                $('#content_lampiran').hide().html(response).fadeIn(400);
            },
            error: function(xhr) {
                console.error(xhr.responseText); // Cek error di console F12
                $('#content_lampiran').html(`
                <div class="p-10 text-center">
                    <div class="alert alert-error shadow-lg max-w-sm mx-auto">
                        <i class="mdi mdi-alert-circle"></i>
                        <span>Gagal memuat data. Periksa koneksi atau Token CSRF.</span>
                    </div>
                </div>
            `);
            }
        });
    }
</script>

<!-- <script>
    $(document).ready(function() {
        // Ketika salah satu menu di klik
        var token = $('#token').val();
        $('#menu_berkas li').click(function() {
            var id = $(this).attr('id'); // Dapatkan ID dari menu yang diklik

            // Lakukan permintaan AJAX ke controller untuk mendapatkan konten yang sesuai
            $.ajax({
                url: '<?= base_url('nonlit/get_content_berkas'); ?>', // Sesuaikan dengan URL controller Anda
                type: 'POST',
                data: {
                    id: id,
                    token: token
                },
                success: function(response) {
                    // Perbarui konten dengan respons yang diterima dari controller
                    // $('#content').html(response);
                    $('#content_lampiran').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script> -->

<script>
    $(document).on('click', '#btnEditLampiran', function() {
        // 1. Ambil data dari tombol yang diklik
        const id_nonlit = $(this).data("idnonlit");
        const id = $(this).data("id");
        const judul_berkas = $(this).data("judul_berkas");
        const nama_berkas = $(this).data("nama_berkas");

        // 2. Masukkan ke input form modal
        $('#edit_id_nonlit_berkas').val(id_nonlit);
        $('#edit_id_det_berkas').val(id);
        $('#edit_judul_berkas').val(judul_berkas);
        $('#edit_lampiran_old').val(nama_berkas);

        // 3. Logika Pratinjau Iframe
        const iframe = $('#edit_lampiran');
        const noPreview = $('#no_preview_lampiran');

        if (nama_berkas) {
            iframe.attr('src', "<?= base_url('assets/berkas_lampiran/') ?>" + nama_berkas).show();
            noPreview.hide();
        } else {
            iframe.hide();
            noPreview.show();
        }

        // 4. Buka Modal DaisyUI
        document.getElementById('modal_edit_lampiran').showModal();
    });
</script>
<script>
    $(document).on('click', '#btnCloseDetLampiran', function() {

        $('#edit_id_nonlit_berkas').val('');
        $('#edit_id_det_berkas').val('');
        $('#edit_judul_berkas').val('');
        $('#edit_lampiran_old').val('');
        document.getElementById('edit_lampiran').style.display = "none";

        $('#modal_edit_lampiran').modal('hide');
    });
</script>


<script>
    //fungsi delete
    $(document).on('click', '.hapus_lampiran', function() {
        // var id_aset = $(this).attr("id");

        var id = $(this).attr("id");
        var id_nonlit = $(this).data("idnonlit");
        var token = $('#token').val();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Anda ingin Menghapus Data Lampiran ",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ya',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo base_url(); ?>nonlit/hapus_lampiran",
                    method: "POST",
                    onBeforeOpen: function() {
                        Swal.fire({
                            title: 'Menunggu',
                            html: 'Memproses data',
                            onOpen: () => {
                                swal.showLoading()
                            }
                        })
                    },
                    data: {
                        id: id,
                        id_nonlit: id_nonlit,
                        token: token,
                    },
                    success: function(data) {
                        Swal.fire(
                            'Berhasil',
                            'Berhasil Menghapus Data',
                            'success'
                        )
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Batal',
                    'Anda membatalkan penghapusan',
                    'error'
                )
            }
        })
    });
</script>