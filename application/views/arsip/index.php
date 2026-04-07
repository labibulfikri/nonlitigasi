<<<<<<< HEAD
<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-primary italic tracking-tighter uppercase">Penyimpanan Berkas</h1>
            <div class="flex items-center gap-2">
                <span class="badge badge-xs badge-primary"></span>
                <p class="text-[10px] opacity-60 font-bold uppercase tracking-widest text-base-content">Arsip Terpusat • Database: Nonlit & Perkara</p>
            </div>
        </div>

        <div id="csrf-holder">
            <?= crsf_ajax() ?>
        </div>
        <button onclick="modal_berkas_umum.showModal()" class="btn btn-sm btn-primary px-5 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            DIGITALISASI UMUM
        </button>
        <form action="<?= base_url('arsip/index') ?>" method="get" class="join shadow-sm">
            <input type="text" name="search"
                class="input input-bordered join-item w-full md:w-64 focus:outline-primary"
                placeholder="Cari nomor/pihak..."
                value="<?= $this->input->get('search') ?>">
            <button type="submit" class="btn btn-primary join-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success shadow-lg mb-6 border-none text-white font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= $this->session->flashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-300">
                    <tr class="text-base-content uppercase text-[11px] tracking-wider">
                        <th class="w-20">Sumber</th>
                        <th>Nomor Register / Perkara</th>
                        <th>Nama Pihak</th>
                        <th class="w-48 text-center">Lokasi Simpan</th>
                        <th class="text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($arsip)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-20">
                                <div class="flex flex-col items-center opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span class="font-black italic">Data tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($arsip as $row): ?>
                        <tr class="hover">
                            <td>
                                <div class="badge badge-sm font-black border-none <?= $row->sumber == 'NONLIT' ? 'bg-blue-500 text-white' : 'bg-orange-500 text-white' ?>">
                                    <?= $row->sumber ?>
                                </div>
                            </td>
                            <td class="font-bold text-xs font-mono text-primary">
                                <?= $row->nomor ?>
                            </td>
                            <td>
                                <div class="text-sm font-bold uppercase"><?= $row->nama_pihak ?></div>
                                <div class="text-[10px] opacity-40 truncate w-64 uppercase tracking-tighter">
                                    <?= $row->lokasi ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($row->id_rak): ?>
                                    <div class="badge badge-outline border-primary text-primary font-black py-3 px-4 gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        RAK: <?= $row->id_rak ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] italic opacity-30 text-error font-bold tracking-widest">BELUM DIARSIPKAN</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <button class="btn btn-square btn-ghost btn-sm text-info tooltip" data-tip="Lihat Detail & Berkas"
                                    onclick="viewDetail('<?= $row->sumber ?>', '<?= $row->id_data ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>

                                <?php if ($row->sumber === 'UMUM'): ?>
                                    <button onclick="editUmum(<?= $row->id_data ?>)" class="btn btn-xs btn-warning btn-square">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                                <!-- <button class="btn btn-square btn-ghost btn-sm text-primary hover:bg-primary hover:text-white transition-all"
                                    onclick="prepUpdate('<?= $row->sumber ?>', '<?= $row->id_data ?>', '<?= $row->id_rak ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 flex flex-col items-center gap-2">
        <span class="text-[10px] font-black opacity-30 tracking-[0.3em] uppercase">Halaman Navigasi</span>
        <?= $pagination ?>
    </div>
</div>

<dialog id="modal_edit_umum" class="modal">
    <div class="modal-box w-11/12 max-w-lg rounded-2xl border-t-8 border-warning p-0 shadow-2xl">
        <div class="p-5 border-b border-base-200 bg-slate-50 rounded-t-2xl">
            <h3 class="font-black text-xs uppercase tracking-[0.2em] text-slate-500">Koreksi Data Berkas Umum</h3>
        </div>

        <form action="<?= base_url('arsip_umum/update_proses') ?>" method="post" class="p-6 space-y-4">
            <input type="hidden" name="id_berkas_umum" id="edit_id">

            <div class="form-control">
                <label class="label"><span class="label-text text-[10px] font-black uppercase opacity-50">Nama Dokumen</span></label>
                <input type="text" name="nama_berkas_umum" id="edit_nama" class="input input-bordered input-sm rounded-lg font-bold text-black uppercase" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-[10px] font-black uppercase opacity-50">Lokasi Rak</span>
                    </label>
                    <input type="text" name="penyimpanan_rak" id="edit_rak" list="data_rak_edit" class="input input-bordered input-sm rounded-lg font-bold text-black uppercase">

                    <datalist id="data_rak_edit">
                        <?php foreach ($saran_rak as $r): ?>
                            <option value="<?= $r->penyimpanan_rak ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase opacity-50">PIC Bertanggung Jawab</span></label>
                    <select name="pic" id="edit_pic" class="select select-bordered select-sm rounded-lg font-black text-[11px] uppercase">
                        <?php foreach ($pic_list as $p): ?>
                            <option value="<?= $p->pic ?>"><?= $p->pic ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text text-[10px] font-black uppercase opacity-50">Keterangan Tambahan</span></label>
                <textarea name="keterangan" id="edit_keterangan" class="textarea textarea-bordered rounded-lg text-xs font-medium text-slate-600" rows="3"></textarea>
            </div>

            <div class="modal-action bg-slate-50 p-4 rounded-b-2xl border-t border-base-200">
                <button type="submit" class="btn btn-warning btn-sm px-8 rounded-lg font-black uppercase italic tracking-tighter text-white">Update Data</button>
                <button type="button" onclick="modal_edit_umum.close()" class="btn btn-ghost btn-sm px-6 font-bold uppercase text-[10px]">Batal</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="modal_detail" class="modal">
    <div class="modal-box w-11/12 max-w-3xl border-t-4 border-info">
        <h3 class="font-black text-xl italic" id="detail_title">DETAIL BERKAS</h3>
        <hr class="my-4 opacity-10">

        <div id="detail_content" class="space-y-4">
        </div>

        <div class="mt-6">
            <h4 class="font-bold text-sm mb-2 uppercase opacity-50 tracking-widest">Daftar Lampiran Berkas</h4>
            <div id="list_lampiran" class="grid grid-cols-1 md:grid-cols-2 gap-2">
            </div>
        </div>

        <div class="modal-action">
            <button onclick="modal_detail.close()" class="btn btn-ghost">Tutup</button>
        </div>
    </div>
</dialog>


<dialog id="modal_rak" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box border-t-4 border-primary shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-3 bg-primary/10 rounded-xl text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div>
                <h3 class="font-black text-xl italic tracking-tight">Pindahkan Berkas</h3>
                <p class="text-[10px] font-bold opacity-50 uppercase">Update Lokasi Penyimpanan Fisik</p>
            </div>
        </div>

        <form action="<?= base_url('arsip/update_rak') ?>" method="post">
            <input type="hidden" name="sumber" id="m_sumber">
            <input type="hidden" name="id_data" id="m_id">

            <div class="form-control w-full mt-6">
                <label class="label">
                    <span class="label-text font-black text-xs uppercase tracking-wider">Pilih Lokasi Rak Baru</span>
                </label>
                <select name="id_rak" id="m_rak" class="select select-bordered w-full select-primary font-bold">
                    <option value="">-- Kosongkan Rak --</option>
                    <?php foreach ($list_rak as $r): ?>
                        <option value="<?= $r->id_rak ?>"><?= $r->kode_rak ?> - <?= $r->nama_rak ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="label mt-1">
                    <span class="label-text-alt italic opacity-50 text-[10px]">Pastikan berkas fisik sudah dipindahkan ke rak tujuan.</span>
                </label>
            </div>

            <div class="modal-action gap-2 mt-8">
                <button type="submit" class="btn btn-primary px-8 shadow-lg shadow-primary/30 font-black italic">Simpan Perubahan</button>
                <button type="button" onclick="modal_rak.close()" class="btn btn-ghost font-bold opacity-50 uppercase text-xs">Batal</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>


<dialog id="modal_berkas_umum" class="modal">
    <div class="modal-box w-11/12 max-w-2xl rounded-lg border-t-8 border-primary p-0 shadow-2xl">
        <div class="p-5 border-b border-base-200 flex justify-between items-center bg-base-100">
            <div>
                <h3 class="font-black text-sm uppercase tracking-widest text-slate-700">Digitalisasi Berkas Umum</h3>
                <p class="text-[9px] opacity-50 uppercase font-bold tracking-tighter mt-1 text-primary italic">Penyimpanan Mandiri Non-Perkara</p>
            </div>
            <button onclick="modal_berkas_umum.close()" class="btn btn-xs btn-circle btn-ghost">✕</button>
        </div>

        <form action="<?= base_url('arsip_umum/simpan') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-5 bg-white">

            <div class="form-control">
                <label class="label">
                    <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Nama Berkas / Judul Dokumen</span>
                </label>
                <input type="text" name="nama_berkas_umum" class="input input-bordered input-sm rounded font-bold uppercase focus:input-primary" placeholder="Contoh: SERTIFIKAT TANAH ASET JALAN TUNJUNGAN" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Lokasi Penyimpanan (RAK)</span>
                    </label>
                    <input type="text" name="penyimpanan_rak" list="data_rak_lama" class="input input-bordered input-sm rounded font-bold text-xs uppercase focus:input-primary" placeholder="Ketik atau pilih rak..." required>

                    <datalist id="data_rak_lama">
                        <?php if (!empty($saran_rak)): ?>
                            <?php foreach ($saran_rak as $rk): ?>
                                <option value="<?= $rk->penyimpanan_rak ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </datalist>
                    <p class="text-[8px] mt-1 opacity-40 italic">*Bisa ketik manual sesuai format data lama.</p>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Penanggung Jawab (PIC)</span>
                    </label>
                    <select name="pic" class="select select-bordered select-sm rounded font-bold text-xs focus:select-primary">
                        <option value="" disabled selected>Pilih PIC...</option>
                        <?php foreach ($pic_list as $p): ?>
                            <option value="<?= $p->pic ?>"><?= $p->pic ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" name="pic" class="input input-bordered input-sm rounded font-bold text-xs" placeholder="Nama petugas pengelola..." required> -->
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Keterangan Tambahan</span>
                </label>
                <textarea name="keterangan" class="textarea textarea-bordered rounded text-xs leading-relaxed" rows="2" placeholder="Catatan mengenai isi atau kondisi fisik berkas..."></textarea>
            </div>

            <!-- <div class="form-control bg-slate-50 p-4 rounded-xl border-2 border-dashed border-slate-200">
                <label class="label pt-0">
                    <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest flex items-center gap-2 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Upload Scan Digital (Multiple)
                    </span>
                </label>
                <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm file-input-primary w-full rounded-lg" accept=".pdf,.jpg,.jpeg,.png">
                <p class="text-[9px] mt-2 italic opacity-40">*Pilih satu atau beberapa file (PDF/Gambar) sekaligus.</p>
            </div> -->

            <div class="modal-action border-t border-base-200 pt-5 mt-4">
                <button type="submit" class="btn btn-primary btn-sm px-10 rounded-md font-black italic uppercase tracking-tighter shadow-md">
                    Simpan Digitalisasi
                </button>
                <button type="button" onclick="modal_berkas_umum.close()" class="btn btn-ghost btn-sm px-6 rounded-md text-[10px] font-bold uppercase">
                    Batal
                </button>
            </div>
        </form>
    </div>
</dialog>


<script>
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
</script>
<script>
    /**
     * Menyiapkan data ke dalam modal sebelum dibuka
     * @param {string} sumber - 'NONLIT' atau 'ASING'
     * @param {string} id - ID data dari masing-masing tabel
     * @param {string} rak - ID Rak saat ini (untuk auto-select)
     */
    function prepUpdate(sumber, id, rak) {
        document.getElementById('m_sumber').value = sumber;
        document.getElementById('m_id').value = id;
        document.getElementById('m_rak').value = rak;

        // Tampilkan modal (DaisyUI menggunakan modal_id.showModal())
        modal_rak.showModal();
    }
