<div class="min-h-screen bg-[#F1F5F9] p-0 md:p-8 font-sans antialiased text-slate-900">
    <div class="max-w-[1600px] mx-auto space-y-6">

        <header class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-50 rounded-full blur-3xl -mr-48 -mt-48 opacity-50"></div>
            <div class="relative flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-slate-900 rounded-[1.5rem] flex items-center justify-center text-white shadow-2xl">
                            <i class="mdi mdi-scale-balance text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black tracking-tighter text-slate-800 uppercase italic leading-none mb-2">
                                <?= $masalah->nama_masalah ?>
                            </h1>
                            <div class="flex flex-wrap gap-2">
                                <span class="badge bg-indigo-100 border-none text-indigo-700 font-black px-3 py-3 rounded-lg text-[10px] uppercase tracking-widest">
                                    <?= $masalah->status_masalah ?>
                                </span>
                                <span class="badge bg-slate-100 border-none text-slate-500 font-bold px-3 py-3 rounded-lg text-[10px] uppercase">
                                    <i class="mdi mdi-map-marker mr-1"></i> <?= $masalah->alamat_masalah ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 bg-slate-50 p-3 rounded-[2rem] border border-slate-100">
                    <button onclick="modal_tambah_rapat.showModal()" class="btn bg-white hover:bg-indigo-600 hover:text-white border-slate-200 text-indigo-600 px-6 rounded-2xl shadow-sm italic font-black text-xs transition-all">
                        <i class="mdi mdi-account-group-outline mr-2 text-xl"></i> + RESUME RAPAT
                    </button>
                    <button onclick="modal_tambah_kronologi.showModal()" class="btn bg-white hover:bg-amber-500 hover:text-white border-slate-200 text-amber-600 px-6 rounded-2xl shadow-sm italic font-black text-xs transition-all">
                        <i class="mdi mdi-cloud-upload-outline mr-2 text-xl"></i> + UPLOAD KRONOLOGI
                    </button>
                </div>
            </div>
        </header>

        <main class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <aside class="lg:col-span-4 xl:col-span-3 space-y-6">
                <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden flex flex-col h-[750px]">
                    <div class="grid grid-cols-2 border-b border-slate-100">
                        <button onclick="switchTab('rapat')" id="tab-rapat" class="p-5 font-black text-[10px] uppercase tracking-[0.2em] border-b-2 border-indigo-600 text-indigo-600 bg-indigo-50/30 transition-all">
                            Resume Rapat
                        </button>
                        <button onclick="switchTab('kronologi')" id="tab-kronologi" class="p-5 font-black text-[10px] uppercase tracking-[0.2em] border-b-2 border-transparent text-slate-400 hover:bg-slate-50 transition-all">
                            Kronologi
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-5 space-y-4 custom-scrollbar bg-white" id="list_container">
                        <?php foreach ($details as $row): ?>
                            <div onclick="loadContent(this)"
                                data-id="<?= $row->id ?>"
                                data-type="<?= $row->tipe ?>"
                                class="item-card group p-5 bg-slate-50 border border-slate-100 rounded-[1.5rem] cursor-pointer hover:bg-white hover:border-slate-300 hover:shadow-xl transition-all active:scale-95 <?= $row->tipe !== 'rapat' ? 'hidden' : '' ?>">

                                <div class="flex justify-between items-start mb-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center <?= $row->tipe === 'rapat' ? 'bg-indigo-600 text-white' : 'bg-amber-500 text-white' ?>">
                                        <i class="mdi <?= $row->tipe === 'rapat' ? 'mdi-text-box-check' : 'mdi-file-multiple' ?> text-xl"></i>
                                    </div>

                                    <span class="text-[10px] font-bold text-slate-400">
                                        <?= !empty($row->tgl) ? date('d/m/y', strtotime($row->tgl)) : '-' ?>
                                    </span>
                                </div>

                                <h4 class="text-xs font-black text-slate-700 uppercase leading-snug">
                                    <?= $row->judul ?>
                                </h4>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <section class="lg:col-span-8 xl:col-span-9">
                <div id="detail_content" class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm min-h-[750px] overflow-hidden relative flex items-center justify-center text-center">
                    <div class="opacity-20 p-10">
                        <i class="mdi mdi-folder-open text-8xl mb-6"></i>
                        <h2 class="text-2xl font-black uppercase italic tracking-tighter text-slate-400">Pilih Dokumen</h2>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<dialog id="modal_tambah_rapat" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-3xl p-0 rounded-[2.5rem]  border-none shadow-2xl">
        <div class="p-10 text-white bg-indigo-600 flex justify-between items-center">
            <div>
                <h3 class="text-3xl font-black uppercase italic tracking-tighter mb-1">Resume Rapat</h3>
                <p class="text-[10px] font-bold opacity-70 uppercase tracking-widest text-indigo-100">Input Notulensi & File PDF tunggal</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
        </div>
        <form action="<?= base_url('masalah/simpan_resume') ?>" method="POST" enctype="multipart/form-data" class="p-10 space-y-6">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control">
                    <label class="label font-black text-[10px] uppercase text-slate-400">Tgl. Pertemuan</label>
                    <input type="date" name="tgl_masalah_det" class="input input-bordered h-14 rounded-2xl bg-slate-50" required>
                </div>
                <div class="form-control">
                    <label class="label font-black text-[10px] uppercase text-slate-400">Judul Rapat</label>
                    <input type="text" name="judul_masalah_det" class="input input-bordered h-14 rounded-2xl bg-slate-50" placeholder="Misal: Rapat Koordinasi" required>
                </div>
            </div>
            <div class="form-control">
                <label class="label font-black text-[10px] uppercase text-slate-400">Isi Notulensi</label>
                <textarea name="deskripsi" class="textarea textarea-bordered h-40 rounded-2xl bg-slate-50 p-5" placeholder="Tuliskan ringkasan diskusi..."></textarea>
            </div>
            <div class="p-6 border-2 border-dashed border-indigo-100 rounded-2xl bg-indigo-50/30">
                <label class="label p-0 font-black text-[10px] uppercase text-indigo-600 italic mb-3">Lampiran Single PDF</label>
                <input type="file" name="berkas" class="file-input file-input-bordered file-input-primary w-full rounded-xl" accept=".pdf" required />
            </div>
            <button type="submit" class="btn btn-block h-16 bg-indigo-600 hover:bg-indigo-700 border-none text-white rounded-2xl font-black uppercase italic shadow-xl shadow-indigo-100 transition-all">
                Simpan Notulensi
            </button>
        </form>
    </div>
