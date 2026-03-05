<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>E-NONLIT | Professional Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        html, body { margin: 0; padding: 0; height: 100vh; overflow: hidden; background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
        :root { --header-height: 64px; --sidebar-width: 280px; --sidebar-collapsed-width: 80px; }

        .sidebar-gradient { background: linear-gradient(180deg, #1e40af 0%, #1e3a8a 100%); }
        
        /* SIDEBAR BASE */
        #sidebar-container { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100vh; position: fixed; left: 0; top: 0; z-index: 50; 
        }

        /* MAIN CONTENT WRAPPER */
        .main-wrapper {
            height: 100vh; display: flex; flex-direction: column; width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* NAVBAR FIX */
        .navbar-fixed {
            height: var(--header-height); min-height: var(--header-height);
            position: sticky; top: 0; width: 100%; z-index: 40;
            background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0; padding: 0 1rem;
        }

        /* INDEPENDENT SCROLL */
        .content-scroll { flex: 1; overflow-y: auto; background-color: #f8fafc; }
        .sidebar-scroll { height: 100%; overflow-y: auto; overflow-x: hidden; display: flex; flex-direction: column; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* DESKTOP STATE */
        @media (min-width: 1024px) {
            .ml-sidebar { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); }
            .ml-sidebar-collapsed { margin-left: var(--sidebar-collapsed-width); width: calc(100% - var(--sidebar-collapsed-width)); }
        }

        /* MOBILE STATE */
        @media (max-width: 1023px) {
            #sidebar-container { transform: translateX(-100%); }
            #sidebar-container.mobile-open { transform: translateX(0); width: var(--sidebar-width) !important; }
            .main-wrapper { margin-left: 0 !important; width: 100% !important; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 45; backdrop-filter: blur(2px); }
            .sidebar-overlay.active { display: block; }
        }

        .active-menu { background: rgba(255, 255, 255, 0.15) !important; color: white !important; border-left: 4px solid #38bdf8; border-radius: 0 !important; }
        .sidebar-text { transition: opacity 0.2s; white-space: nowrap; }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay"></div>

<aside id="sidebar-container" class="sidebar-gradient text-white/80 overflow-hidden shadow-2xl lg:shadow-none" style="width: var(--sidebar-width);">
    <div class="sidebar-scroll no-scrollbar">
        <div class="flex items-center justify-between px-6 shrink-0" style="height: var(--header-height);">
            <div class="flex items-center gap-3 sidebar-text">
                <div class="bg-white p-1 rounded-lg">
                    <img src="<?= base_url('assets/logononlit2.png') ?>" class="h-8 w-auto" />
                </div>
                <h1 class="text-xl font-black italic tracking-tighter text-white">E-NONLIT</h1>
            </div>
            <button id="toggle-sidebar" class="btn btn-ghost btn-xs text-white p-0 w-8 h-8 hidden lg:flex">
                <i class="mdi mdi-backburger text-xl" id="toggle-icon"></i>
            </button>
            <button id="close-sidebar-mobile" class="btn btn-ghost btn-circle text-white lg:hidden">
                <i class="mdi mdi-close text-2xl"></i>
            </button>
        </div>

        <ul class="menu p-0 py-4 w-full space-y-1 flex-grow">
            <li>
                <a href="<?= base_url('home') ?>" class="flex gap-4 py-3 px-6 rounded-none hover:bg-white/10 <?= $this->uri->segment(1) == 'home' ? 'active-menu' : '' ?>">
                    <i class="mdi mdi-view-dashboard-variant-outline text-2xl"></i>
                    <span class="sidebar-text font-semibold">Dashboard</span>
                </a>
            </li>

            <li>
                <details <?= in_array($this->uri->segment(1), ['nonlit']) ? 'open' : '' ?>>
                    <summary class="flex gap-4 py-3 px-6 rounded-none hover:bg-white/10">
                        <i class="mdi mdi-folder-zip-outline text-2xl"></i>
                        <span class="sidebar-text font-semibold">Data Perkara</span>
                    </summary>
                    <ul class="bg-black/10 rounded-none">
                        <!-- <li><a href="<?= base_url('nonlit') ?>" class="py-3 px-14 text-sm font-medium hover:text-white"><i class="mdi mdi-file-document-edit-outline mr-2"></i> Non-Litigasi</a></li> -->
                        <li><a href="<?= base_url('nonlit') ?>"><i class="mdi mdi-archive-outline mr-2"></i> Data Nonlit</a></li>
                    </ul>
                </details>
            </li>

            <li>
                <a href="<?php echo base_url('laporan')?>" class="flex gap-4 py-3 px-6 rounded-none hover:bg-white/10">
                    <i class="mdi mdi-chart-box-multiple-outline text-2xl"></i>
                    <span class="sidebar-text font-semibold">Laporan Statistik</span>
                </a>
            </li>
        </ul>

        <div class="p-4 mt-auto border-t border-white/5 bg-black/10">
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-error btn-outline btn-sm btn-block border-none hover:bg-error hover:text-white transition-all">
                <i class="mdi mdi-power text-lg mr-2"></i>
                <span class="sidebar-text font-bold">KELUAR</span>
            </a>
        </div>
    </div>
</aside>

<div class="main-wrapper ml-sidebar" id="main-content">
    
    <nav class="navbar-fixed flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <button class="btn btn-square btn-ghost lg:hidden" id="mobile-toggle">
                <i class="mdi mdi-menu text-2xl text-slate-700"></i>
            </button>
            
            <div class="flex flex-col ml-2 lg:ml-0">
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest leading-none mb-1">E-Nonlit System</span>
                <span class="text-sm font-black text-slate-800 tracking-tight capitalize"><?= $this->uri->segment(1) ?: 'Overview' ?></span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost flex items-center gap-3 px-2 rounded-xl">
                    <div class="text-right hidden sm:block leading-none">
                        <p class="text-[11px] font-bold text-slate-800"><?= $this->session->userdata('username') ?></p>
                        <p class="text-[9px] text-primary font-bold uppercase tracking-widest opacity-70">Superadmin</p>
                    </div>
                    <div class="avatar">
                        <div class="w-9 rounded-lg bg-primary text-primary-content flex items-center justify-center font-bold">
                            <i class="mdi mdi-account text-xl"></i>
                        </div>
                    </div>
                </div>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow-2xl menu menu-sm dropdown-content bg-base-100 rounded-2xl w-56 border border-slate-100">
                    <li><a><i class="mdi mdi-account-outline text-lg"></i> Profil Saya</a></li>
                    <div class="divider my-1 opacity-50"></div>
                    <li><a href="<?= base_url('auth/logout') ?>" class="text-error font-bold"><i class="mdi mdi-logout-variant text-lg"></i> Logout Sesi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="content-scroll no-scrollbar">
        <div class="p-4 md:p-8">
            <?php $this->load->view($content) ?>
        </div>
        
        <footer class="p-6 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white border-t border-slate-100">
            &copy; 2026 Pemerintah Kota Surabaya
        </footer>
    </main>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
<script>
    $(document).ready(function() {
        let isCollapsed = false;
        const sidebar = $('#sidebar-container');
        const mainContent = $('#main-content');
        const overlay = $('#overlay');

        // Toggle Desktop
        $('#toggle-sidebar').on('click', function() {
            if (!isCollapsed) {
                sidebar.css('width', 'var(--sidebar-collapsed-width)');
                mainContent.removeClass('ml-sidebar').addClass('ml-sidebar-collapsed');
                $('.sidebar-text').addClass('hidden');
                $('details').removeAttr('open');
                $('#toggle-icon').removeClass('mdi-backburger').addClass('mdi-menu-open');
                isCollapsed = true;
            } else {
                sidebar.css('width', 'var(--sidebar-width)');
                mainContent.removeClass('ml-sidebar-collapsed').addClass('ml-sidebar');
                $('.sidebar-text').removeClass('hidden');
                $('#toggle-icon').removeClass('mdi-menu-open').addClass('mdi-backburger');
                isCollapsed = false;
            }
        });

        // Mobile Toggle Logic
        $('#mobile-toggle').on('click', function() {
            sidebar.addClass('mobile-open');
            overlay.addClass('active');
        });

        $('#close-sidebar-mobile, #overlay').on('click', function() {
            sidebar.removeClass('mobile-open');
            overlay.removeClass('active');
        });
    });
</script>

</body>
</html>