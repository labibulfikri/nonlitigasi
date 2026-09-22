<div class="p-6 space-y-6 bg-slate-50 min-h-screen">
    <!-- Header & Filter Tanggal -->
    <div class="flex flex-wrap justify-between items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge badge-primary badge-sm font-bold uppercase">Laporan Realtime</span>
                <span class="text-xs text-slate-400 font-semibold">
                    Cut-off: <?= isset($progres->tgl_laporan) ? date('d F Y', strtotime($progres->tgl_laporan)) : date('d F Y'); ?>
                </span>
            </div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mt-1">Progres Digitalisasi & Scan Berkas</h2>
            <p class="text-xs text-slate-500">Klik pada nama kategori perkara di bawah untuk melihat rincian aktivitas seluruh petugas</p>
        </div>

        <form method="GET" action="<?= base_url('laporan/progres_scan'); ?>" class="flex items-center gap-2">
            <input type="date" name="tanggal" value="<?= isset($progres->tgl_laporan) ? $progres->tgl_laporan : date('Y-m-d'); ?>" class="input input-sm input-bordered rounded-xl text-xs font-bold" />
            <button type="submit" class="btn btn-sm btn-primary text-white rounded-xl">
                <i class="mdi mdi-filter"></i> Tampilkan
            </button>
        </form>
    </div>

    <!-- Ringkasan Cards Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-100">Scan Hari Ini</span>
            <div class="flex items-baseline gap-2 mt-2">
                <h3 class="text-4xl font-black"><?= isset($progres->total_today) ? $progres->total_today : 0; ?></h3>
                <span class="text-sm text-blue-200 font-medium">Data Persil</span>
            </div>
            <div class="mt-4 pt-3 border-t border-blue-400/30 flex justify-between text-xs font-medium text-blue-100">
                <span>Non-Litigasi: <strong><?= isset($progres->nonlit_today) ? $progres->nonlit_today : 0; ?></strong></span>
                <span>Litigasi (ASING): <strong><?= isset($progres->asing_today) ? $progres->asing_today : 0; ?></strong></span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Litigasi (ASING) Terscan</span>
            <h3 class="text-3xl font-black text-slate-800 mt-1"><?= isset($progres->asing_total) ? $progres->asing_total : 0; ?> <span class="text-xs text-slate-400 font-normal">Data</span></h3>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Non-Litigasi Terscan</span>
            <h3 class="text-3xl font-black text-slate-800 mt-1"><?= isset($progres->nonlit_total) ? $progres->nonlit_total : 0; ?> <span class="text-xs text-slate-400 font-normal">Data</span></h3>
        </div>
    </div>

    <!-- Tabel Rekapitulasi dengan Accordion/Klik Kategori ke Bawah -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 uppercase text-sm tracking-wider flex items-center gap-2">
                <i class="mdi mdi-account-group text-indigo-600 text-lg"></i>
                RINCIAN REKAPITULASI & USER UPLOAD (<?= isset($progres->tgl_laporan) ? date('d SEPTEMBER Y', strtotime($progres->tgl_laporan)) : date('d SEPTEMBER Y'); ?>)
            </h3>
            <span class="text-xs text-indigo-600 font-bold italic">💡 Klik nama kategori untuk membuka rincian user</span>
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            <!-- Header Tabel -->
            <div class="grid grid-cols-12 bg-slate-50 p-4 font-bold text-slate-600 uppercase">
                <div class="col-span-1">NO</div>
                <div class="col-span-5">KATEGORI PERKARA</div>
                <div class="col-span-3 text-center">LOG HARI INI</div>
                <div class="col-span-3 text-center">AKUMULASI TERSCAN</div>
            </div>

            <!-- Kategori 1: Litigasi (ASING) -->
            <details class="group">
                <summary class="grid grid-cols-12 p-4 items-center cursor-pointer hover:bg-indigo-50/50 transition-colors list-none select-none">
                    <div class="col-span-1 font-bold text-slate-400">1</div>
                    <div class="col-span-5 flex items-center gap-2 font-black text-slate-800 group-open:text-indigo-600">
                        <i class="mdi mdi-chevron-right text-lg transition-transform group-open:rotate-90"></i>
                        <span>Litigasi (ASING)</span>
                        <span class="badge badge-ghost badge-sm text-[10px] font-normal">Klik untuk mekar</span>
                    </div>
                    <div class="col-span-3 text-center font-bold text-indigo-600">
                        +<?= isset($progres->asing_today) ? $progres->asing_today : 0; ?> Log Aktifitas
                    </div>
                    <div class="col-span-3 text-center font-black text-slate-800">
                        <?= isset($progres->asing_total) ? $progres->asing_total : 0; ?> Log Aktifitas
                    </div>
                </summary>

                <!-- Konten Rincian User (Mekar ke bawah saat diklik) -->
                <div class="p-4 bg-slate-50/80 border-t border-slate-200/60 space-y-2 animate-in fade-in duration-200">
                    <div class="font-bold text-slate-700 uppercase text-[11px] mb-2 px-2 flex justify-between">
                        <span>Daftar Seluruh Petugas (Litigasi ASING)</span>
                        <span>Capaian Hari Ini / Total</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        <?php if (!empty($progres->user_asing)): ?>
                            <?php foreach ($progres->user_asing as $u): ?>
                                <div class="flex justify-between items-center py-1.5 px-2 hover:bg-slate-50 rounded-lg text-slate-700">
                                    <div>
                                        <div class="font-bold uppercase text-slate-800"><?= htmlspecialchars($u->username); ?></div>
                                        <div class="text-[9px] opacity-50 uppercase font-semibold"><?= htmlspecialchars($u->role); ?></div>
                                    </div>
                                    <div class="text-right flex items-center gap-2">
                                        <span class="badge badge-sm font-bold <?= $u->scan_today > 0 ? 'badge-primary' : 'badge-ghost opacity-60'; ?>">
                                            +<?= $u->scan_today; ?>
                                        </span>
                                        <?php if ($u->scan_today > 0): ?>
                                            <button type="button"
                                                class="btn btn-xs btn-primary btn-outline rounded-md btn-detail-user"
                                                data-userid="<?= $u->user_id; ?>"
                                                data-username="<?= htmlspecialchars($u->username); ?>"
                                                data-kategori="asing"
                                                data-tanggal="<?= $progres->tgl_laporan; ?>">
                                                Detail
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </details>

            <!-- Kategori 2: Non-Litigasi -->
            <details class="group">
                <summary class="grid grid-cols-12 p-4 items-center cursor-pointer hover:bg-emerald-50/50 transition-colors list-none select-none">
                    <div class="col-span-1 font-bold text-slate-400">2</div>
                    <div class="col-span-5 flex items-center gap-2 font-black text-slate-800 group-open:text-emerald-600">
                        <i class="mdi mdi-chevron-right text-lg transition-transform group-open:rotate-90"></i>
                        <span>Non-Litigasi</span>
                        <span class="badge badge-ghost badge-sm text-[10px] font-normal">Klik untuk mekar</span>
                    </div>
                    <div class="col-span-3 text-center font-bold text-indigo-600">
                        +<?= isset($progres->nonlit_today) ? $progres->nonlit_today : 0; ?> Log Aktifitas
                    </div>
                    <div class="col-span-3 text-center font-black text-slate-800">
                        <?= isset($progres->nonlit_total) ? $progres->nonlit_total : 0; ?> Log Aktifitas
                    </div>
                </summary>

                <!-- Konten Rincian User (Mekar ke bawah saat diklik) -->
                <div class="p-4 bg-slate-50/80 border-t border-slate-200/60 space-y-2 animate-in fade-in duration-200">
                    <div class="font-bold text-slate-700 uppercase text-[11px] mb-2 px-2 flex justify-between">
                        <span>Daftar Seluruh Petugas (Non-Litigasi)</span>
                        <span>Capaian Hari Ini / Total</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        <?php if (!empty($progres->user_nonlit)): ?>
                            <?php foreach ($progres->user_nonlit as $u): ?>
                                <div class="flex justify-between items-center py-1.5 px-2 hover:bg-slate-50 rounded-lg text-slate-700">
                                    <div>
                                        <div class="font-bold uppercase text-slate-800"><?= htmlspecialchars($u->username); ?></div>
                                        <div class="text-[9px] opacity-50 uppercase font-semibold"><?= htmlspecialchars($u->role); ?></div>
                                    </div>
                                    <div class="text-right flex items-center gap-2">
                                        <span class="badge badge-sm font-bold <?= $u->scan_today > 0 ? 'badge-success text-white' : 'badge-ghost opacity-60'; ?>">
                                            +<?= $u->scan_today; ?>
                                        </span>
                                        <?php if ($u->scan_today > 0): ?>
                                            <button type="button"
                                                class="btn btn-xs btn-success btn-outline rounded-md btn-detail-user"
                                                data-userid="<?= $u->user_id; ?>"
                                                data-username="<?= htmlspecialchars($u->username); ?>"
                                                data-kategori="nonlit"
                                                data-tanggal="<?= $progres->tgl_laporan; ?>">
                                                Detail
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </details>

            <!-- Baris Total Keseluruhan -->
            <div class="grid grid-cols-12 p-4 font-bold text-slate-800 bg-slate-100/70 border-t-2 border-slate-200">
                <div class="col-span-6 text-right uppercase tracking-wider pr-4">TOTAL KESELURUHAN</div>
                <div class="col-span-3 text-center text-indigo-600 text-sm font-black">+<?= isset($progres->total_today) ? $progres->total_today : 0; ?> Data</div>
                <div class="col-span-3 text-center text-sm font-black text-slate-900"><?= isset($progres->grand_total) ? $progres->grand_total : 0; ?> Data</div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== MODAL POP-UP DETAIL USER ==================== -->