</dialog>

<dialog id="modal_tambah_kronologi" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-4xl p-0 rounded-[2rem]  border-none shadow-2xl">
        <div class="p-6 bg-amber-500 text-white flex justify-between items-center">
            <h3 class="text-xl font-bold uppercase italic tracking-tighter">Upload Kronologi (Banyak File)</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
        </div>

        <form id="form_upload_kronologi" class="p-6 space-y-6">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">

            <div class="border-4 border-dashed border-amber-100 rounded-[2rem] p-10 text-center relative cursor-pointer hover:bg-amber-50 transition-all group">
                <input type="file" id="file_kronologi" multiple accept=".jpg,.jpeg,.png,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="space-y-2">
                    <i class="mdi mdi-cloud-upload text-5xl text-amber-500 group-hover:scale-110 transition-transform inline-block"></i>
                    <p class="font-bold text-slate-700 uppercase">Klik / Drag banyak file ke sini</p>
                    <p class="text-xs text-slate-400">JPG, PNG, PDF (Maks 5MB per file)</p>
                </div>
            </div>

            <div id="preview_zone" class="hidden">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <span class="text-xs font-black uppercase text-slate-500">Preview Berkas</span>
                    <span id="file_count_badge" class="badge badge-warning font-bold text-white">0 File</span>
                </div>
                <div id="file_list_preview" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
            </div>

            <button type="button" id="btn_upload_kronologi" onclick="submitKronologiMultiple()" class="btn btn-warning w-full h-14 rounded-2xl text-white font-black uppercase italic shadow-lg shadow-amber-200 hidden">
                <i class="mdi mdi-check-all mr-2"></i> Konfirmasi Upload Kronologi
            </button>
        </form>
    </div>