</script>
<script>
    // 1. Fungsi Update Token Global agar sinkron dengan Helper Manual
    function updateTokenGlobal(newToken) {
        if (newToken) {
            $('#token').val(newToken);
            $('input[name="token"]').val(newToken);
            console.log("CSRF Token Synchronized");
        }
    }

    // 2. Fungsi Utama View Detail
    function viewDetail(sumber, id) {
        const currentToken = $('#token').val();

        // Reset Modal & Show Loading
        document.getElementById('detail_content').innerHTML = `
            <div class="flex justify-center p-10">
                <span class="loading loading-dots loading-lg text-primary"></span>
            </div>`;
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id,
                token: currentToken
            },
            dataType: 'JSON',
            success: function(res) {
                // Update Token untuk aksi berikutnya
                updateTokenGlobal(res.new_token);

                if (!res.data) {
                    document.getElementById('detail_content').innerHTML = '<div class="alert alert-error font-bold uppercase text-xs">Data Tidak Ditemukan</div>';
                    return;
                }

                let html = "";
                let listDet = res.detail_tambahan; // Data dari t_perkara_detail atau nonlit_det

                // --- LOGIKA TAMPILAN PER SUMBER ---

                if (sumber === 'ASING') {
                    html = `
                    <div class="stats shadow w-full mb-4 bg-orange-50 border border-orange-200">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-black uppercase text-orange-600">No. Perkara</div>
                            <div class="stat-value text-lg text-orange-700 font-black">${res.data.perkara_no || '-'}</div>
                            <div class="stat-desc font-bold text-orange-500 uppercase text-[9px]">Status: ${res.data.perkara_status || '-'}</div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black opacity-60 uppercase tracking-widest border-l-4 border-orange-500 pl-2">Riwayat Tingkat & Amar Putusan</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
                            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl mb-2">
                                <input type="radio" name="acc-asing" ${index === 0 ? 'checked' : ''} /> 
                                <div class="collapse-title p-4 flex justify-between items-center">
                                    <span class="text-[11px] font-black uppercase text-slate-700">${det.perkaradet_tingkat || 'TINGKAT'} - ${det.perkaradet_status || 'STATUS'}</span>
                                    <span class="badge badge-sm font-mono text-[9px]">${det.perkaradet_tgl_putusan || '-'}</span>
                                </div>
                                <div class="collapse-content bg-orange-50/20 border-t border-slate-50 pt-4">
                                    <p class="text-[9px] font-black opacity-40 uppercase mb-1 text-orange-600">Amar Putusan:</p>
                                    <p class="text-[11px] leading-relaxed text-slate-700 italic font-medium">"${det.perkaradet_keterangan || 'Tidak ada amar putusan.'}"</p>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-30 uppercase">Belum ada riwayat putusan</p>`;
                    }
                    html += `</div>`;

                } else if (sumber === 'NONLIT') {
                    html = `
                    <div class="stats shadow w-full mb-4 bg-blue-50 border border-blue-200">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-black uppercase text-blue-600">No. Register</div>
                            <div class="stat-value text-lg text-blue-800 font-black">${res.data.register_baru || '-'}</div>
                            <div class="stat-desc font-black uppercase text-blue-500 truncate text-[9px]">${res.data.permohonan_nonlit}</div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black opacity-60 uppercase tracking-widest border-l-4 border-blue-500 pl-2">Resume Notulensi Rapat</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det) => {
                            html += `
                            <div class="collapse collapse-arrow bg-white border border-slate-200 shadow-sm rounded-xl mb-2">
                                <input type="checkbox" /> 
                                <div class="collapse-title p-4 flex justify-between items-center">
                                    <span class="text-[11px] font-black uppercase text-blue-700">${det.judul_rapat || 'RAPAT KOORDINASI'}</span>
                                    <span class="text-[9px] font-bold opacity-40 font-mono">${det.tgl_rapat || '-'}</span>
                                </div>
                                <div class="collapse-content bg-blue-50/20 border-t border-slate-100 pt-4">
                                    <div class="mb-3">
                                        <p class="text-[9px] font-black opacity-40 uppercase mb-1 text-blue-600">Resume / Kesimpulan:</p>
                                        <p class="text-[11px] leading-snug text-slate-700 italic font-medium">"${det.kesimpulan || 'Belum ada resume.'}"</p>
                                    </div>
                                    <div class="flex justify-end pt-2 border-t border-blue-100">
                                        <a href="<?= base_url('assets/berkas_nonlit/') ?>${det.berkas}" target="_blank" 
                                           class="btn btn-xs btn-primary gap-1 ${det.berkas ? '' : 'btn-disabled'} uppercase font-black italic">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download Resume
                                        </a>
                                    </div>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-30 uppercase">Belum ada riwayat rapat</p>`;
                    }
                    html += `</div>`;

                } else if (sumber === 'UMUM') {
                    html = `
                    <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
                        <div class="stat p-5">
                            <div class="stat-title text-[10px] font-black uppercase text-primary">Nama Dokumen Digital</div>
                            <div class="stat-value text-lg text-slate-800 uppercase font-black leading-tight">${res.data.nama_berkas_umum}</div>
                            <div class="stat-desc mt-2 flex items-center gap-4">
                                <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                                <span class="text-[10px] font-bold opacity-50 uppercase">PIC: ${res.data.pic || '-'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6">
                        <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
                            <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
                            <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white" id="input_append" required>
                            <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 uppercase font-black italic text-white shadow-lg">Upload</button>
                        </form>
                    </div>`;


                }
                html += `
        <div class="mt-8 p-5 bg-slate-50 rounded-2xl border border-dashed border-slate-300 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h5 class="text-[11px] font-black uppercase text-slate-500 tracking-widest flex items-center gap-2">
                    <i class="mdi mdi-share-variant text-primary text-lg"></i> Akses Berbagi Dokumen
                </h5>
                <p class="text-[9px] opacity-60 font-bold uppercase italic">Buat link akses publik tanpa perlu login</p>
            </div>
            <button onclick="shareLink('${sumber}', '${id}')" class="btn btn-sm btn-primary px-6 rounded-xl font-black italic uppercase shadow-lg shadow-primary/20">
                Bagikan Berkas
            </button>
        </div>
    `;
                document.getElementById('detail_content').innerHTML = html;

                // --- RENDER LAMPIRAN DIGITAL ---
                let fileHtml = "";
                if (res.lampiran && res.lampiran.length > 0) {
                    res.lampiran.forEach(file => {
                        let fileName = file.nama_file || file.nama_berkas || file.berkas_laporan || file.name_berkas;
                        let filePath = (sumber === 'UMUM') ? 'assets/berkas_umum/' : (sumber === 'NONLIT' ? 'assets/berkas_nonlit/' : 'assets/upload/');
                        // Tombol hapus hanya muncul jika sumbernya UMUM
                        let btnDelete = (sumber === 'UMUM') ? `
        <button onclick="deleteFile('${file.id_berkas_umum_det}', '${res.data.id_berkas_umum}')" class="btn btn-xs btn-error btn-circle text-white shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        </button>` : '';
                        fileHtml += `
                        <div class="flex items-center p-3 bg-white rounded-xl border border-slate-200 gap-3 mb-2 hover:border-primary transition-all group">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black truncate uppercase text-slate-600">${fileName}</p>
                            </div>
                            <a href="<?= base_url() ?>${filePath}${fileName}" target="_blank" class="btn btn-xs btn-primary btn-circle text-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            </a>
                            ${btnDelete}
                        </div>`;
                    });
                } else {
                    fileHtml = '<div class="py-10 flex flex-col items-center opacity-20"><p class="text-[10px] font-black uppercase italic tracking-widest text-slate-500">Tidak ada berkas digital</p></div>';
                }
                document.getElementById('list_lampiran').innerHTML = fileHtml;
            }
        });
    }

    // 3. Fungsi Upload & Refresh Total
    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);
        let tokenVal = $('#token').val();

        formData.append('token', tokenVal);
        btn.addClass('loading').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                Swal.fire({
                    theme: 'auto',
                    title: 'BERHASIL!',
                    text: 'Berkas berhasil diunggah. Halaman akan dimuat ulang.',
                    icon: 'success',
                    confirmButtonText: 'OKE',
                    target: document.getElementById('modal_detail'),
                    customClass: {
                        confirmButton: 'btn btn-primary px-10'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function() {
                Swal.fire({
                    title: 'ERROR',
                    text: 'Token Habis. Refresh halaman.',
                    icon: 'error'
                }).then(() => location.reload());
            }
        });
    }
</script>

<script>
    function deleteFile(id_det, id_utama) {
        const tokenVal = $('#token').val();

        Swal.fire({
            theme: 'auto',
            title: 'HAPUS BERKAS?',
            text: "File akan dihapus permanen dari server!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS!',
            target: document.getElementById('modal_detail')
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("arsip_umum/delete_file") ?>',
                    type: 'POST',
                    data: {
                        id_det: id_det,
                        token: tokenVal
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        updateTokenGlobal(res.new_token); // Update token setelah hapus
                        if (res.status) {
                            Swal.fire({
                                theme: 'auto',
                                title: 'TERHAPUS!',
                                icon: 'success',
                                timer: 1000,
                                showConfirmButton: false,
                                target: document.getElementById('modal_detail')
                            });
                            // Refresh isi modal tanpa reload halaman
                            viewDetail('UMUM', id_utama);
                        }
                    },
                    error: function() {
                        location.reload(); // Jika error/token habis, paksa reload
                    }
                });
            }
        });
    }
</script>
<!-- <script>
    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);

        // Ambil ID dari input hidden yang ada di form
        let id_data = $('input[name="id_berkas_umum"]').val();

        // Efek loading
        btn.addClass('loading').prop('disabled', true).text('Sedang Mengunggah...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    // PENTING: Panggil ulang fungsi viewDetail agar modal me-refresh isinya
                    // Parameter 'UMUM' dan id_data harus sesuai dengan yang dikirim dari controller
                    viewDetail('UMUM', res.id_data);

                    // Reset input file agar bersih kembali
                    $('#input_append').val('');

                    // Opsional: Beri notifikasi kecil
                    console.log('Upload Berhasil, refreshing detail...');
                } else {
                    alert('Gagal: ' + res.message);
                }
            },
            error: function(err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi saat upload.');
            },
            success: function(res) {
                if (res.status) {
                    // res.id_data diambil dari echo json_encode di Controller tadi
                    // Pastikan 'UMUM' ditulis huruf besar sesuai logika di get_detail_berkas
                    viewDetail('UMUM', res.id_data);

                    // Bersihkan input file setelah upload
                    $('input[name="files[]"]').val('');
                    alert('Berkas berhasil ditambahkan!');
                }
            },
            complete: function() {
                btn.removeClass('loading').prop('disabled', false).text('Upload Sekarang');
            }
        });
    }
