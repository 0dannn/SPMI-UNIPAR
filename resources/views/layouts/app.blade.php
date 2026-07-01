<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SPMI') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ sidebarOpen: false, profileDropdownOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Backdrop for Mobile -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col shadow-sm">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200 bg-blue-700 text-white font-bold text-xl tracking-wider shadow-inner">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                SPMI System
            </div>
            
            <!-- Sidebar Nav -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Siklus PPEPP</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }} rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                    <span class="w-8 text-blue-600 font-bold">[DB]</span> Dashboard
                </a>

                @hasanyrole('Admin|LPM|Super Admin')
                <a href="{{ route('standar.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('standar.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }} rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                    <span class="w-8 text-blue-600 font-bold">[P1]</span> Standar Mutu
                </a>
                @endhasanyrole
                
                @hasrole('Auditee')
                <a href="{{ route('pengukuran.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('pengukuran.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }} rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                    <span class="w-8 text-blue-600 font-bold">[P2]</span> Evaluasi Diri
                </a>
                @endhasrole
                
                @hasrole('Auditor')
                <a href="{{ route('audit-ami.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('audit-ami.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }} rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                    <span class="w-8 text-blue-600 font-bold">[ E ]</span> Penilaian AMI
                </a>
                @endhasrole
                
                @hasanyrole('Admin|LPM|Auditee')
                <a href="{{ route('rtm-rtl.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('rtm-rtl.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }} rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                    <span class="w-8 text-blue-600 font-bold">[P3]</span> Tindak Lanjut
                </a>
                @endhasanyrole

                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Lainnya</div>
                
                <a href="{{ route('notifikasi.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('notifikasi.*') ? 'bg-gray-100' : 'text-gray-700' }} rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Notifikasi
                </a>
                
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-gray-100' : 'text-gray-700' }} rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan
                </a>

                @hasrole('Admin')
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Kelola Sistem</div>
                <a href="{{ route('periode.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('periode.*') ? 'bg-gray-100' : 'text-gray-700' }} rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="w-8 text-gray-500 font-bold"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span> Periode
                </a>
                <a href="{{ route('users.index') ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-gray-100' : 'text-gray-700' }} rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="w-8 text-gray-500 font-bold"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></span> Pengguna
                </a>
                @endhasrole

            </nav>
        </aside>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-gray-200 shadow-sm z-10">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    <!-- Hamburger Menu Mobile -->
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md p-1 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <!-- Page Header Title -->
                    <div class="hidden sm:flex items-center text-xl font-semibold text-gray-800">
                        {{ $header ?? 'Dashboard SPMI' }}
                    </div>
                    
                    <!-- Right Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Dropdown -->
                        <div class="relative" x-data="{ notifOpen: false }">
                            @php
                                $unreadNotifsCount = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count();
                                $latestNotifs = \App\Models\Notifikasi::where('user_id', auth()->id())->latest()->take(5)->get();
                            @endphp
                            <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative p-2 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none">
                                @if($unreadNotifsCount > 0)
                                <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full shadow-sm ring-2 ring-white">{{ $unreadNotifsCount > 9 ? '9+' : $unreadNotifsCount }}</span>
                                @endif
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="notifOpen" x-transition.opacity style="display: none;" class="absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-xl py-2 ring-1 ring-black ring-opacity-5 z-50">
                                <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                    <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
                                    @if($unreadNotifsCount > 0)
                                    <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Tandai semua dibaca</button>
                                    </form>
                                    @endif
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse($latestNotifs as $notif)
                                        <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ !$notif->is_read ? 'bg-blue-50/50' : '' }}">
                                            <p class="text-sm font-semibold text-gray-800 mb-1">{{ $notif->title }}</p>
                                            <p class="text-xs text-gray-600 mb-1 line-clamp-2">{{ $notif->message }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada notifikasi baru.</div>
                                    @endforelse
                                </div>
                                <a href="{{ route('notifikasi.index') }}" class="block px-4 py-2 text-xs text-center text-gray-700 bg-gray-50 hover:bg-gray-100 hover:text-blue-600 transition-colors font-medium rounded-b-lg">Lihat Semua Notifikasi</a>
                            </div>
                        </div>
                        
                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="profileDropdownOpen = !profileDropdownOpen" @click.away="profileDropdownOpen = false" class="flex items-center space-x-3 focus:outline-none">
                                <div class="text-right hidden sm:block">
                                    <div class="text-sm font-semibold text-gray-900 leading-tight">{{ Auth::user()->name ?? 'Guest' }}</div>
                                    <div class="text-xs text-blue-600 font-medium capitalize">{{ Auth::check() ? (Auth::user()->roles->first()->name ?? 'User') : 'Role' }}</div>
                                </div>
                                <img class="w-9 h-9 rounded-full object-cover border-2 border-blue-100 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'G') }}&background=EBF4FF&color=1E40AF&bold=true" alt="Avatar">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="profileDropdownOpen" x-transition.opacity style="display: none;" class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50">
                                <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-blue-600">{{ Auth::check() ? (Auth::user()->roles->first()->name ?? 'User') : 'Role' }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">Profil Saya</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                <!-- Validation Errors / Alerts -->
                @if (session('status'))
                    <x-alert type="success" :message="session('status')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
                
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 text-center py-4 text-sm text-gray-500">
                &copy; {{ date('Y') }} <strong>{{ config('app.name', 'SPMI') }}</strong>. All rights reserved.
            </footer>
        </div>
    </div>
</body>
</html>