</dialog>
<!-- <dialog id="modal_edit_rapat" class="modal">
    <div class="modal-box">
        <form action="<?= base_url('masalah/update_rapat') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_masalah_det" id="edit_rapat_id">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">
            <input type="hidden" name="old_berkas" id="edit_rapat_old_file">
            <input type="text" name="judul_masalah_det" id="edit_rapat_judul" placeholder="Judul Rapat" class="input input-bordered w-full mb-4">
            <input type="date" name="tgl_masalah_det" id="edit_rapat_tgl" class="input input-bordered w-full mb-4">
            <textarea name="deskripsi" id="edit_rapat_deskripsi" placeholder="Deskripsi Rapat" class="textarea textarea-bordered w-full mb-4"></textarea>

            <p class="text-xs text-slate-400">Jika tidak ada file baru yang dipilih, file lama akan dipertahankan.</p>
            <input type="file" name="berkas" class="file-input file-input-bordered w-full" accept=".pdf" />



            <button type="submit" class="btn btn-primary">Simpan Rapat</button>
        </form>
    </div>
</dialog> -->


<dialog id="modal_edit_rapat" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl p-0 rounded-[2.5rem]  border-none shadow-2xl">
        <div class="p-8 text-white bg-indigo-600 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black uppercase italic tracking-tighter mb-1">Edit Resume Rapat</h3>
                <p class="text-[10px] font-bold opacity-70 uppercase tracking-widest text-indigo-100">Perbarui notulensi & berkas lampiran</p>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
            </form>
        </div>

        <form action="<?= base_url('masalah/update_rapat') ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id_masalah_det" id="edit_rapat_id">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">
            <input type="hidden" name="old_berkas" id="edit_rapat_old_file">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label font-black text-[10px] uppercase text-slate-400">Judul Pertemuan</label>
                    <div class="relative">
                        <i class="mdi mdi-format-title absolute left-4 top-4 text-slate-400"></i>
                        <input type="text" name="judul_masalah_det" id="edit_rapat_judul"
                            class="input input-bordered w-full pl-11 rounded-2xl bg-slate-50 focus:bg-white transition-all font-bold text-slate-700"
                            placeholder="Judul Rapat" required>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label font-black text-[10px] uppercase text-slate-400">Tanggal Rapat</label>
                    <div class="relative">
                        <i class="mdi mdi-calendar absolute left-4 top-4 text-slate-400"></i>
                        <input type="date" name="tgl_masalah_det" id="edit_rapat_tgl"
                            class="input input-bordered w-full pl-11 rounded-2xl bg-slate-50 focus:bg-white transition-all font-bold text-slate-700" required>
                    </div>
                </div>
            </div>

            <div class="form-control">
                <label class="label font-black text-[10px] uppercase text-slate-400">Ringkasan Notulensi</label>
                <textarea name="deskripsi" id="edit_rapat_deskripsi"
                    class="textarea textarea-bordered h-32 rounded-2xl bg-slate-50 focus:bg-white p-5 font-medium text-slate-600"
                    placeholder="Tuliskan perubahan notulensi di sini..."></textarea>
            </div>

            <div class="p-6 border-2 border-dashed border-indigo-100 rounded-2xl bg-indigo-50/30">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                        <i class="mdi mdi-file-pdf-box text-2xl"></i>
                    </div>
                    <div>
                        <label class="label p-0 font-black text-[10px] uppercase text-indigo-600 italic">Ganti Lampiran PDF</label>
                        <p class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter italic leading-none">Kosongkan jika tidak ingin mengganti file</p>
                    </div>
                </div>
                <input type="file" name="berkas" class="file-input file-input-bordered file-input-primary w-full rounded-xl bg-white" accept=".pdf" />
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-block h-16 bg-indigo-600 hover:bg-indigo-700 border-none text-white rounded-2xl font-black uppercase italic shadow-xl shadow-indigo-100 transition-all active:scale-95">
                    <i class="mdi mdi-check-decagram mr-2 text-xl"></i> Simpan Perubahan Rapat
                </button>
            </div>
        </form>
    </div>