</script> -->
<!-- <script>
    function viewDetail(sumber, id) {
        document.getElementById('detail_content').innerHTML = '<div class="p-10 text-center"><span class="loading loading-spinner text-primary"></span></div>';
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id
            },
            dataType: 'JSON',
            success: function(res) {
                let html = "";
                let listDet = res.detail_tambahan; // Array data detail

                if (sumber === 'ASING') {
                    // Header Informasi Utama (Stats)
                    html = `
    <div class="stats shadow w-full mb-6 bg-orange-50 border border-orange-200">
        <div class="stat">
            <div class="stat-title text-[10px] font-bold uppercase tracking-widest text-orange-600/60">Nomor Perkara Utama</div>
            <div class="stat-value text-lg text-orange-700">${res.data.perkara_no}</div>
            <div class="stat-desc font-bold text-orange-500 italic">Tgl: ${res.data.perkara_tgl || '-'}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-white p-4 rounded-2xl border border-base-200 shadow-sm">
        <div class="col-span-1 md:col-span-2 pb-2 border-b border-dashed border-base-200">
            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1">Para Pihak (Utama)</p>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-bold"><span class="text-primary">P:</span> ${res.data.perkara_penggugat || '-'}</p>
                <p class="text-xs font-bold"><span class="text-error">T:</span> ${res.data.perkara_tergugat || '-'}</p>
            </div>
        </div>
        <div class="col-span-1 md:col-span-2">
            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1">Objek Perkara</p>
            <p class="text-xs italic leading-relaxed text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-100">
                ${res.data.perkara_alamat || 'Data objek perkara tidak tersedia.'}
            </p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex justify-between items-center px-1">
            <h4 class="text-xs font-black opacity-60 uppercase tracking-widest border-l-4 border-orange-500 pl-2">Riwayat Putusan / Tingkat</h4>
            <span class="badge badge-sm badge-outline font-mono opacity-50">${listDet ? listDet.length : 0} Tingkat</span>
        </div>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl overflow-hidden hover:border-orange-300 transition-all">
                <input type="radio" name="my-accordion-asing" ${index === 0 ? 'checked' : ''} /> 
                <div class="collapse-title p-4">
                    <div class="flex justify-between items-center w-[95%]">
                        <span class="text-sm font-black uppercase text-slate-700">
                            ${det.perkaradet_tingkat} - ${det.perkaradet_status} | <span class="text-primary">${det.perkaradet_no || 'No. Belum Input'}</span>
                        </span>
                        <div class="badge badge-sm badge-ghost font-bold font-mono">${det.perkaradet_tgl_putusan || '-'}</div>
                    </div>
                </div>
                <div class="collapse-content bg-orange-50/30 border-t border-base-100 pt-4">
                    <div class="space-y-3 py-2">
                        <div>
                            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1 text-orange-600">Pihak Terkait (Level Ini):</p>
                            <p class="text-xs font-bold text-slate-800 leading-tight">${det.perkaradet_pihak || '-'}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1 text-orange-600">Amar Putusan:</p>
                            <p class="text-[11px] leading-relaxed italic text-slate-600 whitespace-pre-line">${det.perkaradet_keterangan || 'Tidak ada keterangan tambahan.'}</p>
                        </div>
                        <div class="flex justify-end pt-2 border-t border-orange-100">
                            <span class="text-[8px] font-bold opacity-30 italic uppercase">Input: ${det.perkaradet_created_date || '-'}</span>
                        </div>
                    </div>
                </div>
            </div>`;
                        });
                    } else {
                        html += `
        <div class="flex flex-col items-center justify-center p-10 bg-base-100 rounded-2xl border-2 border-dashed border-base-300 opacity-30">
            <p class="text-xs font-bold italic uppercase tracking-widest text-center">Belum ada riwayat putusan berjenjang.</p>
        </div>`;
                    }
                    html += `</div>`;

                } else if (sumber === 'UMUM') {
                    html = `
    <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
        <div class="stat p-5">
            <div class="stat-title text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 text-primary">Nama Dokumen Digital</div>
            <div class="stat-value text-lg text-slate-800 leading-tight uppercase font-black">${res.data.nama_berkas_umum}</div>
            <div class="stat-desc mt-2 flex items-center gap-4">
                <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                <span class="text-[10px] font-bold opacity-50 uppercase tracking-tighter">PIC: ${res.data.pic || '-'}</span>
            </div>
        </div>
    </div>

    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6 group transition-all hover:border-blue-400">
        <div class="flex items-center justify-between mb-3">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah File Baru ke Berkas Ini
            </p>
            <span class="text-[9px] font-bold text-blue-400 italic italic uppercase">Max 10MB (PDF/JPG)</span>
        </div>
        <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
            <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
             
            <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white rounded-lg font-bold" id="input_append" required>
            <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 shadow-lg shadow-blue-200 uppercase font-black italic tracking-tighter">
                Upload Sekarang
            </button>
        </form>
    </div>

    <div class="space-y-3">
        <div class="flex items-center gap-3 px-1">
             <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Lampiran Digital Tersimpan</h4>
             <div class="h-[1px] flex-1 bg-slate-100"></div>
        </div>`;
                } else {
                    // Tampilan NONLIT (Timeline Rapat)
                    // Tampilan NONLIT dengan Dropdown/Accordion untuk setiap Rapat
                    html = `
    <div class="stats border border-base-300 w-full mb-6 rounded-lg bg-base-100">
                    <div class="stat">
                        <div class="stat-title text-[10px] font-bold uppercase tracking-wider">No. Register</div>
                        <div class="stat-value text-xl text-info">${res.data.register_baru}</div>
                        <div class="stat-desc font-bold uppercase truncate">${res.data.permohonan_nonlit}</div>
                    </div>
                    <div class="stat border-l border-base-300">
                        <div class="stat-title text-[10px] font-bold uppercase tracking-wider">PIC / Status</div>
                        <div class="stat-value text-sm font-black uppercase">${res.data.pic || '-'}</div>
                        <div class="stat-desc"><span class="badge badge-info badge-outline badge-xs font-bold">${res.data.status || 'AKTIF'}</span></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 mb-2">Notulensi Rapat</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl overflow-hidden">
                <input type="checkbox" name="accordion-nonlit" /> 
                
                <div class="collapse-title p-4 flex flex-col gap-1">
                    <div class="flex justify-between items-center w-[95%]">
                        <span class="text-[11px] font-black uppercase text-blue-600 tracking-tight">
                            ${det.judul_rapat || 'Rapat Tanpa Judul'}
                        </span>
                        <span class="text-[10px] font-bold opacity-40 font-mono italic">${det.tgl_rapat || '-'}</span>
                    </div>
                </div>

                <div class="collapse-content bg-slate-50 border-t border-base-200 pt-4">
                    <div class="space-y-4 py-2">
                                   
                        
                        <div class="p-3 bg-blue-100 rounded-xl border border-blue-200 relative overflow-hidden">
                            <div class="absolute -right-2 -top-2 opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-[9px] font-black text-blue-500 uppercase mb-1 tracking-widest">Kesimpulan Akhir:</p>
                            <p class="text-xs font-bold text-blue-900 leading-snug">${det.kesimpulan || 'Belum ada kesimpulan.'}</p>
                        </div>
                        
                        <div class="flex justify-end pt-2">
                            <span class="text-[8px] font-bold opacity-30 italic uppercase">Diupdate oleh: ${det.updated_by || 'System'}</span>
                        </div> 
                        <a href="<?= base_url('assets/berkas_nonlit/') ?>${det.berkas || ''}" target="_blank" class="btn btn-xs btn-primary ${det.berkas ? '' : 'btn-disabled'}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            <span class="ml-1 text-[10px]">Unduh Berkas Rapat</span>
                        </a>
                    </div>
                </div>
            </div>`;
                        });
                    } else {
                        html += `
        <div class="flex flex-col items-center justify-center p-10 bg-base-100 rounded-2xl border-2 border-dashed border-base-300 opacity-30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            <p class="text-xs font-bold italic">Belum ada riwayat rapat untuk nomor ini.</p>
        </div>`;
                    }
                    html += `</div>`;
                }
                document.getElementById('detail_content').innerHTML = html;

                // Render Lampiran (Tetap)
                // Render Lampiran

                if (res.lampiran && res.lampiran.length > 0) {
                    res.lampiran.forEach(file => {
                        let fileName = "";
                        let fileUrl = "";

                        if (sumber === 'ASING') {
                            // Konfigurasi untuk ASING
                            fileName = file.name_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/asing/assets/upload/${fileName}`;
                        } else if (sumber === 'NONLIT') {
                            // Konfigurasi untuk UMUM
                            fileName = file.nama_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/nonlitigasi/assets/berkas_lampiran/${fileName}`;
                        } else if (sumber === 'POLISI') {
                            fileName = file.nama_file; // Sesuaikan jika nama kolom di laporan_polisi_det berbeda
                            fileUrl = `<?= base_url('assets/laporan_polisi/') ?>${fileName}`;
                        } else if (sumber === 'MASALAH') {
                            fileName = file.nama_file; // Sesuaikan jika nama kolom di masalah_det berbeda
                            fileUrl = `<?= base_url('assets/masalah/') ?>${fileName}`;
                        } // --- INI PERBAIKANNYA ---
                        else if (sumber === 'UMUM') {
                            fileName = file.nama_file; // SESUAIKAN DENGAN SCREENSHOT NETWORK ANDA
                            fileUrl = `<?= base_url('assets/berkas_umum/') ?>${fileName}`;
                        }

                        // Tentukan Icon berdasarkan tipe file (opsional tapi bagus untuk UI)
                        let isPdf = fileName.toLowerCase().endsWith('.pdf');

                        fileHtml += `
        <div class="flex items-center p-3 bg-base-100 rounded-xl border border-base-300 gap-3 hover:shadow-md hover:border-primary transition-all group">
            <div class="p-2 ${isPdf ? 'bg-error/10 text-error' : 'bg-info/10 text-info'} rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-[10px] font-black truncate uppercase tracking-tight text-slate-600">${fileName}</p>
                <p class="text-[8px] opacity-50 uppercase font-bold">${isPdf ? 'PDF Document' : 'Attachment'}</p>
            </div>
            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary btn-circle text-white shadow-sm hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </a>
        </div>`;
                    });
                } else {
                    fileHtml = `
    <div class="col-span-full py-10 flex flex-col items-center opacity-20">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="text-xs font-bold italic uppercase">Tidak ada berkas fisik</p>
    </div>`;
                }
                document.getElementById('list_lampiran').innerHTML = fileHtml;
            }
        });
    }
