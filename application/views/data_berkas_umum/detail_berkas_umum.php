<div class="p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?= base_url('berkas_umum') ?>" class="btn btn-circle btn-ghost bg-slate-100">
            <i class="mdi mdi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black uppercase italic text-slate-800"><?= $parent->nama_berkas_umum ?></h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?= $parent->keterangan ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm sticky top-8">
                <h3 class="font-black text-xs uppercase mb-6 text-slate-500 tracking-widest text-center">Upload File Baru</h3>
                <form action="<?= base_url('berkas_umum/upload_detail_file') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id_berkas_umum" value="<?= $parent->id_berkas_umum ?>">

                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase text-slate-400">Judul Berkas</span></label>
                        <input type="text" name="judul_file" class="input input-bordered w-full rounded-2xl bg-slate-50 border-none font-bold" placeholder="..." required>
                    </div>

                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase text-slate-400">Pilih File</span></label>
                        <input type="file" name="file_upload" class="file-input file-input-bordered w-full rounded-2xl bg-slate-50 border-none" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-full rounded-2xl uppercase italic font-black shadow-lg shadow-indigo-100 mt-4">
                        <i class="mdi mdi-upload mr-1"></i> Unggah
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php if (empty($files)): ?>
                    <div class="col-span-full p-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                        <i class="mdi mdi-folder-open-outline text-6xl text-slate-200"></i>
                        <p class="text-slate-300 font-bold uppercase text-[10px] mt-4 tracking-widest">Belum ada file di folder ini</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($files as $f): ?>
                    <div class="group bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center group-hover:rotate-6 transition-transform">
                                <?php
                                $ext = pathinfo($f->nama_file, PATHINFO_EXTENSION);
                                $icon = ($ext == 'pdf') ? 'mdi-file-pdf-box text-red-500' : 'mdi-file-document-outline text-indigo-500';
                                ?>
                                <i class="mdi <?= $icon ?> text-3xl"></i>
                            </div>

                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs btn-circle text-slate-300"><i class="mdi mdi-dots-vertical text-lg"></i></label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-3 shadow-2xl bg-base-100 rounded-2xl w-44 text-[10px] font-bold uppercase tracking-tighter">
                                    <li><a href="<?= base_url('assets/berkas_umum/detail/' . $f->nama_file) ?>" target="_blank"><i class="mdi mdi-eye-outline text-indigo-500"></i> Lihat Berkas</a></li>
                                    <li><a href="<?= base_url('assets/berkas_umum/detail/' . $f->nama_file) ?>" download><i class="mdi mdi-download-outline text-blue-500"></i> Download</a></li>
                                    <li><button onclick="editFile(<?= $f->id_berkas_umum_det ?>, '<?= $f->judul_berkas_umum ?>')"><i class="mdi mdi-pencil-outline text-amber-500"></i> Ubah Nama</button></li>
                                    <div class="divider my-1"></div>
                                    <li><button onclick="confirmHapus(<?= $f->id_berkas_umum_det ?>)" class="text-red-500"><i class="mdi mdi-delete-outline"></i> Hapus File</button></li>
                                </ul>
                            </div>
                        </div>

                        <h4 class="font-black text-slate-700 text-xs uppercase truncate pr-4"><?= $f->judul_berkas_umum ?></h4>
                        <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase italic opacity-70"><?= $f->tgl_upload ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_edit_file" class="modal">
    <div class="modal-box rounded-[2.5rem] p-10">
        <h3 class="font-black text-xl uppercase italic mb-6 text-indigo-600">Ubah Judul Berkas</h3>
        <form id="form_edit_det">
            <input type="hidden" name="id_berkas_umum_det" id="edit_id_det">
            <div class="form-control">
                <label class="label"><span class="label-text text-[10px] font-bold uppercase text-slate-400">Judul Berkas Baru</span></label>
                <input type="text" name="judul_file" id="edit_judul_file" class="input input-bordered w-full rounded-2xl font-bold bg-slate-50 border-none" required>
            </div>
            <div class="modal-action">
                <button type="submit" class="btn btn-primary btn-block rounded-2xl font-black uppercase italic">Update Nama</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    function editFile(id, judul) {
        $('#edit_id_det').val(id);
        $('#edit_judul_file').val(judul);
        modal_edit_file.showModal();
    }

    $('#form_edit_det').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= base_url('berkas_umum/update_nama_detail') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function() {
                location.reload();
            }
        });
    });

    function confirmHapus(id) {
        Swal.fire({
            title: 'Hapus Berkas?',
            text: "File fisik akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('berkas_umum/hapus_detail/') ?>" + id;
            }
        });
    }
</script>