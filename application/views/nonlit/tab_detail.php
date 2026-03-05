<div class="p-2">
    <div class="flex items-center gap-2 mb-6 border-b border-base-200 pb-2">
        <div class="w-2 h-8 bg-primary rounded-full"></div>
        <h3 class="font-black text-lg uppercase tracking-wider text-slate-700">Informasi Master</h3>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="stats shadow bg-blue-50 border border-blue-100 flex-1">
                <div class="stat p-4">
                    <div class="stat-title text-[10px] font-bold uppercase text-blue-600">No. Rak</div>
                    <div class="stat-value text-xl text-slate-800"><?= $master['penyimpanan_rak'] ?></div>
                    <div class="stat-desc text-[10px] italic">Lokasi Arsip Fisik</div>
                </div>
            </div>
            <div class="stats shadow bg-slate-50 border border-base-200 flex-1">
                <div class="stat p-4">
                    <div class="stat-title text-[10px] font-bold uppercase text-slate-500">No. Register</div>
                    <div class="stat-value text-xl text-slate-800"><?= $master['register_baru'] ?></div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-4">
                <label class="text-[10px] font-bold uppercase text-slate-500 tracking-widest">Permohonan Non-Litigasi</label>
                <p class="font-bold text-slate-800 leading-tight uppercase"><?= $master['permohonan_nonlit'] ?></p>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-primary/10 p-4 rounded-2xl border border-primary/20">
            <div class="avatar placeholder">
                <div class="bg-primary text-primary-content rounded-full w-10">
                    <span class="text-xs font-bold"><?= substr($master['team_nonlit'], 0, 2) ?></span>
                </div>
            </div>
            <div>
                <label class="text-[9px] font-bold uppercase text-primary block">Team Pelaksana</label>
                <span class="font-black text-slate-700"><?= $master['team_nonlit'] ?></span>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-base-200/50 rounded-2xl border border-base-300">
            <input type="checkbox" checked /> 
            <div class="collapse-title text-xs font-bold uppercase text-slate-500">
                Keterangan Detail
            </div>
            <div class="collapse-content text-sm text-slate-600 leading-relaxed">
                <p><?= nl2br($master['keterangan']) ?></p>
            </div>
        </div>
    </div>
</div>