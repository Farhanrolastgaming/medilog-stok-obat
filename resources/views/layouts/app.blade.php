<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEDILOG - Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brandMaroon: '#115E59', /* Warna Deep Teal Medilog (#115E59) */
                        brandGreen: '#0d9488',  /* Warna Hijau Teal Medilog (#0D9488) */
                        brandYellow: '#eab308', /* Warna Kuning Terang Medilog */
                        menuText: '#6b7280',    /* Warna Abu-abu menu */
                    }
                }
            }
        }
    </script>
    <style>
        /* CSS khusus untuk mode Mini Sidebar (Icon Only) pada Desktop */
        @media (min-width: 768px) {
            #sidebar.is-collapsed {
                width: 5rem !important; /* 80px */
            }
            #sidebar.is-collapsed .sidebar-text,
            #sidebar.is-collapsed .sidebar-heading,
            #sidebar.is-collapsed #sidebarToggle {
                display: none !important;
            }
            #sidebar.is-collapsed .sidebar-logo-container {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                height: 4rem !important;
            }
            #sidebar.is-collapsed .sidebar-user-card {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            #sidebar.is-collapsed .sidebar-nav-item {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            #sidebar.is-collapsed .sidebar-nav-item i {
                margin-right: 0 !important;
                width: auto !important;
                font-size: 1.25rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans">

    <!-- Backdrop Overlay untuk Mobile/Android -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 md:hidden"></div>

    <!-- Sidebar Element -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm z-50 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shrink-0">
        
        <!-- Sidebar Header (Kotak Logo MEDILOG & Tombol 3 Garis / Hamburger) -->
        <div class="h-16 bg-brandMaroon flex items-center justify-between px-4 text-white sidebar-logo-container transition-all">
            <div id="sidebarLogo" class="flex items-center gap-2 font-bold text-xl tracking-wide overflow-hidden cursor-pointer" title="MEDILOG - Buka Sidebar">
                <img src="{{ asset('images/logo.png')}}" width="40" height="40" alt="MEDILOG Logo" class="shrink-0 hover:scale-105 transition-transform">
                <p class="sidebar-text whitespace-nowrap" style="font-size: 25px;">MEDI<span class="text-brandYellow">LOG</span></p>
            </div>
            <!-- Tombol 3 Garis (Hamburger) di Dalam Kotak Logo (Hanya tampil saat sidebar terbuka) -->
            <button id="sidebarToggle" class="text-white hover:text-brandYellow focus:outline-none p-1.5 rounded-lg hover:bg-white/10 transition cursor-pointer flex items-center justify-center shrink-0" title="Tutup Sidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- User Info Card -->
        <div class="p-4 flex items-center gap-3 sidebar-user-card border-b border-gray-100">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 shrink-0 shadow-sm">
                <i class="fas fa-user text-base"></i>
            </div>
            <div class="leading-tight sidebar-text overflow-hidden">
                <div class="text-gray-800 font-medium text-sm truncate">{{ auth()->user()->name ?? 'Muhammad Farhan' }}</div>
                <div class="text-gray-500 font-semibold text-xs mt-0.5">
                    @php
                        $r = strtolower(auth()->user()->role ?? '');
                        $roleLabel = in_array($r, ['admin', 'owner']) ? 'Owner' : 'Apoteker';
                    @endphp
                    {{ $roleLabel }}
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-1.5">
            <a href="{{ route('dashboard') }}" title="Dashboard" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-brandMaroon text-white' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm font-medium shadow-sm transition">
                <i class="fas fa-home w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Dashboard</span>
            </a>

            <div class="sidebar-heading mt-5 mb-2 px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pemesanan</div>
            <a href="{{ route('pemesanan.index') }}" title="Pemesanan Stok" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('pemesanan.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-shopping-cart w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Pemesanan Stok</span>
            </a>

            <div class="sidebar-heading mt-5 mb-2 px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Master</div>
            <a href="{{ route('obat.index') }}" title="Daftar Obat" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('obat.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-pills w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Daftar Obat</span>
            </a>
            <a href="{{ route('pemasok.index')}}" title="Daftar Pemasok" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('pemasok.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-boxes w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Daftar Pemasok</span>
            </a>

            <div class="sidebar-heading mt-5 mb-2 px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Transaksi</div>
            <a href="{{ route('barang-masuk.index') }}" title="Barang Masuk" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('barang-masuk.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-sign-in-alt w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Barang Masuk</span>
            </a>
            <a href="{{ route('barang-keluar.index') }}" title="Barang Keluar" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('barang-keluar.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-sign-out-alt w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Barang Keluar</span>
            </a>
            <a href="{{ route('obat-rusak.index') }}" title="Retur & Obat Rusak" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('obat-rusak.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-exclamation-circle w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Retur & Obat Rusak</span>
            </a>
            
            <div class="sidebar-heading mt-5 mb-2 px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Lampiran</div>
            <a href="{{ route('report.stok') }}" title="Laporan Stok" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('report.stok') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-file-alt w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Laporan Stok</span>
            </a>
            <a href="{{ route('report.barang-masuk') }}" title="Laporan Barang Masuk" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('report.barang-masuk') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-clipboard-check w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Laporan Barang Masuk</span>
            </a>
            <a href="{{ route('report.barang-keluar') }}" title="Laporan Barang Keluar" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('report.barang-keluar') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition">
                <i class="fas fa-clipboard-list w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Laporan Barang Keluar</span>
            </a>

            @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
            <div class="sidebar-heading mt-5 mb-2 px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Akun</div>
            <a href="{{ route('user.index') }}" title="Manajemen Akun" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 {{ request()->routeIs('user.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-100' }} rounded-lg text-sm transition mb-6">
                <i class="fas fa-user-cog w-5 text-center text-base"></i>
                <span class="sidebar-text truncate">Manajemen Akun</span>
            </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 relative h-screen overflow-y-auto flex flex-col min-w-0">
        
        <!-- Header Background Banner -->
        <div class="absolute top-0 left-0 w-full h-48 bg-brandGreen z-0"></div>

        <!-- Top Header Navigation -->
        <header class="py-4 flex items-center justify-between px-4 md:px-6 relative z-30">
            <!-- Left Header: Tombol Buka Sidebar Khusus Mobile/Android -->
            <div class="flex items-center gap-3 md:hidden">
                <button id="mobileSidebarOpen" class="p-2 text-white bg-white/20 hover:bg-white/30 rounded-lg backdrop-blur-sm transition flex items-center justify-center cursor-pointer shadow-sm" title="Buka Sidebar">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div class="text-white font-bold text-lg flex items-center gap-1.5">
                    <span>MEDI<span class="text-brandYellow">LOG</span></span>
                </div>
            </div>
            <div class="hidden md:block"></div>

            <!-- Right Header: User Profile Dropdown -->
            <div class="relative group">
                <div class="flex items-center text-white gap-2 cursor-pointer bg-white/20 px-3 py-1.5 rounded-full backdrop-blur-sm hover:bg-white/30 transition shadow-sm">
                    <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                    <span class="text-xs font-semibold hidden sm:inline">{{ auth()->user()->name ?? 'User' }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-gray-100">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition">
                            <i class="fas fa-sign-out-alt text-red-500"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 px-4 md:px-8 pb-8 relative z-10">
            @yield('content')
        </main>
        
        @stack('modals')
    </div>

    <!-- JavaScript Vanilla untuk Toggle & Responsivitas Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarLogo = document.getElementById('sidebarLogo');
            const mobileSidebarOpen = document.getElementById('mobileSidebarOpen');

            // Restore status desktop collapsed dari localStorage
            if (window.innerWidth >= 768) {
                if (localStorage.getItem('medilog_sidebar_collapsed') === 'true') {
                    sidebar.classList.add('is-collapsed');
                }
            }

            function collapseSidebar() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.add('is-collapsed');
                    localStorage.setItem('medilog_sidebar_collapsed', 'true');
                } else {
                    closeMobileSidebar();
                }
            }

            function expandSidebar() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('is-collapsed');
                    localStorage.setItem('medilog_sidebar_collapsed', 'false');
                } else {
                    openMobileSidebar();
                }
            }

            function openMobileSidebar() {
                sidebar.classList.add('mobile-open');
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileSidebar() {
                sidebar.classList.remove('mobile-open');
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Tombol 3 garis (hamburger) menutup sidebar saat diklik
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    collapseSidebar();
                });
            }

            // Klik Logo MEDILOG membuka sidebar kembali jika sedang tertutup
            if (sidebarLogo) {
                sidebarLogo.addEventListener('click', function() {
                    if (window.innerWidth >= 768 && sidebar.classList.contains('is-collapsed')) {
                        expandSidebar();
                    }
                });
            }

            if (mobileSidebarOpen) mobileSidebarOpen.addEventListener('click', openMobileSidebar);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeMobileSidebar);

            // Tutup sidebar otomatis saat menu navigasi diklik di HP
            const navLinks = sidebar.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeMobileSidebar();
                    }
                });
            });

            // Respon saat window di-resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebarOverlay.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full', 'mobile-open');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    if (!sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            });
        });
    </script>
</body>
</html>