<dialog id="modal_detail_user_upload" class="modal">
    <div class="modal-box w-11/12 max-w-4xl bg-white rounded-2xl shadow-2xl p-6">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
            <div>
                <h3 class="font-black text-slate-800 uppercase text-base flex items-center gap-2">
                    <i class="mdi mdi-file-document-multiple text-indigo-600 text-xl"></i>
                    Rincian Berkas Upload: <span id="modal_username" class="text-indigo-600"></span>
                </h3>
                <p class="text-xs text-slate-400 font-semibold mt-0.5">Daftar berkas yang di-scan / di-update pada <span id="modal_tanggal"></span></p>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>

        <!-- Container Konten Detail via AJAX -->
        <div id="modal_detail_body" class="py-2">
            <div class="flex justify-center items-center py-8">
                <span class="loading loading-spinner loading-md text-primary"></span>
            </div>
        </div>

        <div class="modal-action border-t border-slate-100 pt-3 mt-4">
            <form method="dialog">
                <button class="btn btn-sm btn-neutral rounded-xl">Tutup</button>
            </form>
        </div>
    </div>
</dialog>

<!-- ==================== SCRIPT AJAX MODAL ==================== -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const detailButtons = document.querySelectorAll('.btn-detail-user');
        const modal = document.getElementById('modal_detail_user_upload');
        const modalUsername = document.getElementById('modal_username');
        const modalTanggal = document.getElementById('modal_tanggal');
        const modalBody = document.getElementById('modal_detail_body');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-userid');
                const username = this.getAttribute('data-username');
                const kategori = this.getAttribute('data-kategori');
                const tanggal = this.getAttribute('data-tanggal');

                modalUsername.textContent = username.toUpperCase();
                modalTanggal.textContent = tanggal;
                modalBody.innerHTML = '<div class="flex justify-center items-center py-8"><span class="loading loading-spinner loading-md text-primary"></span></div>';

                modal.showModal();

                // Fetch detail via AJAX
                fetch('<?= base_url("laporan/get_detail_user_upload"); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: `user_id=${userId}&tanggal=${tanggal}&kategori=${kategori}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        modalBody.innerHTML = data;
                    })
                    .catch(error => {
                        modalBody.innerHTML = '<div class="alert alert-error text-xs font-bold text-white">Gagal memuat rincian data.</div>';
                    });
            });
        });
    });
</script>