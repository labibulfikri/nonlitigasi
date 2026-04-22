<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Berkas Publik - Digital Arsip BPKAD</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#4f46e5 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            background-opacity: 0.05;
        }
    </style>
</head>

<body class="bg-pattern min-h-screen antialiased">

    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-500/5 blur-[120px]"></div>
    </div>

    <div class="max-w-3xl mx-auto p-6 md:p-12">
        <div class="text-center mb-12">
            <div class="inline-flex p-4 bg-white shadow-xl shadow-primary/10 rounded-[2rem] text-primary mb-6 ring-1 ring-slate-100">
                <i class="mdi mdi-shield-lock-outline text-5xl"></i>
            </div>
            <h1 class="text-3xl font-black italic tracking-tighter uppercase text-slate-900 leading-none">
                Akses Berkas <span class="text-primary">Terbatas</span>
            </h1>
            <div class="flex items-center justify-center gap-3 mt-4">
                <span class="h-[1px] w-8 bg-slate-200"></span>
                <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-[0.3em]">Secure Encrypted Access</p>
                <span class="h-[1px] w-8 bg-slate-200"></span>
            </div>
        </div>

        <div class="card glass-card shadow-2xl rounded-[2.5rem] overflow-hidden mb-8 border border-white">
            <div class="p-8 md:p-10">
                
                <div class="flex flex-col md:flex-row gap-6 mb-10 bg-white/50 p-6 rounded-3xl border border-slate-100 shadow-inner text-center md:text-left">
                    <div class="flex-1">
                        <p class="text-[9px] font-black uppercase text-primary tracking-widest mb-1">Klasifikasi Sumber</p>
                        <h2 class="text-xl font-black italic text-slate-800 uppercase leading-tight"><?= $sumber ?></h2>
                    </div>
                    <div class="divider md:divider-horizontal opacity-30"></div>
                    <div class="flex-1">
                        <p class="text-[9px] font-black uppercase text-primary tracking-widest mb-1">Identitas Berkas</p>
                        <h2 class="text-lg font-bold text-slate-700 uppercase leading-tight">
                            <?php
                                // Smart Mapping Judul Berdasarkan Sumber
                                if ($sumber === 'NONLIT') echo $result['data']->permohonan_nonlit ?? '-';
                                elseif ($sumber === 'POLISI') echo $result['data']->judul_laporan_polisi ?? '-';
                                elseif ($sumber === 'ASING') echo $result['data']->perkara_no ?? '-';
                                elseif ($sumber === 'UMUM') echo $result['data']->nama_berkas_umum ?? '-';
                            ?>
                        </h2>
                    </div>
                </div>

                <?php if (!empty($result['detail_tambahan'])): ?>
                    <div class="mb-10 text-left">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            <h4 class="text-xs font-black uppercase text-slate-800 tracking-widest">Riwayat & Progres Perkara</h4>
                        </div>
                        
                        <div class="space-y-4">
                            <?php foreach ($result['detail_tambahan'] as $det): ?>
                                <div class="collapse collapse-arrow bg-white border border-slate-100 rounded-[1.5rem] shadow-sm">
                                    <input type="checkbox" /> 
                                    <div class="collapse-title text-xs font-bold uppercase text-slate-600 p-5 flex items-center gap-4">
                                        <span class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-primary">
                                            <i class="mdi mdi-calendar-clock"></i>
                                        </span>
                                        <div>
                                            <?= $sumber === 'ASING' ? ($det->perkaradet_tingkat ?? 'Detail') : ($det->judul_rapat ?? 'Detail Informasi') ?>
                                            <div class="text-[9px] opacity-40 font-mono italic">
                                                <?= $det->perkaradet_tgl_putusan ?? $det->tgl_rapat ?? '' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse-content bg-slate-50/50 p-6 rounded-b-[1.5rem]">
                                        <div class="text-[11px] leading-relaxed text-slate-500 font-medium bg-white p-4 rounded-xl shadow-inner italic border border-slate-100">
                                            "<?= $det->perkaradet_keterangan ?? $det->kesimpulan ?? 'Tidak ada keterangan tambahan.' ?>"
                                        </div>
                                        <?php if ($sumber === 'NONLIT' && !empty($det->berkas)): ?>
                                            <div class="flex justify-end mt-4">
                                                <a href="<?= base_url('assets/berkas_lampiran/' . $det->berkas) ?>" target="_blank" 
                                                   class="btn btn-xs btn-primary rounded-lg px-4 font-black italic uppercase">
                                                    <i class="mdi mdi-download mr-1 text-sm"></i> Berkas Rapat
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-left">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                        <h4 class="text-xs font-black uppercase text-slate-800 tracking-widest">Lampiran Dokumen Terverifikasi</h4>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <?php if (!empty($result['lampiran'])): ?>
                            <?php foreach ($result['lampiran'] as $file): ?>
                                <?php
                                    // Deteksi Field Nama File Secara Dinamis
                                    $fileName = $file->nama_berkas ?? $file->berkas_laporan ?? $file->nama_file ?? $file->berkas ?? 'Dokumen_Tanpa_Nama.pdf';
                                    
                                    // Set Path Berdasarkan Sumber
                                    if($sumber === 'ASING') {
                                        $filePath = 'assets/upload/'; // Sesuaikan folder t_upload anda
                                    } elseif($sumber === 'NONLIT') {
                                        $filePath = 'assets/berkas_nonlit/';
                                    } else {
                                        $filePath = 'assets/upload/';
                                    }
                                ?>
                                <div class="flex items-center p-5 bg-white rounded-[1.8rem] border border-slate-100 hover:border-primary transition-all group shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300">
                                    <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                                        <i class="mdi mdi-file-pdf-box text-3xl"></i>
                                    </div>
                                    <div class="flex-1 ml-5 overflow-hidden">
                                        <p class="text-[12px] font-black uppercase text-slate-700 truncate tracking-tight"><?= $fileName ?></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="badge badge-ghost text-[8px] font-black uppercase px-2 py-1 rounded">Secured PDF</span>
                                            <span class="text-[8px] font-bold text-slate-300 uppercase italic">Digital Archive</span>
                                        </div>
                                    </div>
                                    <a href="<?= base_url($filePath . $fileName) ?>" target="_blank" 
                                       class="btn btn-sm btn-primary rounded-2xl px-6 font-black italic uppercase shadow-lg shadow-primary/20">
                                        <i class="mdi mdi-eye-outline text-lg"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="py-16 text-center border-2 border-dashed border-slate-100 rounded-[2.5rem] bg-slate-50/50 opacity-40 italic">
                                <i class="mdi mdi-folder-open-outline text-5xl"></i>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] mt-4">Belum ada lampiran tersedia</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center space-y-4 mt-12 pb-10">
            <p class="text-[9px] font-black uppercase text-slate-400 tracking-[0.4em]">&copy; 2026 DIGITAL ARSIP &bull; PEMKOT SURABAYA</p>
            <div class="inline-flex items-center gap-3 bg-red-50 text-red-500 px-6 py-2 rounded-full border border-red-100 shadow-sm">
                <i class="mdi mdi-clock-alert-outline text-sm animate-pulse"></i>
                <span class="text-[8px] font-black uppercase italic tracking-widest leading-none">Akses ini bersifat rahasia dan terbatas waktu</span>
            </div>
        </div>
    </div>

</body>
</html>