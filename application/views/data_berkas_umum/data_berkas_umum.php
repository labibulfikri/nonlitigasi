<div class="p-8 bg-slate-50 min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-slate-800">Berkas Umum</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Manajemen Folder Dokumen Utama</p>
        </div>
        <div id="csrf-holder">
            <?= crsf_ajax() ?>
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
                    <!-- <button onclick="shareLink('${sumber}', '${id}')" class="btn btn-sm btn-primary px-6 rounded-xl font-black italic uppercase shadow-lg shadow-primary/20">
                        <i class="mdi mdi-share-variant mr-1"></i>
                    </button> -->
                    <button onclick="copyShareLink(<?= $b->id_berkas_umum ?>)" class="btn btn-circle btn-xs btn-ghost text-indigo-500 hover:bg-indigo-50" title="Bagikan Folder">
                        <i class="mdi mdi-share-variant"></i>
                    </button>
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
        <h3 id="modal_title" class="font-black text-2xl uppercase italic text-indigo-600 mb-8">Data Master Folder</h3>
        <form id="form_folder">
            <input type="hidden" name="id_berkas_umum" id="id_berkas_umum">
            <div class="space-y-5">

                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">Judul Folder</span></label>
                    <input type="text" name="nama_berkas_umum" id="nama_berkas_umum" class="input input-bordered w-full rounded-2xl font-bold bg-slate-50 border-none" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">Penyimpanan Rak</span></label>
                        <input type="text" name="penyimpanan_rak" id="penyimpanan_rak" list="data_rak" class="input input-bordered w-full rounded-2xl font-bold bg-slate-50 border-none" placeholder="Pilih atau Ketik...">
                        <datalist id="data_rak">
                            <?php foreach ($list_rak as $rk): ?>
                                <option value="<?= $rk->penyimpanan_rak ?>">
                                <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">PIC Terkait</span></label>
                        <select name="pic" id="pic_folder" class="select select-bordered w-full rounded-2xl font-bold bg-slate-50 border-none">
                            <option value="">Pilih PIC</option> <?php foreach ($master_pic as $p): ?>
                                <option value="<?= $p->nama_pic ?>"><?= strtoupper($p->nama_pic) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase text-slate-400">Keterangan</span></label>
                    <textarea name="keterangan" id="keterangan" class="textarea textarea-bordered rounded-2xl h-24 bg-slate-50 border-none" placeholder="Opsional..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary btn-block rounded-2xl font-black uppercase italic shadow-lg shadow-indigo-100">Simpan Perubahan</button>
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

            setTimeout(function() {
                $('#pic_folder').val(data.pic).change();
            }, 100);

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

<script>
    function copyShareLink(id) {
        $.get("<?= base_url('berkas_umum/generate_share_folder/') ?>" + id, function(link) {
            Swal.fire({
                title: 'BAGIKAN FOLDER',
                html: `
                <div class="mt-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 text-left">Link Akses Publik</p>
                    <div class="flex gap-2">
                        <input type="text" id="linkInput" class="input input-bordered w-full rounded-xl bg-slate-50 border-none font-medium text-xs" value="${link}" readonly>
                        <button onclick="copyToClipboard()" class="btn btn-primary rounded-xl px-4">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                    </div>
                    <p class="text-[9px] text-amber-500 font-bold uppercase mt-3 italic">* Siapa pun yang memiliki link ini dapat melihat isi folder.</p>
                </div>
            `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-[2.5rem]'
                }
            });
        });
    }

    function copyToClipboard() {
        const copyText = document.getElementById("linkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // Untuk mobile

        navigator.clipboard.writeText(copyText.value).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Tersalin!',
                text: 'Link berhasil dicopy ke clipboard',
                timer: 1500,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        });
    }
</script>
<!-- <script>
    function updateTokenGlobal(newToken) {
        if (newToken) {
            $('#token').val(newToken);
            $('input[name="token"]').val(newToken);
            console.log("CSRF Token Synchronized");
        }
    }

    function shareLink(sumber, id) {
        // Ambil token dari input hidden id="token"
        const currentToken = $('#token').val();

        $.ajax({
            url: '<?= base_url("arsip/generate_share_link") ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                sumber: sumber,
                id_data: id,
                durasi: 24, // Misal default 24 jam
                token: currentToken
            },
            success: function(res) {
                // PENTING: Update token di seluruh halaman
                if (res.new_token) {
                    updateTokenGlobal(res.new_token);
                }

                Swal.fire({
                    theme: 'auto',
                    title: 'LINK BERHASIL DIBUAT!',
                    html: `
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 mt-4">
                        <input type="text" id="pubUrl" class="input input-bordered w-full text-xs" value="${res.url}" readonly>
                        <button onclick="copyToClipboard()" class="btn btn-sm btn-primary w-full mt-2 font-black italic">SALIN LINK</button>
                        <p class="text-[9px] mt-3 text-error font-black italic uppercase">Berlaku sampai: ${res.expired}</p>
                    </div>`,
                    showConfirmButton: false,
                    showCloseButton: true,
                    target: document.getElementById('modal_detail')
                });
            },
            error: function(xhr) {
                // Jika error 403 muncul lagi, paksa reload agar token sinkron
                Swal.fire({
                    theme: 'auto',
                    title: 'Sesi Keamanan Habis',
                    text: 'Halaman akan dimuat ulang untuk sinkronisasi token.',
                    icon: 'warning'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }
</script> -->