</script> -->
<!-- <script>
    function viewDetail(sumber, id) {
        // 1. Reset State Modal ke Loading
        document.getElementById('detail_content').innerHTML = '<div class="p-10 text-center"><span class="loading loading-spinner text-primary"></span></div>';
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id
            },
            dataType: 'JSON',
            success: function(res) {
                let html = "";
                let fileHtml = "";
                let listDet = res.detail_tambahan; // Riwayat Sidang / Rapat

                // =============================================================
                // BAGIAN 1: RENDER KONTEN DETAIL (AMAR, RESUME, INFO UTAMA)
                // =============================================================

                if (sumber === 'ASING') {
                    html = `
                    <div class="stats shadow w-full mb-6 bg-orange-50 border border-orange-200">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-black uppercase tracking-widest text-orange-600/60">Nomor Perkara Utama</div>
                            <div class="stat-value text-lg text-orange-700 font-black">${res.data.perkara_no}</div>
                            <div class="stat-desc font-bold text-orange-500 italic uppercase text-[9px]">Tgl Reg: ${res.data.perkara_tgl || '-'}</div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black opacity-60 uppercase tracking-[0.2em] border-l-4 border-orange-500 pl-2 text-slate-700">Riwayat Putusan & Amar</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
                            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl overflow-hidden mb-3">
                                <input type="radio" name="acc-asing" ${index === 0 ? 'checked' : ''} /> 
                                <div class="collapse-title p-4">
                                    <div class="flex justify-between items-center pr-6">
                                        <span class="text-[11px] font-black uppercase text-slate-700">${det.perkaradet_tingkat} - ${det.perkaradet_status}</span>
                                        <span class="badge badge-sm badge-ghost font-mono text-[9px]">${det.perkaradet_tgl_putusan || '-'}</span>
                                    </div>
                                </div>
                                <div class="collapse-content bg-orange-50/20 border-t border-slate-50 pt-4">
                                    <p class="text-[9px] font-black opacity-40 uppercase mb-1 text-orange-600">Amar Putusan:</p>
                                    <p class="text-[11px] leading-relaxed text-slate-700 italic">"${det.perkaradet_keterangan || 'Tidak ada amar putusan.'}"</p>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-40 uppercase">Belum ada riwayat putusan</p>`;
                    }
                } else if (sumber === 'NONLIT') {
                    html = `
                    <div class="stats border border-blue-200 w-full mb-6 rounded-xl bg-blue-50/30">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-bold uppercase text-blue-400">No. Register</div>
                            <div class="stat-value text-lg text-blue-800 font-black">${res.data.register_baru}</div>
                            <div class="stat-desc font-black uppercase text-blue-600 truncate">${res.data.permohonan_nonlit}</div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 mb-2 text-slate-700">Resume & Notulensi Rapat</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det) => {
                            html += `
                            <div class="p-4 bg-white border border-slate-200 rounded-xl mb-3 shadow-sm">
                                <div class="flex justify-between items-start mb-2 border-b border-slate-50 pb-2">
                                    <p class="text-[11px] font-black text-blue-600 uppercase tracking-tight">${det.judul_rapat || 'Rapat Koordinasi'}</p>
                                    <span class="text-[9px] font-bold opacity-40 uppercase font-mono">${det.tgl_rapat || '-'}</span>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Resume / Kesimpulan:</p>
                                    <p class="text-[11px] leading-snug text-slate-700 italic whitespace-pre-line">"${det.kesimpulan || 'Belum ada resume rapat.'}"</p>
                                </div>
                                <a href="<?= base_url('assets/berkas_nonlit/') ?>${det.berkas || ''}" target="_blank" class="btn btn-xs btn-primary mt-3 ${det.berkas ? '' : 'btn-disabled'}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    <span class="ml-1 text-[10px]">Unduh Notulensi</span>
                                </a>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-40 uppercase">Belum ada riwayat rapat</p>`;
                    }
                } else if (sumber === 'UMUM') {
                    html = `

                    
                    <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
                        <div class="stat p-5">
                            <div class="stat-title text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 text-primary">Nama Dokumen Digital</div>
                            <div class="stat-value text-lg text-slate-800 leading-tight uppercase font-black">${res.data.nama_berkas_umum}</div>
                            <div class="stat-desc mt-2 flex items-center gap-4">
                                <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                                <span class="text-[10px] font-bold opacity-50 uppercase tracking-tighter">PIC: ${res.data.pic || '-'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6 group transition-all hover:border-blue-400">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2 font-black italic">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Tambah File Baru ke Folder Ini
                            </p>
                        </div>
                        <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
                            <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
                            <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white rounded-lg" id="input_append" required>
                            <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 uppercase font-black italic tracking-tighter text-white">Upload</button>
                        </form>
                    </div>`;
                } else if (sumber === 'POLISI' || sumber === 'MASALAH') {
                    let judul = (sumber === 'POLISI') ? res.data.nomor_polisi : res.data.nama_masalah;
                    let sub = (sumber === 'POLISI') ? res.data.pelapor : res.data.alamat_masalah;
                    html = `
                    <div class="stats border border-slate-200 w-full mb-6 rounded-xl bg-white">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-bold uppercase opacity-40">${sumber}</div>
                            <div class="stat-value text-lg text-slate-800 font-black">${judul || '-'}</div>
                            <div class="stat-desc font-bold text-slate-500 uppercase">${sub || '-'}</div>
                        </div>
                    </div>`;
                }

                document.getElementById('detail_content').innerHTML = html;

                // =============================================================
                // BAGIAN 2: RENDER LAMPIRAN (FILE DIGITAL)
                // =============================================================

                if (res.lampiran && res.lampiran.length > 0) {
                    res.lampiran.forEach(file => {
                        let fileName = "";
                        let fileUrl = "";

                        // Mapping File Name & URL berdasarkan sumber
                        if (sumber === 'ASING') {
                            fileName = file.name_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/asing/assets/upload/${fileName}`;
                        } else if (sumber === 'NONLIT') {
                            fileName = file.nama_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/nonlitigasi/assets/berkas_lampiran/${fileName}`;
                        } else if (sumber === 'POLISI') {
                            fileName = file.nama_file;
                            fileUrl = `<?= base_url('assets/laporan_polisi/') ?>${fileName}`;
                        } else if (sumber === 'MASALAH') {
                            fileName = file.nama_file;
                            fileUrl = `<?= base_url('assets/masalah/') ?>${fileName}`;
                        } else if (sumber === 'UMUM') {
                            fileName = file.nama_file;
                            fileUrl = `<?= base_url('assets/berkas_umum/') ?>${fileName}`;
                        }

                        let isPdf = fileName ? fileName.toLowerCase().endsWith('.pdf') : false;

                        fileHtml += `
                        <div class="flex items-center p-3 bg-white rounded-xl border border-slate-200 gap-3 hover:shadow-md hover:border-primary transition-all group mb-2">
                            <div class="p-2 ${isPdf ? 'bg-error/10 text-error' : 'bg-info/10 text-info'} rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black truncate uppercase tracking-tight text-slate-600">${fileName}</p>
                                <p class="text-[8px] opacity-40 uppercase font-bold text-slate-400">${isPdf ? 'PDF Document' : 'Image/File'}</p>
                            </div>
                            <a href="${fileUrl}" target="_blank" class="btn btn-xs btn-primary btn-circle text-white shadow-sm transition-transform hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            </a>
                        </div>`;
                    });
                } else {
                    fileHtml = `
                    <div class="py-10 flex flex-col items-center opacity-20 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-[10px] font-black uppercase italic tracking-widest">Tidak ada berkas digital</p>
                    </div>`;
                }
                document.getElementById('list_lampiran').innerHTML = fileHtml;
            }
        });
    }

    // Fungsi Upload Tambahan (Hanya untuk UMUM)
    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);
        let id_data = $('input[name="id_berkas_umum"]').val();

        btn.addClass('loading').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    viewDetail('UMUM', id_data); // Refresh modal
                    $('#input_append').val(''); // Reset input
                } else {
                    alert('Gagal upload!');
                }
            },
            complete: function() {
                btn.removeClass('loading').prop('disabled', false).text('Upload Sekarang');
            }
        });
    }
</script> -->


<script>
    // Fungsi pembantu agar semua input token di halaman terupdate
    function updateTokenGlobal(newToken) {
        if (newToken) {
            $('#token').val(newToken);
            $('input[name="token"]').val(newToken);
            console.log("Token Updated");
        }
    }

    function viewDetail2(sumber, id) {
        // Ambil token dari input hidden id="token"
        const currentToken = $('#token').val();

        document.getElementById('detail_content').innerHTML = '<div class="p-10 text-center"><span class="loading loading-spinner text-primary"></span></div>';
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id,
                token: currentToken // Kirim token untuk cek_csrf
            },
            dataType: 'JSON',
            success: function(res) {
                // Update token setelah request detail berhasil
                updateTokenGlobal(res.new_token);

                if (res.status && res.data) {
                    let html = "";
                    // Tampilan UMUM
                    if (sumber === 'UMUM') {
                        html = `
                        <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
                            <div class="stat p-5">
                                <div class="stat-title text-[10px] font-black uppercase text-primary">Nama Dokumen Digital</div>
                                <div class="stat-value text-lg text-slate-800 uppercase font-black">${res.data.nama_berkas_umum}</div>
                                <div class="stat-desc mt-2 flex items-center gap-4">
                                    <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                                    <span class="text-[10px] font-bold opacity-50 uppercase tracking-tighter">PIC: ${res.data.pic || '-'}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6 group">
                            <p class="text-[10px] font-black text-blue-600 uppercase mb-3 italic">Tambah File Baru:</p>
                            <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
                                <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
                                <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white rounded-lg font-bold" id="input_append" required>
                                <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 uppercase font-black italic text-white shadow-lg shadow-blue-200">Upload</button>
                            </form>
                        </div>`;
                    }
                    // Render Sumber Lainnya (NONLIT, ASING, dll) di sini...

                    document.getElementById('detail_content').innerHTML = html;

                    // Render Lampiran
                    let fileHtml = "";
                    if (res.lampiran && res.lampiran.length > 0) {
                        res.lampiran.forEach(file => {
                            let fileName = file.nama_file || file.nama_berkas;
                            fileHtml += `
                            <div class="flex items-center p-3 bg-white rounded-xl border border-slate-200 gap-3 group mb-2 hover:border-primary transition-all">
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-[10px] font-black truncate uppercase text-slate-600">${fileName}</p>
                                </div>
                                <a href="<?= base_url('assets/berkas_umum/') ?>${fileName}" target="_blank" class="btn btn-xs btn-primary btn-circle text-white shadow-sm">
                                    <i class="mdi mdi-download"></i>
                                </a>
                            </div>`;
                        });
                    } else {
                        fileHtml = '<p class="text-center text-[10px] opacity-30 italic py-10 uppercase font-black">Tidak ada berkas digital</p>';
                    }
                    document.getElementById('list_lampiran').innerHTML = fileHtml;
                } else {
                    document.getElementById('detail_content').innerHTML = '<div class="alert alert-error">Data Gagal Dimuat (Null)</div>';
                }
            }
        });
    }

    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);
        let tokenVal = $('#token').val();

        formData.append('token', tokenVal); // Masukkan token terbaru

        btn.addClass('loading').prop('disabled', true).text('Mengunggah...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    Swal.fire({
                        theme: 'auto',
                        title: 'BERHASIL!',
                        text: 'Berkas berhasil ditambahkan. Halaman akan dimuat ulang.',
                        icon: 'success',
                        confirmButtonText: 'OKE',
                        target: document.getElementById('modal_detail'), // SWAL di depan modal
                        customClass: {
                            confirmButton: 'btn btn-primary px-10'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Refresh halaman agar token & data sinkron
                        }
                    });
                } else {
                    Swal.fire({
                        theme: 'auto',
                        title: 'GAGAL!',
                        text: res.message,
                        icon: 'error',
                        target: document.getElementById('modal_detail')
                    });
                }
            },
            error: function() {
                Swal.fire('ERROR', 'Token Expired. Refresh halaman.', 'error').then(() => location.reload());
            }
        });
    }
</script>
<script>
    function editUmum(id) {
        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: 'UMUM',
                id_data: id
            },
            dataType: 'JSON',
            success: function(res) {
                // Isi form di modal edit dengan data dari database
                $('#edit_id').val(res.data.id_berkas_umum);
                $('#edit_nama').val(res.data.nama_berkas_umum);
                $('#edit_rak').val(res.data.penyimpanan_rak);
                $('#edit_pic').val(res.data.pic);
                $('#edit_keterangan').val(res.data.keterangan);

                // Tampilkan modal edit
                modal_edit_umum.showModal();
            }
        });
    }
