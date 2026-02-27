<x-guest-layout>
    <div class="flex flex-col items-center">
        <!-- Profile Section -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-3xl shadow-xl mb-6 transform rotate-6 hover:rotate-0 transition-transform duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
                    <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
                    <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">KeuanganKu</h1>
            <p class="text-white/80 text-lg mt-2 font-light">Catat setiap rupiah, raih masa depan cerah.</p>
        </div>

        <!-- Links Section -->
        <div class="w-full space-y-4">
            <!-- Login -->
            <a href="{{ route('login') }}" class="glass group flex items-center p-5 rounded-2xl hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 shadow-xl">
                <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div class="ml-4">
                    <span class="block font-bold text-gray-800">Masuk ke Aplikasi</span>
                    <span class="text-xs text-gray-500">Mulai kelola saldo dompetmu</span>
                </div>
            </a>

            <!-- Register -->
            <a href="{{ route('register') }}" class="glass group flex items-center p-5 rounded-2xl hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 shadow-xl">
                <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <span class="block font-bold text-gray-800">Daftar Akun Baru</span>
                    <span class="text-xs text-gray-500">Gratis dan selamanya milikmu</span>
                </div>
            </a>
        </div>

        <!-- Info / Features Section -->
        <div class="mt-10 grid grid-cols-2 gap-4 w-full">
            <div class="glass p-4 rounded-2xl text-center">
                <span class="block text-xl font-bold text-indigo-600">Mudah</span>
                <span class="text-xs text-gray-500 uppercase tracking-widest">Catat Transaksi</span>
            </div>
            <div class="glass p-4 rounded-2xl text-center">
                <span class="block text-xl font-bold text-emerald-600">Aman</span>
                <span class="text-xs text-gray-500 uppercase tracking-widest">Data Terenkripsi</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-white/60 text-xs">
            <p>&copy; 2026 KeuanganKu Apps • Versi 1.0</p>
        </div>
    </div>
</x-guest-layout>
