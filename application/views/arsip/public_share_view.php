<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Berkas Publik - Digital Arsip</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
</head>

<body class="bg-slate-50 min-h-screen">

    <div class="max-w-3xl mx-auto p-4 md:p-10">
        <div class="text-center mb-10">
            <div class="inline-flex p-3 bg-primary/10 rounded-2xl text-primary mb-4">
                <i class="mdi mdi-folder-key-network text-4xl"></i>
            </div>
            <h1 class="text-2xl font-black italic tracking-tighter uppercase text-slate-800">Akses Berkas Terbatas</h1>
            <p class="text-[10px] font-bold opacity-50 uppercase tracking-[0.3em]">Dokumen ini dibagikan secara aman via Link Terenkripsi</p>
        </div>

        <div class="card bg-white shadow-xl border border-slate-200 overflow-hidden rounded-3xl mb-6">
            <div class="p-6 md:p-8">
                <div class="stats stats-vertical md:stats-horizontal shadow-sm bg-slate-50 border border-slate-100 w-full mb-8 rounded-2xl">
                    <div class="stat p-6">
                        <div class="stat-title text-[10px] font-black uppercase text-primary tracking-widest">Jenis Berkas</div>
                        <div class="stat-value text-xl uppercase text-slate-700"><?= $sumber ?></div>
                        <div class="stat-desc font-bold opacity-50 uppercase mt-1 italic">Status: Aktif</div>
                    </div>
                    <div class="stat p-6">
                        <div class="stat-title text-[10px] font-black uppercase text-primary tracking-widest">Identitas Berkas</div>
                        <div class="stat-value text-lg text-slate-700 uppercase">
                            <?php
                            if ($sumber === 'UMUM') echo $result['data']->nama_berkas_umum;
                            elseif ($sumber === 'POLISI') echo $result['data']->judul_laporan_polisi;
                            elseif ($sumber === 'NONLIT') echo $result['data']->permohonan_nonlit;
                            elseif ($sumber === 'ASING') echo $result['data']->perkara_no;
                            ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($result['detail_tambahan'])): ?>
                    <h4 class="text-[10px] font-black uppercase opacity-40 mb-4 tracking-[0.2em] border-l-4 border-primary pl-3">Riwayat & Keterangan</h4>
                    <div class="space-y-3 mb-8">
                        <?php foreach ($result['detail_tambahan'] as $det): ?>
                            <div class="collapse collapse-arrow bg-slate-50 border border-slate-200 rounded-2xl">
                                <input type="checkbox" />
                                <div class="collapse-title text-xs font-black uppercase text-slate-600 p-4">
                                    <?= $sumber === 'ASING' ? $det->perkaradet_tingkat : ($det->judul_rapat ?? 'Detail Informasi') ?>
                                    <span class="opacity-40 ml-2 font-mono text-[9px]"><?= $det->tgl_rapat ?? $det->perkaradet_tgl_putusan ?? '' ?></span>
                                </div>
                                <div class="collapse-content bg-white/50 border-t border-slate-100 p-4">
                                    <div class="text-xs italic text-slate-500 mb-4">
                                        <?= $det->kesimpulan ?? $det->perkaradet_keterangan ?? 'Tidak ada deskripsi.' ?>
                                    </div>

                                    <?php if ($sumber === 'NONLIT' && !empty($det->berkas)): ?>
                                        <div class="flex justify-end pt-2">
                                            <a href="<?= base_url('assets/berkas_nonlit/' . $det->berkas) ?>" target="_blank" class="btn btn-xs btn-primary font-black italic uppercase rounded-lg px-4">
                                                <i class="mdi mdi-download mr-1"></i> Unduh Berkas Rapat
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h4 class="text-[10px] font-black uppercase opacity-40 mb-4 tracking-[0.2em] border-l-4 border-primary pl-3">Lampiran Dokumen Digital</h4>
                <div class="grid grid-cols-1 gap-3">
                    <?php if (!empty($result['lampiran'])): ?>
                        <?php foreach ($result['lampiran'] as $file): ?>
                            <?php
                            $fileName = $file->nama_file ?? $file->nama_berkas ?? $file->berkas_laporan ?? $file->name_berkas;
                            $filePath = ($sumber === 'UMUM') ? 'assets/berkas_umum/detail/' : ($sumber === 'NONLIT' ? 'assets/berkas_nonlit/' : 'assets/upload/');
                            ?>
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-200 hover:border-primary hover:bg-white transition-all group shadow-sm">
                                <div class="p-3 bg-white rounded-xl text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                                    <i class="mdi mdi-file-document-outline text-2xl"></i>
                                </div>
                                <div class="flex-1 ml-4 overflow-hidden">
                                    <p class="text-[11px] font-black uppercase text-slate-700 truncate"><?= $fileName ?></p>
                                    <p class="text-[9px] font-bold opacity-40 uppercase">Dokumen Terverifikasi</p>
                                </div>
                                <a href="<?= base_url($filePath . $fileName) ?>" target="_blank" class="btn btn-sm btn-primary rounded-xl px-5 font-black italic uppercase">
                                    Unduh
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-10 text-center border-2 border-dashed border-slate-200 rounded-3xl opacity-30 italic text-xs font-bold uppercase">
                            Tidak ada berkas digital tersedia
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="text-center opacity-30 mt-10">
            <p class="text-[9px] font-bold uppercase tracking-widest">&copy; 2026 DIGITAL ARSIP SYSTEM - SURABAYA</p>
            <p class="text-[8px] italic mt-1 uppercase font-black">Link ini bersifat rahasia dan memiliki batas waktu akses</p>
        </div>
    </div>

</body>

</html>