=======
<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-primary italic tracking-tighter uppercase">Penyimpanan Berkas</h1>
            <div class="flex items-center gap-2">
                <span class="badge badge-xs badge-primary"></span>
                <p class="text-[10px] opacity-60 font-bold uppercase tracking-widest text-base-content">Arsip Terpusat • Database: Nonlit & Perkara</p>
            </div>
        </div>

        <div id="csrf-holder">
            <?= crsf_ajax() ?>
        </div>
        <button onclick="modal_berkas_umum.showModal()" class="btn btn-sm btn-primary px-5 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            DIGITALISASI UMUM
        </button>
        <form action="<?= base_url('arsip/index') ?>" method="get" class="join shadow-sm">
            <input type="text" name="search"
                class="input input-bordered join-item w-full md:w-64 focus:outline-primary"
                placeholder="Cari nomor/pihak..."
                value="<?= $this->input->get('search') ?>">
            <button type="submit" class="btn btn-primary join-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success shadow-lg mb-6 border-none text-white font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= $this->session->flashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-300">
                    <tr class="text-base-content uppercase text-[11px] tracking-wider">
                        <th class="w-20">Sumber</th>
                        <th>Nomor Register / Perkara</th>
                        <th>Nama Pihak</th>
                        <th class="w-48 text-center">Lokasi Simpan</th>
                        <th class="text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($arsip)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-20">
                                <div class="flex flex-col items-center opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span class="font-black italic">Data tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($arsip as $row): ?>
                        <tr class="hover">
                            <td>
                                <div class="badge badge-sm font-black border-none <?= $row->sumber == 'NONLIT' ? 'bg-blue-500 text-white' : 'bg-orange-500 text-white' ?>">
                                    <?= $row->sumber ?>
                                </div>
                            </td>
                            <td class="font-bold text-xs font-mono text-primary">
                                <?= $row->nomor ?>
                            </td>
                            <td>
                                <div class="text-sm font-bold uppercase"><?= $row->nama_pihak ?></div>
                                <div class="text-[10px] opacity-40 truncate w-64 uppercase tracking-tighter">
                                    <?= $row->lokasi ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($row->id_rak): ?>
                                    <div class="badge badge-outline border-primary text-primary font-black py-3 px-4 gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        RAK: <?= $row->id_rak ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] italic opacity-30 text-error font-bold tracking-widest">BELUM DIARSIPKAN</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <button class="btn btn-square btn-ghost btn-sm text-info tooltip" data-tip="Lihat Detail & Berkas"
                                    onclick="viewDetail('<?= $row->sumber ?>', '<?= $row->id_data ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>

                                <?php if ($row->sumber === 'UMUM'): ?>
                                    <button onclick="editUmum(<?= $row->id_data ?>)" class="btn btn-xs btn-warning btn-square">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                                <!-- <button class="btn btn-square btn-ghost btn-sm text-primary hover:bg-primary hover:text-white transition-all"
                                    onclick="prepUpdate('<?= $row->sumber ?>', '<?= $row->id_data ?>', '<?= $row->id_rak ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 flex flex-col items-center gap-2">
        <span class="text-[10px] font-black opacity-30 tracking-[0.3em] uppercase">Halaman Navigasi</span>
        <?= $pagination ?>
    </div>
</div>

<dialog id="modal_edit_umum" class="modal">
    <div class="modal-box w-11/12 max-w-lg rounded-2xl border-t-8 border-warning p-0 shadow-2xl">
        <div class="p-5 border-b border-base-200 bg-slate-50 rounded-t-2xl">
            <h3 class="font-black text-xs uppercase tracking-[0.2em] text-slate-500">Koreksi Data Berkas Umum</h3>
        </div>

        <form action="<?= base_url('arsip_umum/update_proses') ?>" method="post" class="p-6 space-y-4">
            <input type="hidden" name="id_berkas_umum" id="edit_id">

            <div class="form-control">
                <label class="label"><span class="label-text text-[10px] font-black uppercase opacity-50">Nama Dokumen</span></label>
                <input type="text" name="nama_berkas_umum" id="edit_nama" class="input input-bordered input-sm rounded-lg font-bold text-black uppercase" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-[10px] font-black uppercase opacity-50">Lokasi Rak</span>
                    </label>
                    <input type="text" name="penyimpanan_rak" id="edit_rak" list="data_rak_edit" class="input input-bordered input-sm rounded-lg font-bold text-black uppercase">

                    <datalist id="data_rak_edit">
                        <?php foreach ($saran_rak as $r): ?>
                            <option value="<?= $r->penyimpanan_rak ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text text-[10px] font-black uppercase opacity-50">PIC Bertanggung Jawab</span></label>
                    <select name="pic" id="edit_pic" class="select select-bordered select-sm rounded-lg font-black text-[11px] uppercase">
                        <?php foreach ($pic_list as $p): ?>
                            <option value="<?= $p->pic ?>"><?= $p->pic ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text text-[10px] font-black uppercase opacity-50">Keterangan Tambahan</span></label>
                <textarea name="keterangan" id="edit_keterangan" class="textarea textarea-bordered rounded-lg text-xs font-medium text-slate-600" rows="3"></textarea>
            </div>

            <div class="modal-action bg-slate-50 p-4 rounded-b-2xl border-t border-base-200">
                <button type="submit" class="btn btn-warning btn-sm px-8 rounded-lg font-black uppercase italic tracking-tighter text-white">Update Data</button>
                <button type="button" onclick="modal_edit_umum.close()" class="btn btn-ghost btn-sm px-6 font-bold uppercase text-[10px]">Batal</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="modal_detail" class="modal">
    <div class="modal-box w-11/12 max-w-3xl border-t-4 border-info">
        <h3 class="font-black text-xl italic" id="detail_title">DETAIL BERKAS</h3>
        <hr class="my-4 opacity-10">

        <div id="detail_content" class="space-y-4">
        </div>

        <div class="mt-6">
            <h4 class="font-bold text-sm mb-2 uppercase opacity-50 tracking-widest">Daftar Lampiran Berkas</h4>
            <div id="list_lampiran" class="grid grid-cols-1 md:grid-cols-2 gap-2">
            </div>
        </div>

        <div class="modal-action">
            <button onclick="modal_detail.close()" class="btn btn-ghost">Tutup</button>
        </div>
    </div>
</dialog>


<dialog id="modal_rak" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box border-t-4 border-primary shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-3 bg-primary/10 rounded-xl text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div>
                <h3 class="font-black text-xl italic tracking-tight">Pindahkan Berkas</h3>
                <p class="text-[10px] font-bold opacity-50 uppercase">Update Lokasi Penyimpanan Fisik</p>
            </div>
        </div>

        <form action="<?= base_url('arsip/update_rak') ?>" method="post">
            <input type="hidden" name="sumber" id="m_sumber">
            <input type="hidden" name="id_data" id="m_id">

            <div class="form-control w-full mt-6">
                <label class="label">
                    <span class="label-text font-black text-xs uppercase tracking-wider">Pilih Lokasi Rak Baru</span>
                </label>
                <select name="id_rak" id="m_rak" class="select select-bordered w-full select-primary font-bold">
                    <option value="">-- Kosongkan Rak --</option>
                    <?php foreach ($list_rak as $r): ?>
                        <option value="<?= $r->id_rak ?>"><?= $r->kode_rak ?> - <?= $r->nama_rak ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="label mt-1">
                    <span class="label-text-alt italic opacity-50 text-[10px]">Pastikan berkas fisik sudah dipindahkan ke rak tujuan.</span>
                </label>
            </div>

            <div class="modal-action gap-2 mt-8">
                <button type="submit" class="btn btn-primary px-8 shadow-lg shadow-primary/30 font-black italic">Simpan Perubahan</button>
                <button type="button" onclick="modal_rak.close()" class="btn btn-ghost font-bold opacity-50 uppercase text-xs">Batal</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>


<dialog id="modal_berkas_umum" class="modal">
    <div class="modal-box w-11/12 max-w-2xl rounded-lg border-t-8 border-primary p-0 shadow-2xl">
        <div class="p-5 border-b border-base-200 flex justify-between items-center bg-base-100">
            <div>
                <h3 class="font-black text-sm uppercase tracking-widest text-slate-700">Digitalisasi Berkas Umum</h3>
                <p class="text-[9px] opacity-50 uppercase font-bold tracking-tighter mt-1 text-primary italic">Penyimpanan Mandiri Non-Perkara</p>
            </div>
            <button onclick="modal_berkas_umum.close()" class="btn btn-xs btn-circle btn-ghost">✕</button>
        </div>

        <form action="<?= base_url('arsip_umum/simpan') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-5 bg-white">

            <div class="form-control">
                <label class="label">
                    <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Nama Berkas / Judul Dokumen</span>
                </label>
                <input type="text" name="nama_berkas_umum" class="input input-bordered input-sm rounded font-bold uppercase focus:input-primary" placeholder="Contoh: SERTIFIKAT TANAH ASET JALAN TUNJUNGAN" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Lokasi Penyimpanan (RAK)</span>
                    </label>
                    <input type="text" name="penyimpanan_rak" list="data_rak_lama" class="input input-bordered input-sm rounded font-bold text-xs uppercase focus:input-primary" placeholder="Ketik atau pilih rak..." required>

                    <datalist id="data_rak_lama">
                        <?php if (!empty($saran_rak)): ?>
                            <?php foreach ($saran_rak as $rk): ?>
                                <option value="<?= $rk->penyimpanan_rak ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </datalist>
                    <p class="text-[8px] mt-1 opacity-40 italic">*Bisa ketik manual sesuai format data lama.</p>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Penanggung Jawab (PIC)</span>
                    </label>
                    <select name="pic" class="select select-bordered select-sm rounded font-bold text-xs focus:select-primary">
                        <option value="" disabled selected>Pilih PIC...</option>
                        <?php foreach ($pic_list as $p): ?>
                            <option value="<?= $p->pic ?>"><?= $p->pic ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" name="pic" class="input input-bordered input-sm rounded font-bold text-xs" placeholder="Nama petugas pengelola..." required> -->
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest">Keterangan Tambahan</span>
                </label>
                <textarea name="keterangan" class="textarea textarea-bordered rounded text-xs leading-relaxed" rows="2" placeholder="Catatan mengenai isi atau kondisi fisik berkas..."></textarea>
            </div>

            <!-- <div class="form-control bg-slate-50 p-4 rounded-xl border-2 border-dashed border-slate-200">
                <label class="label pt-0">
                    <span class="label-text text-[10px] font-black uppercase opacity-40 tracking-widest flex items-center gap-2 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Upload Scan Digital (Multiple)
                    </span>
                </label>
                <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm file-input-primary w-full rounded-lg" accept=".pdf,.jpg,.jpeg,.png">
                <p class="text-[9px] mt-2 italic opacity-40">*Pilih satu atau beberapa file (PDF/Gambar) sekaligus.</p>
            </div> -->

            <div class="modal-action border-t border-base-200 pt-5 mt-4">
                <button type="submit" class="btn btn-primary btn-sm px-10 rounded-md font-black italic uppercase tracking-tighter shadow-md">
                    Simpan Digitalisasi
                </button>
                <button type="button" onclick="modal_berkas_umum.close()" class="btn btn-ghost btn-sm px-6 rounded-md text-[10px] font-bold uppercase">
                    Batal
                </button>
            </div>
        </form>
    </div>
</dialog>


<script>
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
</script>
<script>
    /**
     * Menyiapkan data ke dalam modal sebelum dibuka
     * @param {string} sumber - 'NONLIT' atau 'ASING'
     * @param {string} id - ID data dari masing-masing tabel
     * @param {string} rak - ID Rak saat ini (untuk auto-select)
     */
    function prepUpdate(sumber, id, rak) {
        document.getElementById('m_sumber').value = sumber;
        document.getElementById('m_id').value = id;
        document.getElementById('m_rak').value = rak;

        // Tampilkan modal (DaisyUI menggunakan modal_id.showModal())
        modal_rak.showModal();
    }