</dialog>
<dialog id="modal_edit_kronologi" class="modal">
    <div class="modal-box">
        <form action="<?= base_url('masalah/update_kronologi') ?>" method="POST">
            <input type="hidden" name="id_bahan_masalah" id="edit_kronologi_id">
            <input type="hidden" name="id_masalah" value="<?= $masalah->id_masalah ?>">
            <button type="submit" class="btn btn-warning">Simpan Kronologi</button>
        </form>
    </div>
</dialog>
<script>
    let selectedFiles = []; // Array penampung file kronologi

    // 1. Tab Switcher
    function switchTab(type) {
        $('#tab-rapat, #tab-kronologi').removeClass('border-indigo-600 text-indigo-600 bg-indigo-50/30 text-amber-600 border-amber-500 bg-amber-50/30 border-transparent text-slate-400 bg-transparent');
        if (type === 'rapat') {
            $('#tab-rapat').addClass('border-indigo-600 text-indigo-600 bg-indigo-50/30');
            $('.item-card[data-type="rapat"]').show();
            $('.item-card[data-type="kronologi"]').hide();
        } else {
            $('#tab-kronologi').addClass('border-amber-500 text-amber-600 bg-amber-50/30');
            $('.item-card[data-type="kronologi"]').show();
            $('.item-card[data-type="rapat"]').hide();
        }
    }

    // 2. Load Content Detail via AJAX
    function loadContent(element) {
        const id = $(element).data('id');
        const type = $(element).data('type');

        $('.item-card').removeClass('ring-2 ring-indigo-500 bg-white shadow-2xl scale-[1.02]');
        $(element).addClass('ring-2 ring-indigo-500 bg-white shadow-2xl scale-[1.02]');

        $('#detail_content').html(`
            <div class="flex flex-col items-center justify-center h-[600px] w-full">
                <span class="loading loading-spinner loading-lg text-indigo-600"></span>
                <p class="mt-4 text-[10px] font-black uppercase tracking-[0.5em] text-slate-300">Menarik Data...</p>
            </div>
        `);

        $.ajax({
            url: "<?= base_url('masalah/get_detail_content') ?>",
            type: "POST",
            data: {
                id: id,
                type: type
            },
            success: function(res) {
                $('#detail_content').hide().html(res).fadeIn(400);
            }
        });
    }

    // 3. Kronologi Multiple File Handling
    document.getElementById('file_kronologi').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert(file.name + " melebihi 5MB!");
                return;
            }
            selectedFiles.push(file);
        });
        renderPreview();
    });

    function renderPreview() {
        const container = document.getElementById('file_list_preview');
        const previewZone = document.getElementById('preview_zone');
        const btnUpload = document.getElementById('btn_upload_kronologi');
        const badge = document.getElementById('file_count_badge');

        container.innerHTML = "";
        if (selectedFiles.length > 0) {
            previewZone.classList.remove('hidden');
            btnUpload.classList.remove('hidden');
            badge.innerText = selectedFiles.length + " File";

            selectedFiles.forEach((file, index) => {
                const isImage = file.type.startsWith('image/');
                const card = document.createElement('div');
                card.className = "relative border rounded-lg overflow-hidden bg-slate-50";

                let content = isImage ?
                    `<img src="${URL.createObjectURL(file)}" class="w-full h-24 object-cover">` :
                    `<div class="h-24 flex flex-col items-center justify-center text-rose-500 bg-rose-50"><i class="mdi mdi-file-pdf-box text-4xl"></i><span class="text-[8px] font-bold">PDF</span></div>`;

                card.innerHTML = `
                    ${content}
                    <div class="p-2 text-[10px] truncate font-bold text-slate-600">${file.name}</div>
                    <button type="button" onclick="removeFile(${index})" class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full text-xs flex items-center justify-center shadow-lg">✕</button>
                `;
                container.appendChild(card);
            });
        } else {
            previewZone.classList.add('hidden');
            btnUpload.classList.add('hidden');
        }
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        renderPreview();
    }

    // 4. AJAX Submit Kronologi
    function submitKronologiMultiple() {
        if (selectedFiles.length === 0) return Swal.fire('Opps', 'Pilih file dulu', 'warning');

        let formData = new FormData();
        formData.append('id_masalah', '<?= $masalah->id_masalah ?>');

        selectedFiles.forEach(file => {
            formData.append('berkas[]', file);
        });

        formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');

        $('#btn_upload_kronologi').addClass('loading').prop('disabled', true);

        $.ajax({
            url: "<?= base_url('masalah/simpan_kronologi_multiple') ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#btn_upload_kronologi').removeClass('loading').prop('disabled', false);

                if (res.trim() === "success") {
                    // TUTUP MODAL DULU agar SweetAlert muncul paling depan
                    const modal = document.getElementById('modal_tambah_kronologi');
                    if (modal) modal.close();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Berkas kronologi telah diupload.',
                        icon: 'success',
                        confirmButtonText: 'Mantap',
                        // Pastikan z-index tinggi jika masih tertutup (opsional)
                        target: 'body'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal Upload', res, 'error');
                }
            },
            error: function(xhr) {
                $('#btn_upload_kronologi').removeClass('loading').prop('disabled', false);
                Swal.fire('Error Server', 'File terlalu banyak/besar atau permission folder salah.', 'error');
            }
        });
    }
