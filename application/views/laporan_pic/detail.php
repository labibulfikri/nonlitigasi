<div class="p-4 md:p-8 bg-slate-50 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <a href="<?= base_url('laporan_pic') ?>" class="text-blue-600 text-[10px] font-black uppercase tracking-widest hover:underline flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Ranking
                </a>
            </div>
            
            <form action="<?= base_url('laporan_pic/detail/'.$pic_id) ?>" method="GET" class="flex gap-2 w-full md:w-auto">
                <select name="tahun" class="bg-white border border-slate-200 text-[11px] font-bold rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500 flex-1 md:w-40">
                    <option value="">Semua Tahun</option>
                    <?php 
                    $thn_skrg = date('Y');
                    for($i = $thn_skrg; $i >= 2020; $i--): ?>
                        <option value="<?= $i ?>" <?= ($this->input->get('tahun') == $i) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="bg-slate-800 text-white text-[10px] font-black px-5 py-2 rounded-xl uppercase">Filter</button>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-200 overflow-hidden mb-10">
            <div class="bg-slate-900 p-8 md:p-12 flex flex-col lg:flex-row items-center gap-10">
                
                <div class="shrink-0 text-center">
                    <?php 
                        $clean_name = trim(preg_replace('/\s+/', ' ', $pic_name));
                        $words = explode(" ", $clean_name);
                        $initials = "";
                        foreach ($words as $w) {
                            if (!empty($w)) $initials .= strtoupper($w[0]);
                        }
                        $display_initials = substr($initials, 0, 2);
                        if (empty($display_initials)) $display_initials = "PIC";
                    ?>
                    <img src="<?= base_url('assets/img/users/'.$pic_id.'.jpg') ?>" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($display_initials) ?>&background=0D8ABC&color=fff&size=128&font-size=0.4'"
                         class="w-32 h-32 rounded-[2rem] object-cover border-4 border-slate-700 shadow-2xl mx-auto transform hover:scale-105 transition-transform duration-300">
                    <h1 class="text-2xl font-black text-white uppercase italic mt-5 tracking-tighter"><?= $pic_name ?></h1>
                    <span class="inline-block mt-1 px-3 py-1 bg-slate-800 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-slate-700">Person In Charge</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1 w-full">
                    <div class="bg-slate-800/50 p-5 rounded-3xl border-t-4 border-blue-500 backdrop-blur-sm">
                        <p class="text-[10px] font-black text-blue-400 uppercase mb-3 flex justify-between items-center">
                            Non-Litigasi <i class="fa-solid fa-gavel"></i>
                        </p>
                        <div class="flex justify-between items-end">
                            <div><p class="text-[9px] text-slate-500 uppercase font-bold">Proses</p><p class="text-2xl font-black text-white"><?= $stats['nonlit']['proses'] ?></p></div>
                            <div class="text-right"><p class="text-[9px] text-slate-500 uppercase font-bold">Selesai</p><p class="text-2xl font-black text-emerald-400"><?= $stats['nonlit']['selesai'] ?></p></div>
                        </div>
                    </div>
                    <div class="bg-slate-800/50 p-5 rounded-3xl border-t-4 border-red-500 backdrop-blur-sm">
                        <p class="text-[10px] font-black text-red-400 uppercase mb-3 flex justify-between items-center">
                            Laporan Polisi <i class="fa-solid fa-shield-halved"></i>
                        </p>
                        <div class="flex justify-between items-end">
                            <div><p class="text-[9px] text-slate-500 uppercase font-bold">Proses</p><p class="text-2xl font-black text-white"><?= $stats['lp']['proses'] ?></p></div>
                            <div class="text-right"><p class="text-[9px] text-slate-500 uppercase font-bold">Selesai</p><p class="text-2xl font-black text-emerald-400"><?= $stats['lp']['selesai'] ?></p></div>
                        </div>
                    </div>
                    <div class="bg-slate-800/50 p-5 rounded-3xl border-t-4 border-amber-500 backdrop-blur-sm">
                        <p class="text-[10px] font-black text-amber-400 uppercase mb-3 flex justify-between items-center">
                            Permasalahan <i class="fa-solid fa-circle-exclamation"></i>
                        </p>
                        <div class="flex justify-between items-end">
                            <div><p class="text-[9px] text-slate-500 uppercase font-bold">Proses</p><p class="text-2xl font-black text-white"><?= $stats['masalah']['proses'] ?></p></div>
                            <div class="text-right"><p class="text-[9px] text-slate-500 uppercase font-bold">Selesai</p><p class="text-2xl font-black text-emerald-400"><?= $stats['masalah']['selesai'] ?></p></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-10 bg-white">
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-6 w-1.5 bg-blue-600 rounded-full"></div>
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Daftar Rincian Pekerjaan Terdaftar</h2>
                </div>

                <div class="space-y-4">
    <?php foreach ($projects as $p): ?>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 flex items-center justify-between">
        <div class="flex-1 pr-6">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[9px] font-black px-2 py-0.5 rounded bg-blue-600 text-white uppercase">
                    <?= $p['jenis'] ?? 'N/A' ?>
                </span>
            </div>
            
            <h4 class="text-sm font-bold text-slate-800 uppercase break-words whitespace-normal">
                <?= $p['permohonan_nonlit'] ?? 'Judul Tidak Tersedia' ?>
            </h4>
            
            <p class="text-[11px] text-slate-400 mt-2 font-medium">
                No. Register : <?= $p['register_baru'] ?? '-' ?>
            </p>
        </div>

        <div class="flex items-center gap-4">
            <div class="bg-slate-50 px-4 py-2 rounded-xl text-center border border-slate-100">
                <p class="text-[8px] font-black text-slate-400 uppercase">Rapat</p>
                <p class="text-sm font-black text-slate-800"><?= $p['total_rapat'] ?? 0 ?></p>
            </div>
            
            <a href="<?= base_url('nonlit/detail/'.$p['id']) ?>" 
               class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-200">
                Detail
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Menangani judul yang sangat panjang agar tidak memanjang ke samping */
    .break-words {
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
    }
</style>