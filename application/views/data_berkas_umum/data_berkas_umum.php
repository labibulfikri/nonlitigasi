<div class="p-8 bg-slate-50 min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-slate-800">Berkas Umum</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Manajemen Folder Dokumen Utama</p>
        </div>

        <div class="flex items-center gap-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 w-full md:w-auto">
            <div class="relative flex-1 md:flex-none">
                <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="search_folder" placeholder="Cari folder..." class="input input-sm input-ghost w-full md:w-64 pl-10 focus:bg-slate-50 rounded-xl font-medium">
            </div>

            <div class="divider divider-horizontal mx-0 hidden md:flex"></div>

            <div class="flex gap-1">
                <button onclick="switchView('grid')" id="btn_grid" class="btn btn-sm btn-square btn-primary rounded-xl transition-all">
                    <i class="mdi mdi-view-grid"></i>
                </button>
                <button onclick="switchView('list')" id="btn_list" class="btn btn-sm btn-square btn-ghost rounded-xl transition-all">
                    <i class="mdi mdi-view-list"></i>
                </button>
            </div>

            <button onclick="tambahFolder()" class="btn btn-sm btn-primary rounded-xl px-4 ml-2">
                <i class="mdi mdi-plus-circle mr-1"></i> Baru
            </button>
        </div>
    </div>

    <div id="view_grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($berkas as $b): ?>
            <div class="folder-card group bg-white rounded-[2.5rem] border border-slate-100 p-6 hover:shadow-xl transition-all relative" data-name="<?= strtolower($b->nama_berkas_umum) ?>">
                <div class="absolute top-5 right-5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="editFolder(<?= $b->id_berkas_umum ?>)" class="btn btn-circle btn-xs btn-ghost text-amber-500 bg-amber-50"><i class="mdi mdi-pencil"></i></button>
                    <button onclick="hapusFolder(<?= $b->id_berkas_umum ?>)" class="btn btn-circle btn-xs btn-ghost text-red-500 bg-red-50"><i class="mdi mdi-delete"></i></button>
                </div>
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform">
                    <i class="mdi mdi-folder text-3xl"></i>
                </div>
                <h3 class="font-black text-slate-700 uppercase truncate text-sm mb-1"><?= $b->nama_berkas_umum ?></h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase truncate mb-6"><?= $b->keterangan ?: 'Folder Kosong' ?></p>
                <a href="<?= base_url('berkas_umum/detail/' . $b->id_berkas_umum) ?>" class="btn btn-sm btn-block btn-outline rounded-xl border-slate-200 hover:bg-indigo-600 hover:border-indigo-600">BUKA FOLDER</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="view_list" class="hidden bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
        <table class="table w-full">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest">
                    <th class="p-6">Nama Folder</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($berkas as $b): ?>
                    <tr class="folder-card-list hover:bg-slate-50 border-b border-slate-50 last:border-none" data-name="<?= strtolower($b->nama_berkas_umum) ?>">
                        <td class="p-4 flex items-center gap-4">
                            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                                <i class="mdi mdi-folder text-xl"></i>
                            </div>
                            <span class="font-black text-slate-700 uppercase text-xs"><?= $b->nama_berkas_umum ?></span>
                        </td>
                        <td class="text-[11px] text-slate-400 font-bold uppercase"><?= $b->keterangan ?></td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('berkas_umum/detail/' . $b->id_berkas_umum) ?>" class="btn btn-xs btn-ghost bg-indigo-50 text-indigo-600 rounded-lg">DETAIL</a>
                                <button onclick="editFolder(<?= $b->id_berkas_umum ?>)" class="btn btn-xs btn-ghost bg-amber-50 text-amber-500 rounded-lg"><i class="mdi mdi-pencil"></i></button>
                                <button onclick="hapusFolder(<?= $b->id_berkas_umum ?>)" class="btn btn-xs btn-ghost bg-red-50 text-red-500 rounded-lg"><i class="mdi mdi-delete"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<dialog id="modal_folder" class="modal">
    <div class="modal-box rounded-[2.5rem] p-10">
        <h3 id="modal_title" class="font-black text-2xl uppercase italic text-indigo-600 mb-8">Buat Folder</h3>
        <form id="form_folder">
            <input type="hidden" name="id_berkas_umum" id="id_berkas_umum">
            <div class="space-y-6">
                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">Judul Folder</span></label>
                    <input type="text" name="nama_berkas_umum" id="nama_berkas_umum" class="input input-bordered w-full rounded-2xl font-bold bg-slate-50 border-none" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">Penyimpanan Rak</span></label>
                    <input type="text" name="penyimpanan_rak" id="penyimpanan_rak" class="input input-bordered w-full rounded-2xl font-bold bg-slate-50 border-none" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">Keterangan</span></label>
                    <textarea name="keterangan" id="keterangan" class="textarea textarea-bordered rounded-2xl h-24 bg-slate-50 border-none"></textarea>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary btn-block rounded-2xl font-black uppercase italic">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    // Live Search Logic
    document.getElementById('search_folder').addEventListener('input', function() {
        let keyword = this.value.toLowerCase();
        document.querySelectorAll('.folder-card, .folder-card-list').forEach(item => {
            let name = item.getAttribute('data-name');
            item.style.display = name.includes(keyword) ? "" : "none";
        });
    });

    // View Switcher Logic
    function switchView(type) {
        const grid = document.getElementById('view_grid');
        const list = document.getElementById('view_list');
        const bGrid = document.getElementById('btn_grid');
        const bList = document.getElementById('btn_list');

        if (type === 'grid') {
            grid.classList.remove('hidden');
            list.classList.add('hidden');
            bGrid.classList.replace('btn-ghost', 'btn-primary');
            bList.classList.replace('btn-primary', 'btn-ghost');
        } else {
            grid.classList.add('hidden');
            list.classList.remove('hidden');
            bList.classList.replace('btn-ghost', 'btn-primary');
            bGrid.classList.replace('btn-primary', 'btn-ghost');
        }
    }

    // CRUD Logic
    function tambahFolder() {
        $('#form_folder')[0].reset();
        $('#id_berkas_umum').val('');
        $('#modal_title').text('Buat Folder Baru');
        modal_folder.showModal();
    }

    function editFolder(id) {
        $.getJSON("<?= base_url('berkas_umum/get_edit/') ?>" + id, function(data) {
            $('#id_berkas_umum').val(data.id_berkas_umum);
            $('#nama_berkas_umum').val(data.nama_berkas_umum);
            $('#penyimpanan_rak').val(data.penyimpanan_rak);
            $('#keterangan').val(data.keterangan);
            $('#modal_title').text('Update Folder');
            modal_folder.showModal();
        });
    }

    $('#form_folder').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= base_url('berkas_umum/simpan') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function() {
                Swal.fire('Berhasil!', 'Data folder telah disimpan.', 'success').then(() => location.reload());
            }
        });
    });

    function hapusFolder(id) {
        Swal.fire({
            title: 'Hapus Folder?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('berkas_umum/hapus/') ?>" + id;
            }
        });
    }
</script>