</script>
<script>
    // 1. Fungsi Update Token Global agar sinkron dengan Helper Manual
    function updateTokenGlobal(newToken) {
        if (newToken) {
            $('#token').val(newToken);
            $('input[name="token"]').val(newToken);
            console.log("CSRF Token Synchronized");
        }
    }

    // 2. Fungsi Utama View Detail
    function viewDetail(sumber, id) {
        const currentToken = $('#token').val();

        // Reset Modal & Show Loading
        document.getElementById('detail_content').innerHTML = `
            <div class="flex justify-center p-10">
                <span class="loading loading-dots loading-lg text-primary"></span>
            </div>`;
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id,
                token: currentToken
            },
            dataType: 'JSON',
            success: function(res) {
                // Update Token untuk aksi berikutnya
                updateTokenGlobal(res.new_token);

                if (!res.data) {
                    document.getElementById('detail_content').innerHTML = '<div class="alert alert-error font-bold uppercase text-xs">Data Tidak Ditemukan</div>';
                    return;
                }

                let html = "";
                let listDet = res.detail_tambahan; // Data dari t_perkara_detail atau nonlit_det

                // --- LOGIKA TAMPILAN PER SUMBER ---

                if (sumber === 'ASING') {
                    html = `
                    <div class="stats shadow w-full mb-4 bg-orange-50 border border-orange-200">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-black uppercase text-orange-600">No. Perkara</div>
                            <div class="stat-value text-lg text-orange-700 font-black">${res.data.perkara_no || '-'}</div>
                            <div class="stat-desc font-bold text-orange-500 uppercase text-[9px]">Status: ${res.data.perkara_status || '-'}</div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black opacity-60 uppercase tracking-widest border-l-4 border-orange-500 pl-2">Riwayat Tingkat & Amar Putusan</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
                            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl mb-2">
                                <input type="radio" name="acc-asing" ${index === 0 ? 'checked' : ''} /> 
                                <div class="collapse-title p-4 flex justify-between items-center">
                                    <span class="text-[11px] font-black uppercase text-slate-700">${det.perkaradet_tingkat || 'TINGKAT'} - ${det.perkaradet_status || 'STATUS'}</span>
                                    <span class="badge badge-sm font-mono text-[9px]">${det.perkaradet_tgl_putusan || '-'}</span>
                                </div>
                                <div class="collapse-content bg-orange-50/20 border-t border-slate-50 pt-4">
                                    <p class="text-[9px] font-black opacity-40 uppercase mb-1 text-orange-600">Amar Putusan:</p>
                                    <p class="text-[11px] leading-relaxed text-slate-700 italic font-medium">"${det.perkaradet_keterangan || 'Tidak ada amar putusan.'}"</p>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-30 uppercase">Belum ada riwayat putusan</p>`;
                    }
                    html += `</div>`;

                } else if (sumber === 'NONLIT') {
                    html = `
                    <div class="stats shadow w-full mb-4 bg-blue-50 border border-blue-200">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-black uppercase text-blue-600">No. Register</div>
                            <div class="stat-value text-lg text-blue-800 font-black">${res.data.register_baru || '-'}</div>
                            <div class="stat-desc font-black uppercase text-blue-500 truncate text-[9px]">${res.data.permohonan_nonlit}</div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black opacity-60 uppercase tracking-widest border-l-4 border-blue-500 pl-2">Resume Notulensi Rapat</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det) => {
                            html += `
                            <div class="collapse collapse-arrow bg-white border border-slate-200 shadow-sm rounded-xl mb-2">
                                <input type="checkbox" /> 
                                <div class="collapse-title p-4 flex justify-between items-center">
                                    <span class="text-[11px] font-black uppercase text-blue-700">${det.judul_rapat || 'RAPAT KOORDINASI'}</span>
                                    <span class="text-[9px] font-bold opacity-40 font-mono">${det.tgl_rapat || '-'}</span>
                                </div>
                                <div class="collapse-content bg-blue-50/20 border-t border-slate-100 pt-4">
                                    <div class="mb-3">
                                        <p class="text-[9px] font-black opacity-40 uppercase mb-1 text-blue-600">Resume / Kesimpulan:</p>
                                        <p class="text-[11px] leading-snug text-slate-700 italic font-medium">"${det.kesimpulan || 'Belum ada resume.'}"</p>
                                    </div>
                                    <div class="flex justify-end pt-2 border-t border-blue-100">
                                        <a href="<?= base_url('assets/berkas_nonlit/') ?>${det.berkas}" target="_blank" 
                                           class="btn btn-xs btn-primary gap-1 ${det.berkas ? '' : 'btn-disabled'} uppercase font-black italic">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download Resume
                                        </a>
                                    </div>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-30 uppercase">Belum ada riwayat rapat</p>`;
                    }
                    html += `</div>`;

                } else if (sumber === 'UMUM') {
                    html = `
                    <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
                        <div class="stat p-5">
                            <div class="stat-title text-[10px] font-black uppercase text-primary">Nama Dokumen Digital</div>
                            <div class="stat-value text-lg text-slate-800 uppercase font-black leading-tight">${res.data.nama_berkas_umum}</div>
                            <div class="stat-desc mt-2 flex items-center gap-4">
                                <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                                <span class="text-[10px] font-bold opacity-50 uppercase">PIC: ${res.data.pic || '-'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6">
                        <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
                            <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
                            <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white" id="input_append" required>
                            <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 uppercase font-black italic text-white shadow-lg">Upload</button>
                        </form>
                    </div>`;


                }
                html += `
        <div class="mt-8 p-5 bg-slate-50 rounded-2xl border border-dashed border-slate-300 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h5 class="text-[11px] font-black uppercase text-slate-500 tracking-widest flex items-center gap-2">
                    <i class="mdi mdi-share-variant text-primary text-lg"></i> Akses Berbagi Dokumen
                </h5>
                <p class="text-[9px] opacity-60 font-bold uppercase italic">Buat link akses publik tanpa perlu login</p>
            </div>
            <button onclick="shareLink('${sumber}', '${id}')" class="btn btn-sm btn-primary px-6 rounded-xl font-black italic uppercase shadow-lg shadow-primary/20">
                Bagikan Berkas
            </button>
        </div>
    `;
                document.getElementById('detail_content').innerHTML = html;

                // --- RENDER LAMPIRAN DIGITAL ---
                let fileHtml = "";
                if (res.lampiran && res.lampiran.length > 0) {
                    res.lampiran.forEach(file => {
                        let fileName = file.nama_file || file.nama_berkas || file.berkas_laporan || file.name_berkas;
                        let filePath = (sumber === 'UMUM') ? 'assets/berkas_umum/' : (sumber === 'NONLIT' ? 'assets/berkas_nonlit/' : 'assets/upload/');
                        // Tombol hapus hanya muncul jika sumbernya UMUM
                        let btnDelete = (sumber === 'UMUM') ? `
        <button onclick="deleteFile('${file.id_berkas_umum_det}', '${res.data.id_berkas_umum}')" class="btn btn-xs btn-error btn-circle text-white shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        </button>` : '';
                        fileHtml += `
                        <div class="flex items-center p-3 bg-white rounded-xl border border-slate-200 gap-3 mb-2 hover:border-primary transition-all group">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black truncate uppercase text-slate-600">${fileName}</p>
                            </div>
                            <a href="<?= base_url() ?>${filePath}${fileName}" target="_blank" class="btn btn-xs btn-primary btn-circle text-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            </a>
                            ${btnDelete}
                        </div>`;
                    });
                } else {
                    fileHtml = '<div class="py-10 flex flex-col items-center opacity-20"><p class="text-[10px] font-black uppercase italic tracking-widest text-slate-500">Tidak ada berkas digital</p></div>';
                }
                document.getElementById('list_lampiran').innerHTML = fileHtml;
            }
        });
    }

    // 3. Fungsi Upload & Refresh Total
    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);
        let tokenVal = $('#token').val();

        formData.append('token', tokenVal);
        btn.addClass('loading').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                Swal.fire({
                    theme: 'auto',
                    title: 'BERHASIL!',
                    text: 'Berkas berhasil diunggah. Halaman akan dimuat ulang.',
                    icon: 'success',
                    confirmButtonText: 'OKE',
                    target: document.getElementById('modal_detail'),
                    customClass: {
                        confirmButton: 'btn btn-primary px-10'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function() {
                Swal.fire({
                    title: 'ERROR',
                    text: 'Token Habis. Refresh halaman.',
                    icon: 'error'
                }).then(() => location.reload());
            }
        });
    }
</script>

<script>
    function deleteFile(id_det, id_utama) {
        const tokenVal = $('#token').val();

        Swal.fire({
            theme: 'auto',
            title: 'HAPUS BERKAS?',
            text: "File akan dihapus permanen dari server!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS!',
            target: document.getElementById('modal_detail')
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("arsip_umum/delete_file") ?>',
                    type: 'POST',
                    data: {
                        id_det: id_det,
                        token: tokenVal
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        updateTokenGlobal(res.new_token); // Update token setelah hapus
                        if (res.status) {
                            Swal.fire({
                                theme: 'auto',
                                title: 'TERHAPUS!',
                                icon: 'success',
                                timer: 1000,
                                showConfirmButton: false,
                                target: document.getElementById('modal_detail')
                            });
                            // Refresh isi modal tanpa reload halaman
                            viewDetail('UMUM', id_utama);
                        }
                    },
                    error: function() {
                        location.reload(); // Jika error/token habis, paksa reload
                    }
                });
            }
        });
    }
</script>
<!-- <script>
    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);

        // Ambil ID dari input hidden yang ada di form
        let id_data = $('input[name="id_berkas_umum"]').val();

        // Efek loading
        btn.addClass('loading').prop('disabled', true).text('Sedang Mengunggah...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    // PENTING: Panggil ulang fungsi viewDetail agar modal me-refresh isinya
                    // Parameter 'UMUM' dan id_data harus sesuai dengan yang dikirim dari controller
                    viewDetail('UMUM', res.id_data);

                    // Reset input file agar bersih kembali
                    $('#input_append').val('');

                    // Opsional: Beri notifikasi kecil
                    console.log('Upload Berhasil, refreshing detail...');
                } else {
                    alert('Gagal: ' + res.message);
                }
            },
            error: function(err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi saat upload.');
            },
            success: function(res) {
                if (res.status) {
                    // res.id_data diambil dari echo json_encode di Controller tadi
                    // Pastikan 'UMUM' ditulis huruf besar sesuai logika di get_detail_berkas
                    viewDetail('UMUM', res.id_data);

                    // Bersihkan input file setelah upload
                    $('input[name="files[]"]').val('');
                    alert('Berkas berhasil ditambahkan!');
                }
            },
            complete: function() {
                btn.removeClass('loading').prop('disabled', false).text('Upload Sekarang');
            }
        });
    }
