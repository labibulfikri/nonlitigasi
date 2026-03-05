<!doctype html>
<html lang="en" data-theme="light">

<head>
    <title>Login E-Nonlit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body, html { height: 100%; margin: 0; font-family: 'Lato', sans-serif; }

        .login-container { display: flex; min-height: 100vh; width: 100%; }

        /* Area Form yang bisa discroll secara independen */
        .left-side {
            width: 41.666667%; 
            background-color: #ffffff;
            overflow-y: auto; 
            height: 100vh;
            z-index: 20;
        }

        /* Area Gambar yang tetap diam */
        .right-side {
            width: 58.333333%;
            background-color: #0047AB;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .left-side::-webkit-scrollbar { width: 6px; }
        .left-side::-webkit-scrollbar-track { background: #f8fafc; }
        .left-side::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @media (max-width: 1024px) {
            .login-container { flex-direction: column-reverse; }
            .left-side, .right-side { width: 100%; height: auto; position: relative; }
            .left-side { overflow-y: visible; height: auto; }
            .right-side { height: 400px; }
        }
    </style>
</head>

<body>

    <div class="login-container">
        
        <div class="left-side">
            <div class="flex flex-col items-center p-8 md:p-16 lg:p-20 w-full min-h-full">
                <div class="max-w-md w-full my-auto py-10">
                    
                    <div class="mb-10">
                        <div class="badge badge-primary badge-outline mb-4 px-4 py-3 font-bold uppercase tracking-widest text-[10px]">Pemerintah Kota Surabaya</div>
                        <h2 class="text-5xl font-black text-slate-800 mb-2 tracking-tight">Login</h2>
                        <p class="text-slate-500 text-lg">Akses Sistem E-Nonlit</p>
                    </div>

                    <?php if($this->session->userdata('pesan')): ?>
                    <div class="alert alert-error shadow-lg mb-8 border-none bg-red-50 text-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-bold"><?php echo $this->session->userdata('pesan') ?></span>
                    </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo base_url('auth/check_captcha') ?>" class="space-y-6">
                        <?= crsf() ?>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold text-slate-700 uppercase text-xs tracking-wider">Username</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400"><i class="fa fa-user-circle-o text-lg"></i></span>
                                <input type="text" name="username" placeholder="Masukkan username" class="input input-bordered w-full pl-12 h-14 bg-slate-50 focus:bg-white border-slate-200 shadow-sm" required />
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold text-slate-700 uppercase text-xs tracking-wider">Password</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400"><i class="fa fa-lock text-lg"></i></span>
                                <input type="password" id="password" name="password" placeholder="••••••••" class="input input-bordered w-full px-12 h-14 bg-slate-50 focus:bg-white border-slate-200 shadow-sm" required />
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-all">
                                    <i id="eye-icon" class="fa fa-eye-slash text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-200 shadow-inner mt-8">
                            <div class="flex items-center justify-between mb-4 gap-4">
                                <div id="captcha-img" class="bg-white p-2 rounded-xl shadow-sm border border-slate-100 flex-grow text-center overflow-hidden">
                                    <?php echo $image; ?>
                                </div>
                                <button id="btn_cap" type="button" class="btn btn-circle btn-primary shadow-md btn-sm">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </div>
                            <input type="text" name="captcha" placeholder="Ketik kode di atas" class="input input-bordered w-full text-center h-12 tracking-[0.3em] font-black border-slate-200 uppercase" required>
                        </div>

                        <div class="space-y-4 pt-6">
                            <button type="submit" class="btn btn-primary btn-block text-white shadow-xl h-14 text-lg font-bold hover:translate-y-[-2px] transition-all duration-300">
                                MASUK SEKARANG <i class="fa fa-sign-in ml-2"></i>
                            </button>

                            <div class="divider text-slate-300 text-[10px] font-bold uppercase tracking-[0.3em]">Layanan Terintegrasi</div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="https://assistdpbt.surabaya.go.id/" target="_blank" class="btn btn-outline border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-400 h-14 transition-all normal-case font-bold">
                                    <i class="fa fa-globe text-primary"></i> ASSIST
                                </a>
                                <a href="https://assistdpbt.surabaya.go.id/asing" target="_blank" class="btn btn-outline border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-400 h-14 transition-all normal-case font-bold">
                                    <i class="fa fa-external-link text-primary"></i>ASING
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="mt-20 text-center opacity-30 text-[10px] font-bold tracking-[0.2em] leading-loose uppercase">
                        Bagian Hukum dan Kerjasama<br>
                        Setda Kota Surabaya &copy; 2026
                    </div>
                </div>
            </div>
        </div>

        <div class="right-side hidden lg:flex">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute w-[500px] h-[500px] bg-blue-400/20 rounded-full blur-[120px] -top-20 -right-20"></div>
            
            <div class="relative z-10 text-center">
                <div class="animate-float">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-12 rounded-[3.5rem] shadow-2xl inline-block mb-10">
                        <img src="<?php echo base_url() ?>assets/logononlit.png" alt="Logo" class="w-72 h-auto drop-shadow-[0_20px_20px_rgba(0,0,0,0.4)]" />
                    </div>
                </div>
                <h1 class="text-7xl font-black text-white tracking-tighter mb-2">E-<span class="text-sky-300">NONLIT</span></h1>
                <p class="text-sky-100 text-xl font-light tracking-[0.5em] uppercase opacity-70 mb-12">Pemerintah Kota Surabaya</p>
                
                <div class="flex justify-center gap-8 text-[10px] font-bold text-white/30 tracking-[0.3em]">
                    <span>INTEGRITAS</span>
                    <span>•</span>
                    <span>PROFESIONAL</span>
                    <span>•</span>
                    <span>MODERN</span>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo base_url() ?>assets/login/js/jquery.min.js"></script>
    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }

        $(document).ready(function() {
            $("#btn_cap").click(function() {
                const icon = $(this).find('i');
                icon.addClass('fa-spin');
                $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: "<?php echo base_url('auth/reload_captcha') ?>",
                    success: function(data) {
                        $('#captcha-img').html(data);
                        icon.removeClass('fa-spin');
                    }
                });
            });
        });
    </script>
</body>

</html>