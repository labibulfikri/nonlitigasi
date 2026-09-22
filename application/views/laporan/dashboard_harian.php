<div class="p-6 space-y-6 bg-slate-50 min-h-screen">
    <!-- Header & Filter Form -->
    <div class="flex flex-wrap justify-between items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge badge-primary badge-sm font-bold uppercase">Audit Trail</span>
                <span class="text-xs text-slate-400 font-semibold">Tanggal: <?= date('d F Y', strtotime($tanggal)); ?></span>
            </div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mt-1">Dashboard Log Aktivitas Sistem</h2>
            <p class="text-xs text-slate-500">Pemantauan seluruh aksi (Tambah, Edit, Hapus) yang dilakukan petugas harian</p>
        </div>

        <form method="GET" action="<?= base_url('laporan/dashboard_harian'); ?>" class="flex flex-wrap items-center gap-2">
            <input type="date" name="tanggal" value="<?= $tanggal; ?>" class="input input-sm input-bordered rounded-xl text-xs font-bold" />

            <select name="user_id" class="select select-sm select-bordered rounded-xl text-xs font-bold">
                <option value="">-- Semua Petugas --</option>
                <?php foreach ($list_user as $usr): ?>
                    <option value="<?= $usr->id; ?>" <?= ($selected_user == $usr->id) ? 'selected' : ''; ?>>
                        <?= strtoupper($usr->username); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="action" class="select select-sm select-bordered rounded-xl text-xs font-bold">
                <option value="">-- Semua Aksi --</option>
                <option value="CREATE" <?= ($selected_act == 'CREATE') ? 'selected' : ''; ?>>CREATE (Tambah)</option>
                <option value="UPDATE" <?= ($selected_act == 'UPDATE') ? 'selected' : ''; ?>>UPDATE (Edit)</option>
                <option value="DELETE" <?= ($selected_act == 'DELETE') ? 'selected' : ''; ?>>DELETE (Hapus)</option>
            </select>

            <button type="submit" class="btn btn-sm btn-primary text-white rounded-xl">
                <i class="mdi mdi-filter"></i> Filter
            </button>
            <a href="<?= base_url('laporan_log'); ?>" class="btn btn-sm btn-ghost rounded-xl">Reset</a>
        </form>
    </div>

    <!-- Ringkasan Kartu Log Harian -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-xl">
                <i class="mdi mdi-history text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-slate-400">Total Aktivitas</span>
                <h3 class="text-2xl font-black text-slate-800"><?= isset($stats->total_log) ? $stats->total_log : 0; ?> <span class="text-xs text-slate-400 font-normal">Aksi</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl">
                <i class="mdi mdi-plus-box text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-slate-400">Tambah Data (CREATE)</span>
                <h3 class="text-2xl font-black text-slate-800"><?= isset($stats->total_create) ? $stats->total_create : 0; ?> <span class="text-xs text-slate-400 font-normal">Data</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="bg-amber-100 text-amber-600 p-3 rounded-xl">
                <i class="mdi mdi-file-document-edit text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-slate-400">Pembaruan (UPDATE)</span>
                <h3 class="text-2xl font-black text-slate-800"><?= isset($stats->total_update) ? $stats->total_update : 0; ?> <span class="text-xs text-slate-400 font-normal">Aksi</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="bg-rose-100 text-rose-600 p-3 rounded-xl">
                <i class="mdi mdi-delete-alert text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-slate-400">Penghapusan (DELETE)</span>
                <h3 class="text-2xl font-black text-slate-800"><?= isset($stats->total_delete) ? $stats->total_delete : 0; ?> <span class="text-xs text-slate-400 font-normal">Aksi</span></h3>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Log Aktivitas -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 uppercase text-sm tracking-wider flex items-center gap-2">
                <i class="mdi mdi-format-list-bulleted text-primary text-lg"></i>
                Rincian Log Aktivitas Tanggal: <span class="text-primary"><?= date('d F Y', strtotime($tanggal)); ?></span>
            </h3>
            <span class="badge badge-neutral badge-sm uppercase font-bold"><?= count($logs); ?> Baris Log</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                        <th class="w-12">No</th>
                        <th>Waktu</th>
                        <th>Petugas / User</th>
                        <th class="text-center">Aksi</th>
                        <th>Modul Tabel</th>
                        <th>ID Record</th>
                        <th>Keterangan Aktivitas</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 font-bold uppercase">
                                Tidak ada catatan log aktivitas pada tanggal ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($logs as $row): ?>
                            <tr class="hover:bg-slate-50/50">
                                <td class="font-bold text-slate-400"><?= $no++; ?></td>
                                <td class="font-mono font-bold text-slate-700">
                                    <?= date('H:i:s', strtotime($row->created_at)); ?> WIB
                                </td>
                                <td>
                                    <div class="font-black text-slate-800 uppercase"><?= htmlspecialchars($row->username ?: 'User #' . $row->user_id); ?></div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase"><?= htmlspecialchars($row->role ?: '-'); ?></div>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $badge = 'badge-ghost';
                                    if ($row->action == 'CREATE') $badge = 'badge-success text-white font-bold';
                                    else if ($row->action == 'UPDATE') $badge = 'badge-warning text-white font-bold';
                                    else if ($row->action == 'DELETE') $badge = 'badge-error text-white font-bold';
                                    ?>
                                    <span class="badge badge-sm <?= $badge; ?>"><?= $row->action; ?></span>
                                </td>
                                <td class="font-mono font-bold text-slate-600 uppercase"><?= htmlspecialchars($row->module); ?></td>
                                <td class="font-bold text-indigo-600">#<?= htmlspecialchars($row->record_id); ?></td>
                                <td class="text-slate-700 max-w-xs truncate" title="<?= htmlspecialchars($row->description); ?>">
                                    <?= htmlspecialchars($row->description); ?>
                                </td>
                                <td class="font-mono text-[10px] text-slate-400"><?= htmlspecialchars($row->ip_address); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>