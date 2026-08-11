<div class="min-h-screen bg-slate-50 p-4 md:p-8 font-sans">

    <div class="max-w-7xl mx-auto mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 class="text-xl font-black text-slate-800 uppercase italic">🏆 Ranking Penanganan Project</h1>

        <form action="<?= base_url('laporan_pic') ?>" method="GET" class="flex items-center gap-2">
            <select name="tahun" class="bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Semua Tahun</option>
                <?php
                $thn_skrg = date('Y');
                for ($i = $thn_skrg; $i >= 2020; $i--): ?>
                    <option value="<?= $i ?>" <?= ($this->input->get('tahun') == $i) ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="bg-slate-800 text-white text-[10px] font-black px-4 py-2 rounded-lg uppercase hover:bg-black transition">
                Filter
            </button>
        </form>
    </div>

    <div class="p-6 bg-slate-50 min-h-screen">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800 text-white text-[10px] uppercase tracking-widest">
                        <th class="px-4 py-4 text-center w-16">Rank</th>
                        <th class="px-6 py-4">PIC Pegawai</th>
                        <th class="px-4 py-4 text-center border-l border-slate-700">Non-Litigasi</th>
                        <th class="px-4 py-4 text-center border-l border-slate-700">Lap. Polisi</th>
                        <th class="px-4 py-4 text-center border-l border-slate-700">Permasalahan</th>
                        <th class="px-4 py-4 text-center border-l border-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($list_pic as $index => $row): ?>
                        <tr class="hover:bg-slate-50 transition-colors align-top">
                            <td class="px-4 py-6 text-center font-black text-slate-300 italic text-lg">#<?= $index + 1 ?></td>
                            <td class="px-6 py-6">
                                <p class="font-black text-slate-800 uppercase text-sm"><?= $row['nama_lengkap'] ?></p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">ID: #<?= $row['id'] ?></p>
                            </td>

                            <td class="px-2 py-6 text-center bg-blue-50/20">
                                <details class="group">
                                    <summary class="list-none cursor-pointer">
                                        <span class="text-xl font-black text-blue-600 block"><?= $row['total_nonlit'] ?></span>
                                        <span class="text-[9px] font-bold text-blue-400 uppercase group-open:hidden tracking-tighter">Klik Detail</span>
                                    </summary>
                                    <div class="mt-3 text-left px-3 py-2 bg-white border border-blue-100 rounded-lg shadow-inner max-w-xs mx-auto">
                                        <?php if ($row['list_nonlit']):
                                            foreach (explode('|', $row['list_nonlit']) as $item): ?>
                                                <p class="text-[10px] text-slate-600 border-b border-slate-50 py-1 uppercase font-medium leading-tight last:border-0">• <?= $item ?></p>
                                        <?php endforeach;
                                        else: echo "<p class='text-[10px] italic text-slate-400'>Kosong</p>";
                                        endif; ?>
                                    </div>
                                </details>
                            </td>

                            <td class="px-2 py-6 text-center bg-red-50/20">
                                <details class="group">
                                    <summary class="list-none cursor-pointer">
                                        <span class="text-xl font-black text-red-600 block"><?= $row['total_lp'] ?></span>
                                        <span class="text-[9px] font-bold text-red-400 uppercase group-open:hidden tracking-tighter">Klik Detail</span>
                                    </summary>
                                    <div class="mt-3 text-left px-3 py-2 bg-white border border-red-100 rounded-lg shadow-inner max-w-xs mx-auto">
                                        <?php if ($row['list_lp']):
                                            foreach (explode('|', $row['list_lp']) as $item): ?>
                                                <p class="text-[10px] text-slate-600 border-b border-slate-50 py-1 uppercase font-medium leading-tight last:border-0">• <?= $item ?></p>
                                        <?php endforeach;
                                        else: echo "<p class='text-[10px] italic text-slate-400'>Kosong</p>";
                                        endif; ?>
                                    </div>
                                </details>
                            </td>

                            <td class="px-2 py-6 text-center bg-amber-50/20">
                                <details class="group">
                                    <summary class="list-none cursor-pointer">
                                        <span class="text-xl font-black text-amber-600 block"><?= $row['total_masalah'] ?></span>
                                        <span class="text-[9px] font-bold text-amber-400 uppercase group-open:hidden tracking-tighter">Klik Detail</span>
                                    </summary>
                                    <div class="mt-3 text-left px-3 py-2 bg-white border border-amber-100 rounded-lg shadow-inner max-w-xs mx-auto">
                                        <?php if ($row['list_masalah']):
                                            foreach (explode('|', $row['list_masalah']) as $item): ?>
                                                <p class="text-[10px] text-slate-600 border-b border-slate-50 py-1 uppercase font-medium leading-tight last:border-0">• <?= $item ?></p>
                                        <?php endforeach;
                                        else: echo "<p class='text-[10px] italic text-slate-400'>Kosong</p>";
                                        endif; ?>
                                    </div>
                                </details>
                            </td>

                            <td class="px-4 py-6 text-center">
                                <a href="<?= base_url('laporan_pic/detail/' . encrypt_url($row['id'])) ?>"
                                    class="bg-slate-800 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase shadow-lg shadow-slate-200 hover:bg-black transition">
                                    Profil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



</div>

<style>
    /* Mengatur agar teks judul panjang otomatis turun ke bawah (wrap) */
    .break-words {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    @media print {
        .no-print {
            display: none;
        }

        body {
            background: white;
            padding: 0;
        }

        .shadow-sm,
        .shadow-md,
        .shadow-lg {
            shadow: none !important;
            border: 1px solid #eee;
        }
    }
</style>