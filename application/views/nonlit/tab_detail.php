<div class="p-2 text-base-content">
    <div class="flex items-center gap-2 mb-6 border-b border-base-300 pb-2">
        <div class="w-2 h-8 bg-primary rounded-full"></div>
        <h3 class="font-black text-lg uppercase tracking-wider">Informasi Master</h3>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="stats shadow bg-base-200 border border-base-300 flex-1">
                <div class="stat p-4">
                    <div class="stat-title text-[10px] font-bold uppercase text-primary">No. Rak</div>
                    <div class="stat-value text-xl font-black"><?= $master['penyimpanan_rak'] ?></div>
                    <div class="stat-desc text-[10px] italic opacity-70">Lokasi Arsip Fisik</div>
                </div>
            </div>
            <div class="stats shadow bg-base-200 border border-base-300 flex-1">
                <div class="stat p-4">
                    <div class="stat-title text-[10px] font-bold uppercase opacity-60">No. Register</div>
                    <div class="stat-value text-xl font-black"><?= $master['register_baru'] ?></div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-4">
                <label class="text-[10px] font-bold uppercase opacity-50 tracking-widest">Permohonan Non-Litigasi</label>
                <p class="font-bold leading-tight uppercase text-primary"><?= $master['permohonan_nonlit'] ?></p>
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
                <span class="font-black text-base-content"><?= $master['team_nonlit'] ?></span>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-base-200 rounded-2xl border border-base-300">
            <input type="checkbox" checked />
            <div class="collapse-title text-xs font-bold uppercase opacity-60">
                Keterangan Detail
            </div>
            <div class="collapse-content text-sm leading-relaxed opacity-80">
                <p><?= nl2br($master['keterangan']) ?></p>
            </div>
        </div>
    </div>
</div>