</script>

<script>
    // 1. Fungsi Hapus
    function hapusDetail(id, type) {
        Swal.fire({
            title: 'Hapus Dokumen?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('masalah/hapus_detail/') ?>" + id + "/" + type,
                    type: "GET",
                    success: function(res) {
                        if (res.trim() === "success") {
                            Swal.fire('Terhapus!', 'Data berhasil dibuang.', 'success')
                                .then(() => location.reload());
                        }
                    }
                });
            }
        });
    }

    function openEditRapat(id, id_masalah, type, judul, tgl, deskripsi, berkas = '') {
        if (type === 'rapat') {
            // Isi data ke Modal Rapat
            $('#edit_rapat_id').val(id);
            $('#edit_rapat_judul').val(judul);
            $('#edit_rapat_tgl').val(tgl);
            $('#edit_rapat_deskripsi').val(deskripsi);
            $('#edit_rapat_old_file').val(berkas); // Jika ada parameter berkas

            modal_edit_rapat.showModal();
        } else {
            // Isi data ke Modal Kronologi
            $('#edit_kronologi_id').val(id);
            $('#edit_kronologi_nama').val(judul);
            $('#edit_kronologi_ket').val(deskripsi);

            modal_edit_kronologi.showModal();
        }
    }

    // 3. Fungsi Save Update
    function saveUpdate() {
        let data = $('#form_edit').serialize();

        $.ajax({
            url: "<?= base_url('masalah/update_detail') ?>",
            type: "POST",
            data: data,
            success: function(res) {
                if (res.trim() === "success") {
                    Swal.fire('Berhasil!', 'Data telah diperbarui.', 'success')
                        .then(() => location.reload());
                }
            }
        });
    }

    function hapusDetail(id, type) {
        let id_masalah = '<?= $masalah->id_masalah ?>'; // Ambil ID utama dari PHP
        Swal.fire({
            title: 'Yakin hapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('masalah/hapus_item/') ?>" + id + "/" + type + "/" + id_masalah;
            }
        });
    }
</script>

<style>
    .swal2-container {
        z-index: 999999 !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #E2E8F0;
        border-radius: 10px;
    }

    .modal-box {
        font-family: 'Jakarta Sans', sans-serif;
    }
</style>