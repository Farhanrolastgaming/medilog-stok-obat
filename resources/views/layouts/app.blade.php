<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEDILOG - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brandMaroon: '#9b2c2c', /* Warna Merah Gelap Medilog */
                        brandGreen: '#4ce05c',  /* Warna Hijau Terang Header */
                        menuText: '#6b7280',    /* Warna Abu-abu menu */
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm z-20">
        <div class="h-16 bg-brandMaroon flex items-center px-4 justify-between text-white">
            <div class="flex items-center gap-2 font-bold text-xl tracking-wide">
                <i class="fas fa-plus-square text-yellow-400"></i> MEDILOG
            </div>
            <i class="fas fa-bars cursor-pointer"></i>
        </div>

        <div class="p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                <i class="fas fa-user fa-lg"></i>
            </div>
            <div class="leading-tight">
                <div class="text-gray-800 font-medium text-sm">Muhammad Farhan</div>
                <div class="text-gray-900 font-bold text-xs mt-1">User</div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-2 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-brandMaroon text-white' : 'text-menuText hover:bg-gray-50' }} rounded-md text-sm font-medium shadow-sm transition">
                <i class="fas fa-home w-5"></i> Dashboard
            </a>

            <div class="mt-6 mb-2 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Master</div>
            <a href="{{ route('obat.index') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('obat.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-pills w-5"></i> Daftar Obat
            </a>
            <a href="{{ route('pemasok.index')}}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('pemasok.index') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-boxes w-5"></i> Daftar Pemasok
            </a>

            <div class="mt-6 mb-2 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Transaksi</div>
            <a href="{{ route('barang-masuk.index') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('barang-masuk.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-sign-in-alt w-5"></i> Barang Masuk
            </a>
            <a href="{{ route('barang-keluar.index') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('barang-keluar.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-sign-out-alt w-5"></i> Barang Keluar
            </a>

            <div class="mt-6 mb-2 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Lampiran</div>
            <a href="{{ route('report.stok') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('report.stok') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-file-alt w-5"></i> Laporan Stok
            </a>
            <a href="{{ route('report.barang-masuk') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('report.barang-masuk') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-clipboard-check w-5"></i> Laporan Barang Masuk
            </a>
            <a href="{{ route('report.barang-keluar') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('report.barang-keluar') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }}">
                <i class="fas fa-clipboard-list w-5"></i> Laporan Barang Keluar
            </a>

            <div class="mt-6 mb-2 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Akun</div>
            <a href="{{ route('user.index') }}" class="flex items-center gap-3 px-4 py-2 text-menuText hover:bg-gray-50 rounded-md text-sm transition {{ request()->routeIs('user.*') ? 'bg-brandMaroon text-white shadow-sm font-medium' : 'text-menuText hover:bg-gray-50' }} pb-6">
                <i class="fas fa-user-cog w-5"></i> Manajemen Akun
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col relative h-screen overflow-y-auto">
        <div class="absolute top-0 w-full h-48 bg-brandGreen z-0"></div>

        <header class="h-16 flex items-center justify-end px-6 z-10">
            <div class="relative group">
                <div class="flex items-center text-white gap-2 cursor-pointer bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm hover:bg-white/30 transition">
                    <div class="w-7 h-7 rounded-full bg-gray-200"></div>
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 px-8 pb-8 z-10 relative">
            @yield('content')
        </main>
    </div>

</body>
</html>