</script> -->
<!-- <script>
    function viewDetail(sumber, id) {
        document.getElementById('detail_content').innerHTML = '<div class="p-10 text-center"><span class="loading loading-spinner text-primary"></span></div>';
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id
            },
            dataType: 'JSON',
            success: function(res) {
                let html = "";
                let listDet = res.detail_tambahan; // Array data detail

                if (sumber === 'ASING') {
                    // Header Informasi Utama (Stats)
                    html = `
    <div class="stats shadow w-full mb-6 bg-orange-50 border border-orange-200">
        <div class="stat">
            <div class="stat-title text-[10px] font-bold uppercase tracking-widest text-orange-600/60">Nomor Perkara Utama</div>
            <div class="stat-value text-lg text-orange-700">${res.data.perkara_no}</div>
            <div class="stat-desc font-bold text-orange-500 italic">Tgl: ${res.data.perkara_tgl || '-'}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-white p-4 rounded-2xl border border-base-200 shadow-sm">
        <div class="col-span-1 md:col-span-2 pb-2 border-b border-dashed border-base-200">
            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1">Para Pihak (Utama)</p>
            <div class="flex flex-col gap-1">
                <p class="text-xs font-bold"><span class="text-primary">P:</span> ${res.data.perkara_penggugat || '-'}</p>
                <p class="text-xs font-bold"><span class="text-error">T:</span> ${res.data.perkara_tergugat || '-'}</p>
            </div>
        </div>
        <div class="col-span-1 md:col-span-2">
            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1">Objek Perkara</p>
            <p class="text-xs italic leading-relaxed text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-100">
                ${res.data.perkara_alamat || 'Data objek perkara tidak tersedia.'}
            </p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex justify-between items-center px-1">
            <h4 class="text-xs font-black opacity-60 uppercase tracking-widest border-l-4 border-orange-500 pl-2">Riwayat Putusan / Tingkat</h4>
            <span class="badge badge-sm badge-outline font-mono opacity-50">${listDet ? listDet.length : 0} Tingkat</span>
        </div>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl overflow-hidden hover:border-orange-300 transition-all">
                <input type="radio" name="my-accordion-asing" ${index === 0 ? 'checked' : ''} /> 
                <div class="collapse-title p-4">
                    <div class="flex justify-between items-center w-[95%]">
                        <span class="text-sm font-black uppercase text-slate-700">
                            ${det.perkaradet_tingkat} - ${det.perkaradet_status} | <span class="text-primary">${det.perkaradet_no || 'No. Belum Input'}</span>
                        </span>
                        <div class="badge badge-sm badge-ghost font-bold font-mono">${det.perkaradet_tgl_putusan || '-'}</div>
                    </div>
                </div>
                <div class="collapse-content bg-orange-50/30 border-t border-base-100 pt-4">
                    <div class="space-y-3 py-2">
                        <div>
                            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1 text-orange-600">Pihak Terkait (Level Ini):</p>
                            <p class="text-xs font-bold text-slate-800 leading-tight">${det.perkaradet_pihak || '-'}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black opacity-40 uppercase tracking-widest mb-1 text-orange-600">Amar Putusan:</p>
                            <p class="text-[11px] leading-relaxed italic text-slate-600 whitespace-pre-line">${det.perkaradet_keterangan || 'Tidak ada keterangan tambahan.'}</p>
                        </div>
                        <div class="flex justify-end pt-2 border-t border-orange-100">
                            <span class="text-[8px] font-bold opacity-30 italic uppercase">Input: ${det.perkaradet_created_date || '-'}</span>
                        </div>
                    </div>
                </div>
            </div>`;
                        });
                    } else {
                        html += `
        <div class="flex flex-col items-center justify-center p-10 bg-base-100 rounded-2xl border-2 border-dashed border-base-300 opacity-30">
            <p class="text-xs font-bold italic uppercase tracking-widest text-center">Belum ada riwayat putusan berjenjang.</p>
        </div>`;
                    }
                    html += `</div>`;

                } else if (sumber === 'UMUM') {
                    html = `
    <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
        <div class="stat p-5">
            <div class="stat-title text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 text-primary">Nama Dokumen Digital</div>
            <div class="stat-value text-lg text-slate-800 leading-tight uppercase font-black">${res.data.nama_berkas_umum}</div>
            <div class="stat-desc mt-2 flex items-center gap-4">
                <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                <span class="text-[10px] font-bold opacity-50 uppercase tracking-tighter">PIC: ${res.data.pic || '-'}</span>
            </div>
        </div>
    </div>

    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6 group transition-all hover:border-blue-400">
        <div class="flex items-center justify-between mb-3">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah File Baru ke Berkas Ini
            </p>
            <span class="text-[9px] font-bold text-blue-400 italic italic uppercase">Max 10MB (PDF/JPG)</span>
        </div>
        <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
            <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
             
            <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white rounded-lg font-bold" id="input_append" required>
            <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 shadow-lg shadow-blue-200 uppercase font-black italic tracking-tighter">
                Upload Sekarang
            </button>
        </form>
    </div>

    <div class="space-y-3">
        <div class="flex items-center gap-3 px-1">
             <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Lampiran Digital Tersimpan</h4>
             <div class="h-[1px] flex-1 bg-slate-100"></div>
        </div>`;
                } else {
                    // Tampilan NONLIT (Timeline Rapat)
                    // Tampilan NONLIT dengan Dropdown/Accordion untuk setiap Rapat
                    html = `
    <div class="stats border border-base-300 w-full mb-6 rounded-lg bg-base-100">
                    <div class="stat">
                        <div class="stat-title text-[10px] font-bold uppercase tracking-wider">No. Register</div>
                        <div class="stat-value text-xl text-info">${res.data.register_baru}</div>
                        <div class="stat-desc font-bold uppercase truncate">${res.data.permohonan_nonlit}</div>
                    </div>
                    <div class="stat border-l border-base-300">
                        <div class="stat-title text-[10px] font-bold uppercase tracking-wider">PIC / Status</div>
                        <div class="stat-value text-sm font-black uppercase">${res.data.pic || '-'}</div>
                        <div class="stat-desc"><span class="badge badge-info badge-outline badge-xs font-bold">${res.data.status || 'AKTIF'}</span></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 mb-2">Notulensi Rapat</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl overflow-hidden">
                <input type="checkbox" name="accordion-nonlit" /> 
                
                <div class="collapse-title p-4 flex flex-col gap-1">
                    <div class="flex justify-between items-center w-[95%]">
                        <span class="text-[11px] font-black uppercase text-blue-600 tracking-tight">
                            ${det.judul_rapat || 'Rapat Tanpa Judul'}
                        </span>
                        <span class="text-[10px] font-bold opacity-40 font-mono italic">${det.tgl_rapat || '-'}</span>
                    </div>
                </div>

                <div class="collapse-content bg-slate-50 border-t border-base-200 pt-4">
                    <div class="space-y-4 py-2">
                                   
                        
                        <div class="p-3 bg-blue-100 rounded-xl border border-blue-200 relative overflow-hidden">
                            <div class="absolute -right-2 -top-2 opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-[9px] font-black text-blue-500 uppercase mb-1 tracking-widest">Kesimpulan Akhir:</p>
                            <p class="text-xs font-bold text-blue-900 leading-snug">${det.kesimpulan || 'Belum ada kesimpulan.'}</p>
                        </div>
                        
                        <div class="flex justify-end pt-2">
                            <span class="text-[8px] font-bold opacity-30 italic uppercase">Diupdate oleh: ${det.updated_by || 'System'}</span>
                        </div> 
                        <a href="<?= base_url('assets/berkas_nonlit/') ?>${det.berkas || ''}" target="_blank" class="btn btn-xs btn-primary ${det.berkas ? '' : 'btn-disabled'}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            <span class="ml-1 text-[10px]">Unduh Berkas Rapat</span>
                        </a>
                    </div>
                </div>
            </div>`;
                        });
                    } else {
                        html += `
        <div class="flex flex-col items-center justify-center p-10 bg-base-100 rounded-2xl border-2 border-dashed border-base-300 opacity-30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            <p class="text-xs font-bold italic">Belum ada riwayat rapat untuk nomor ini.</p>
        </div>`;
                    }
                    html += `</div>`;
                }
                document.getElementById('detail_content').innerHTML = html;

                // Render Lampiran (Tetap)
                // Render Lampiran

                if (res.lampiran && res.lampiran.length > 0) {
                    res.lampiran.forEach(file => {
                        let fileName = "";
                        let fileUrl = "";

                        if (sumber === 'ASING') {
                            // Konfigurasi untuk ASING
                            fileName = file.name_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/asing/assets/upload/${fileName}`;
                        } else if (sumber === 'NONLIT') {
                            // Konfigurasi untuk UMUM
                            fileName = file.nama_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/nonlitigasi/assets/berkas_lampiran/${fileName}`;
                        } else if (sumber === 'POLISI') {
                            fileName = file.nama_file; // Sesuaikan jika nama kolom di laporan_polisi_det berbeda
                            fileUrl = `<?= base_url('assets/laporan_polisi/') ?>${fileName}`;
                        } else if (sumber === 'MASALAH') {
                            fileName = file.nama_file; // Sesuaikan jika nama kolom di masalah_det berbeda
                            fileUrl = `<?= base_url('assets/masalah/') ?>${fileName}`;
                        } // --- INI PERBAIKANNYA ---
                        else if (sumber === 'UMUM') {
                            fileName = file.nama_file; // SESUAIKAN DENGAN SCREENSHOT NETWORK ANDA
                            fileUrl = `<?= base_url('assets/berkas_umum/') ?>${fileName}`;
                        }

                        // Tentukan Icon berdasarkan tipe file (opsional tapi bagus untuk UI)
                        let isPdf = fileName.toLowerCase().endsWith('.pdf');

                        fileHtml += `
        <div class="flex items-center p-3 bg-base-100 rounded-xl border border-base-300 gap-3 hover:shadow-md hover:border-primary transition-all group">
            <div class="p-2 ${isPdf ? 'bg-error/10 text-error' : 'bg-info/10 text-info'} rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-[10px] font-black truncate uppercase tracking-tight text-slate-600">${fileName}</p>
                <p class="text-[8px] opacity-50 uppercase font-bold">${isPdf ? 'PDF Document' : 'Attachment'}</p>
            </div>
            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary btn-circle text-white shadow-sm hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </a>
        </div>`;
                    });
                } else {
                    fileHtml = `
    <div class="col-span-full py-10 flex flex-col items-center opacity-20">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="text-xs font-bold italic uppercase">Tidak ada berkas fisik</p>
    </div>`;
                }
                document.getElementById('list_lampiran').innerHTML = fileHtml;
            }
        });
    }
