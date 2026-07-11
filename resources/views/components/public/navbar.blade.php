    <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm text-icc-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12">

            <a href="{{ url('/') }}" class="h-full flex items-center">
                @if ($settings?->logo_header)
                    <img src="{{ Storage::url($settings->logo_header) }}" alt="{{ $settings->nama_website ?? 'ICC' }}" class="max-h-full w-auto max-w-[260px] object-contain">
                @else
                    <span class="text-xl font-bold text-icc-primary">{{ $settings->nama_website ?? 'ICC' }}</span>
                @endif
            </a>

            <div class="hidden lg:flex items-center gap-1 ml-12">
                <a href="{{ url('/') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Home</a>
                <a href="{{ route('event.index') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Event</a>
                <a href="{{ route('pengajuan') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Pengajuan</a>
                <a href="{{ route('verifikasi.index') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Verifikasi Sertifikat</a>
                <a href="{{ route('struktur') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Struktur</a>
                <a href="{{ route('juri') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Juri</a>
                <a href="{{ route('regulasi') }}" class="px-3 py-1.5 text-sm font-medium text-[#0A0A0A] hover:text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition-all">Regulasi</a>

                @auth
                    @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <a href="{{ url('/admin') }}" class="ml-2 px-5 py-1.5 text-sm font-semibold text-white bg-[#FF1A1A] border-2 border-[#FF1A1A] rounded-full hover:bg-[#CC1515] hover:border-[#CC1515] transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ url('/panel') }}" class="ml-2 px-5 py-1.5 text-sm font-semibold text-white bg-[#FF1A1A] border-2 border-[#FF1A1A] rounded-full hover:bg-[#CC1515] hover:border-[#CC1515] transition shadow-sm">Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-[#FF1A1A] hover:text-[#CC1515] hover:bg-[#FF1A1A]/10 rounded-lg transition" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="ml-2 px-5 py-1.5 text-sm font-semibold text-white bg-[#FF1A1A] border-2 border-[#FF1A1A] rounded-full hover:bg-[#CC1515] hover:border-[#CC1515] transition">Login</a>
                @endauth
            </div>

            <button @click="open = !open" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                <svg x-show="!open" class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak class="lg:hidden pb-4 border-t border-gray-100 pt-2 space-y-1">
            <a href="{{ url('/') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Home</a>
            <a href="{{ route('event.index') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Event</a>
            <a href="{{ route('pengajuan') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Pengajuan Event</a>
            <a href="{{ route('verifikasi.index') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Verifikasi Sertifikat</a>
            <a href="{{ route('struktur') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Struktur Organisasi</a>
            <a href="{{ route('juri') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Daftar Juri</a>
            <a href="{{ route('regulasi') }}" class="block px-3 py-2 text-sm font-medium text-[#0A0A0A] rounded-lg hover:bg-[#FF1A1A]/10 hover:text-[#FF1A1A] transition">Regulasi</a>

            @auth
                @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                    <a href="{{ url('/admin') }}" class="block px-3 py-2 text-sm font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition text-center">Dashboard</a>
                @else
                    <a href="{{ url('/panel') }}" class="block px-3 py-2 text-sm font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition text-center">Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm font-medium text-[#FF1A1A] hover:bg-[#FF1A1A]/10 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition text-center">Login</a>
            @endauth
        </div>
    </div>
</nav>
