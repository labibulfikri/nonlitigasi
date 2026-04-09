<!doctype html>
<html lang="id" data-theme="light">

<head>
    <title>Login E-Nonlit | Surabaya City</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fcfcfd;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Area Form */
        .left-side {
            width: 40%;
            background-color: #ffffff;
            overflow-y: auto;
            height: 100vh;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #f1f5f9;
        }

        /* Area Branding (Clean Orange Amber) */
        .right-side {
            width: 60%;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .input-focus-amber:focus {
            border-color: #f59e0b !important;
            outline: 2px solid rgba(245, 158, 11, 0.1);
        }

        @keyframes subtle-float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(1deg);
            }
        }

        .animate-subtle {
            animation: subtle-float 8s ease-in-out infinite;
        }

        /* Styling Quick Action Buttons */
        .btn-quick-action {
            @apply flex flex-col items-center justify-center gap-2 p-4 rounded-[1.5rem] border border-slate-100 bg-white transition-all duration-300 hover:border-amber-400 hover:bg-amber-50 group shadow-sm;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .login-container {
                flex-direction: column-reverse;
            }

            .left-side,
            .right-side {
                width: 100%;
                height: auto;
                position: relative;
            }

            .right-side {
                height: 350px;
                border-bottom: 1px solid #f1f5f9;
            }
        }
    </style>
</head>

<body class="antialiased">

    <div class="login-container">

        <div class="left-side">
            <div class="m-auto w-full max-w-[420px] p-8 md:p-12">

                <div class="mb-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em]">Pemerintah Kota Surabaya</span>
                    </div>
                    <h2 class="text-4xl font-extrabold text-slate-800 mb-2 tracking-tight italic">Sign In</h2>
                    <p class="text-slate-400 text-sm font-medium">Panel Kontrol Manajemen Sengketa Di Lingkungan Pemerintah Kota Surabaya.</p>
                </div>

                <?php if ($this->session->userdata('pesan')): ?>
                    <div class="alert bg-rose-50 border-none text-rose-700 rounded-2xl mb-8 flex items-center gap-3">
                        <i class="mdi mdi-alert-circle text-xl"></i>
                        <span class="text-xs font-bold uppercase tracking-wide"><?php echo $this->session->userdata('pesan') ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo base_url('auth/check_captcha') ?>" class="space-y-4">
                    <?= crsf() ?>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-black text-slate-400 uppercase text-[10px] tracking-widest">Username</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-4 flex items-center text-slate-300">
                                <i class="mdi mdi-account-circle-outline text-2xl"></i>
                            </span>
                            <input type="text" name="username" placeholder="Username"
                                class="input input-bordered w-full h-14 pl-12 bg-slate-50 border-slate-100 rounded-2xl font-semibold text-slate-700 input-focus-amber transition-all" required />
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-black text-slate-400 uppercase text-[10px] tracking-widest">Password</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-4 flex items-center text-slate-300">
                                <i class="mdi mdi-lock-outline text-2xl"></i>
                            </span>
                            <input type="password" id="password" name="password" placeholder="••••••••"
                                class="input input-bordered w-full h-14 px-12 bg-slate-50 border-slate-100 rounded-2xl font-semibold text-slate-700 input-focus-amber transition-all" required />

                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-4 flex items-center text-slate-300 hover:text-amber-500 transition-colors">
                                <i id="eye-icon" class="mdi mdi-eye-off-outline text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 mt-6">
                        <div class="flex items-center justify-between mb-4 gap-4">
                            <div id="captcha-img" class="bg-white p-2 rounded-xl border border-slate-100 flex-grow text-center overflow-hidden h-12 flex items-center justify-center grayscale opacity-70">
                                <?php echo $image; ?>
                            </div>
                            <button id="btn_cap" type="button" class="btn btn-square btn-ghost bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-xl">
                                <i class="mdi mdi-refresh text-xl"></i>
                            </button>
                        </div>
                        <input type="text" name="captcha" placeholder="Masukan Kode"
                            class="input input-bordered w-full text-center h-12 tracking-[0.4em] font-black border-slate-100 rounded-xl uppercase text-sm input-focus-amber" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-block bg-amber-500 hover:bg-amber-600 text-white border-none shadow-lg shadow-amber-200 h-14 rounded-2xl text-xs font-black italic tracking-widest uppercase">
                            Sign In System <i class="mdi mdi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-12">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="h-px bg-slate-100 flex-1"></span>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Akses Cepat</span>
                        <span class="h-px bg-slate-100 flex-1"></span>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <a href="https://assistdpbt.surabaya.go.id/asing" target="_blank" class="btn-quick-action group">
                            <i class="mdi mdi-earth text-2xl text-slate-300 group-hover:text-amber-500 transition-colors"></i>
                            <span class="text-[9px] font-black text-slate-400 group-hover:text-amber-700 uppercase tracking-tighter">Asing</span>
                        </a>

                        <a href="https://assistdpbt.surabaya.go.id/" target="_blank" class="btn-quick-action group">
                            <i class="mdi mdi-certificate-outline text-2xl text-slate-300 group-hover:text-amber-500 transition-colors"></i>
                            <span class="text-[9px] font-black text-slate-400 group-hover:text-amber-700 uppercase tracking-tighter">Sertifikasi</span>
                        </a>

                        <a href="https://assistdpbt.surabaya.go.id/rak" target="_blank" class="btn-quick-action group">
                            <i class="mdi mdi-archive-search-outline text-2xl text-slate-300 group-hover:text-amber-500 transition-colors"></i>
                            <span class="text-[9px] font-black text-slate-400 group-hover:text-amber-700 uppercase tracking-tighter">Cek Rak</span>
                        </a>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50 text-center opacity-40">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">
                        BPKAD Kota Surabaya &copy; 2026
                    </p>
                </div>
            </div>
        </div>

        <div class="right-side lg:flex overflow-hidden">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(#f59e0b 1px, transparent 1px); background-size: 32px 32px;"></div>

            <div class="relative z-10 text-center">
                <div class="animate-subtle">
                    <div class="bg-white/40 backdrop-blur-md border border-white p-12 rounded-[4rem] shadow-sm inline-block mb-12">
                        <i class="mdi mdi-shield-check text-amber-500 text-[120px] leading-none"></i>
                    </div>
                </div>
                <h1 class="text-7xl font-black text-slate-800 tracking-tighter mb-2 italic uppercase leading-none">E-<span class="text-amber-500">Nonlit</span></h1>
                <p class="text-amber-600/60 text-xs font-black tracking-[0.5em] uppercase mb-12 leading-none">Database Terpadu Surabaya</p>

                <div class="flex justify-center gap-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Integritas</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Profesional</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Modern</p>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo base_url() ?>assets/login/js/jquery.min.js"></script>
    <script>
        // FUNGSI TOGGLE PASSWORD
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('mdi-eye-off-outline', 'mdi-eye-outline');
            } else {
                pwd.type = 'password';
                icon.classList.replace('mdi-eye-outline', 'mdi-eye-off-outline');
            }
        }

        $(document).ready(function() {
            $("#btn_cap").click(function() {
                const icon = $(this).find('i');
                icon.addClass('mdi-spin');
                $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: "<?php echo base_url('auth/reload_captcha') ?>",
                    success: function(data) {
                        $('#captcha-img').html(data);
                        icon.removeClass('mdi-spin');
                    }
                });
            });
        });
    </script>
</body>

</html>