</script> -->
<!-- <script>
    function viewDetail(sumber, id) {
        // 1. Reset State Modal ke Loading
        document.getElementById('detail_content').innerHTML = '<div class="p-10 text-center"><span class="loading loading-spinner text-primary"></span></div>';
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id
            },
            dataType: 'JSON',
            success: function(res) {
                let html = "";
                let fileHtml = "";
                let listDet = res.detail_tambahan; // Riwayat Sidang / Rapat

                // =============================================================
                // BAGIAN 1: RENDER KONTEN DETAIL (AMAR, RESUME, INFO UTAMA)
                // =============================================================

                if (sumber === 'ASING') {
                    html = `
                    <div class="stats shadow w-full mb-6 bg-orange-50 border border-orange-200">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-black uppercase tracking-widest text-orange-600/60">Nomor Perkara Utama</div>
                            <div class="stat-value text-lg text-orange-700 font-black">${res.data.perkara_no}</div>
                            <div class="stat-desc font-bold text-orange-500 italic uppercase text-[9px]">Tgl Reg: ${res.data.perkara_tgl || '-'}</div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black opacity-60 uppercase tracking-[0.2em] border-l-4 border-orange-500 pl-2 text-slate-700">Riwayat Putusan & Amar</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det, index) => {
                            html += `
                            <div class="collapse collapse-arrow bg-white border border-base-200 shadow-sm rounded-xl overflow-hidden mb-3">
                                <input type="radio" name="acc-asing" ${index === 0 ? 'checked' : ''} /> 
                                <div class="collapse-title p-4">
                                    <div class="flex justify-between items-center pr-6">
                                        <span class="text-[11px] font-black uppercase text-slate-700">${det.perkaradet_tingkat} - ${det.perkaradet_status}</span>
                                        <span class="badge badge-sm badge-ghost font-mono text-[9px]">${det.perkaradet_tgl_putusan || '-'}</span>
                                    </div>
                                </div>
                                <div class="collapse-content bg-orange-50/20 border-t border-slate-50 pt-4">
                                    <p class="text-[9px] font-black opacity-40 uppercase mb-1 text-orange-600">Amar Putusan:</p>
                                    <p class="text-[11px] leading-relaxed text-slate-700 italic">"${det.perkaradet_keterangan || 'Tidak ada amar putusan.'}"</p>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-40 uppercase">Belum ada riwayat putusan</p>`;
                    }
                } else if (sumber === 'NONLIT') {
                    html = `
                    <div class="stats border border-blue-200 w-full mb-6 rounded-xl bg-blue-50/30">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-bold uppercase text-blue-400">No. Register</div>
                            <div class="stat-value text-lg text-blue-800 font-black">${res.data.register_baru}</div>
                            <div class="stat-desc font-black uppercase text-blue-600 truncate">${res.data.permohonan_nonlit}</div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 mb-2 text-slate-700">Resume & Notulensi Rapat</h4>`;

                    if (listDet && listDet.length > 0) {
                        listDet.forEach((det) => {
                            html += `
                            <div class="p-4 bg-white border border-slate-200 rounded-xl mb-3 shadow-sm">
                                <div class="flex justify-between items-start mb-2 border-b border-slate-50 pb-2">
                                    <p class="text-[11px] font-black text-blue-600 uppercase tracking-tight">${det.judul_rapat || 'Rapat Koordinasi'}</p>
                                    <span class="text-[9px] font-bold opacity-40 uppercase font-mono">${det.tgl_rapat || '-'}</span>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Resume / Kesimpulan:</p>
                                    <p class="text-[11px] leading-snug text-slate-700 italic whitespace-pre-line">"${det.kesimpulan || 'Belum ada resume rapat.'}"</p>
                                </div>
                                <a href="<?= base_url('assets/berkas_nonlit/') ?>${det.berkas || ''}" target="_blank" class="btn btn-xs btn-primary mt-3 ${det.berkas ? '' : 'btn-disabled'}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    <span class="ml-1 text-[10px]">Unduh Notulensi</span>
                                </a>
                            </div>`;
                        });
                    } else {
                        html += `<p class="p-6 text-center text-[10px] italic opacity-40 uppercase">Belum ada riwayat rapat</p>`;
                    }
                } else if (sumber === 'UMUM') {
                    html = `

                    
                    <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
                        <div class="stat p-5">
                            <div class="stat-title text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 text-primary">Nama Dokumen Digital</div>
                            <div class="stat-value text-lg text-slate-800 leading-tight uppercase font-black">${res.data.nama_berkas_umum}</div>
                            <div class="stat-desc mt-2 flex items-center gap-4">
                                <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                                <span class="text-[10px] font-bold opacity-50 uppercase tracking-tighter">PIC: ${res.data.pic || '-'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6 group transition-all hover:border-blue-400">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2 font-black italic">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Tambah File Baru ke Folder Ini
                            </p>
                        </div>
                        <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
                            <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
                            <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white rounded-lg" id="input_append" required>
                            <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 uppercase font-black italic tracking-tighter text-white">Upload</button>
                        </form>
                    </div>`;
                } else if (sumber === 'POLISI' || sumber === 'MASALAH') {
                    let judul = (sumber === 'POLISI') ? res.data.nomor_polisi : res.data.nama_masalah;
                    let sub = (sumber === 'POLISI') ? res.data.pelapor : res.data.alamat_masalah;
                    html = `
                    <div class="stats border border-slate-200 w-full mb-6 rounded-xl bg-white">
                        <div class="stat p-4">
                            <div class="stat-title text-[10px] font-bold uppercase opacity-40">${sumber}</div>
                            <div class="stat-value text-lg text-slate-800 font-black">${judul || '-'}</div>
                            <div class="stat-desc font-bold text-slate-500 uppercase">${sub || '-'}</div>
                        </div>
                    </div>`;
                }

                document.getElementById('detail_content').innerHTML = html;

                // =============================================================
                // BAGIAN 2: RENDER LAMPIRAN (FILE DIGITAL)
                // =============================================================

                if (res.lampiran && res.lampiran.length > 0) {
                    res.lampiran.forEach(file => {
                        let fileName = "";
                        let fileUrl = "";

                        // Mapping File Name & URL berdasarkan sumber
                        if (sumber === 'ASING') {
                            fileName = file.name_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/asing/assets/upload/${fileName}`;
                        } else if (sumber === 'NONLIT') {
                            fileName = file.nama_berkas;
                            fileUrl = `https://assistdpbt.surabaya.go.id/nonlitigasi/assets/berkas_lampiran/${fileName}`;
                        } else if (sumber === 'POLISI') {
                            fileName = file.nama_file;
                            fileUrl = `<?= base_url('assets/laporan_polisi/') ?>${fileName}`;
                        } else if (sumber === 'MASALAH') {
                            fileName = file.nama_file;
                            fileUrl = `<?= base_url('assets/masalah/') ?>${fileName}`;
                        } else if (sumber === 'UMUM') {
                            fileName = file.nama_file;
                            fileUrl = `<?= base_url('assets/berkas_umum/') ?>${fileName}`;
                        }

                        let isPdf = fileName ? fileName.toLowerCase().endsWith('.pdf') : false;

                        fileHtml += `
                        <div class="flex items-center p-3 bg-white rounded-xl border border-slate-200 gap-3 hover:shadow-md hover:border-primary transition-all group mb-2">
                            <div class="p-2 ${isPdf ? 'bg-error/10 text-error' : 'bg-info/10 text-info'} rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black truncate uppercase tracking-tight text-slate-600">${fileName}</p>
                                <p class="text-[8px] opacity-40 uppercase font-bold text-slate-400">${isPdf ? 'PDF Document' : 'Image/File'}</p>
                            </div>
                            <a href="${fileUrl}" target="_blank" class="btn btn-xs btn-primary btn-circle text-white shadow-sm transition-transform hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            </a>
                        </div>`;
                    });
                } else {
                    fileHtml = `
                    <div class="py-10 flex flex-col items-center opacity-20 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-[10px] font-black uppercase italic tracking-widest">Tidak ada berkas digital</p>
                    </div>`;
                }
                document.getElementById('list_lampiran').innerHTML = fileHtml;
            }
        });
    }

    // Fungsi Upload Tambahan (Hanya untuk UMUM)
    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);
        let id_data = $('input[name="id_berkas_umum"]').val();

        btn.addClass('loading').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    viewDetail('UMUM', id_data); // Refresh modal
                    $('#input_append').val(''); // Reset input
                } else {
                    alert('Gagal upload!');
                }
            },
            complete: function() {
                btn.removeClass('loading').prop('disabled', false).text('Upload Sekarang');
            }
        });
    }
</script> -->


<script>
    // Fungsi pembantu agar semua input token di halaman terupdate
    function updateTokenGlobal(newToken) {
        if (newToken) {
            $('#token').val(newToken);
            $('input[name="token"]').val(newToken);
            console.log("Token Updated");
        }
    }

    function viewDetail2(sumber, id) {
        // Ambil token dari input hidden id="token"
        const currentToken = $('#token').val();

        document.getElementById('detail_content').innerHTML = '<div class="p-10 text-center"><span class="loading loading-spinner text-primary"></span></div>';
        document.getElementById('list_lampiran').innerHTML = '';
        modal_detail.showModal();

        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: sumber,
                id_data: id,
                token: currentToken // Kirim token untuk cek_csrf
            },
            dataType: 'JSON',
            success: function(res) {
                // Update token setelah request detail berhasil
                updateTokenGlobal(res.new_token);

                if (res.status && res.data) {
                    let html = "";
                    // Tampilan UMUM
                    if (sumber === 'UMUM') {
                        html = `
                        <div class="stats border border-slate-200 w-full mb-6 rounded-2xl bg-white shadow-sm overflow-hidden">
                            <div class="stat p-5">
                                <div class="stat-title text-[10px] font-black uppercase text-primary">Nama Dokumen Digital</div>
                                <div class="stat-value text-lg text-slate-800 uppercase font-black">${res.data.nama_berkas_umum}</div>
                                <div class="stat-desc mt-2 flex items-center gap-4">
                                    <span class="badge badge-ghost font-bold text-[10px] px-3">RAK: ${res.data.penyimpanan_rak || '-'}</span>
                                    <span class="text-[10px] font-bold opacity-50 uppercase tracking-tighter">PIC: ${res.data.pic || '-'}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6 group">
                            <p class="text-[10px] font-black text-blue-600 uppercase mb-3 italic">Tambah File Baru:</p>
                            <form id="form_append_file" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
                                <input type="hidden" name="id_berkas_umum" value="${res.data.id_berkas_umum}">
                                <input type="file" name="files[]" multiple class="file-input file-input-bordered file-input-sm flex-1 bg-white rounded-lg font-bold" id="input_append" required>
                                <button type="button" id="btn_append" onclick="appendFile()" class="btn btn-sm btn-primary px-6 uppercase font-black italic text-white shadow-lg shadow-blue-200">Upload</button>
                            </form>
                        </div>`;
                    }
                    // Render Sumber Lainnya (NONLIT, ASING, dll) di sini...

                    document.getElementById('detail_content').innerHTML = html;

                    // Render Lampiran
                    let fileHtml = "";
                    if (res.lampiran && res.lampiran.length > 0) {
                        res.lampiran.forEach(file => {
                            let fileName = file.nama_file || file.nama_berkas;
                            fileHtml += `
                            <div class="flex items-center p-3 bg-white rounded-xl border border-slate-200 gap-3 group mb-2 hover:border-primary transition-all">
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-[10px] font-black truncate uppercase text-slate-600">${fileName}</p>
                                </div>
                                <a href="<?= base_url('assets/berkas_umum/') ?>${fileName}" target="_blank" class="btn btn-xs btn-primary btn-circle text-white shadow-sm">
                                    <i class="mdi mdi-download"></i>
                                </a>
                            </div>`;
                        });
                    } else {
                        fileHtml = '<p class="text-center text-[10px] opacity-30 italic py-10 uppercase font-black">Tidak ada berkas digital</p>';
                    }
                    document.getElementById('list_lampiran').innerHTML = fileHtml;
                } else {
                    document.getElementById('detail_content').innerHTML = '<div class="alert alert-error">Data Gagal Dimuat (Null)</div>';
                }
            }
        });
    }

    function appendFile() {
        let btn = $('#btn_append');
        let formData = new FormData($('#form_append_file')[0]);
        let tokenVal = $('#token').val();

        formData.append('token', tokenVal); // Masukkan token terbaru

        btn.addClass('loading').prop('disabled', true).text('Mengunggah...');

        $.ajax({
            url: '<?= base_url("arsip_umum/append_file") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    Swal.fire({
                        theme: 'auto',
                        title: 'BERHASIL!',
                        text: 'Berkas berhasil ditambahkan. Halaman akan dimuat ulang.',
                        icon: 'success',
                        confirmButtonText: 'OKE',
                        target: document.getElementById('modal_detail'), // SWAL di depan modal
                        customClass: {
                            confirmButton: 'btn btn-primary px-10'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Refresh halaman agar token & data sinkron
                        }
                    });
                } else {
                    Swal.fire({
                        theme: 'auto',
                        title: 'GAGAL!',
                        text: res.message,
                        icon: 'error',
                        target: document.getElementById('modal_detail')
                    });
                }
            },
            error: function() {
                Swal.fire('ERROR', 'Token Expired. Refresh halaman.', 'error').then(() => location.reload());
            }
        });
    }
</script>
<script>
    function editUmum(id) {
        $.ajax({
            url: '<?= base_url("arsip/get_detail_json") ?>',
            type: 'POST',
            data: {
                sumber: 'UMUM',
                id_data: id
            },
            dataType: 'JSON',
            success: function(res) {
                // Isi form di modal edit dengan data dari database
                $('#edit_id').val(res.data.id_berkas_umum);
                $('#edit_nama').val(res.data.nama_berkas_umum);
                $('#edit_rak').val(res.data.penyimpanan_rak);
                $('#edit_pic').val(res.data.pic);
                $('#edit_keterangan').val(res.data.keterangan);

                // Tampilkan modal edit
                modal_edit_umum.showModal();
            }
        });
    }
>>>>>>> Initial commit dari server
</script>