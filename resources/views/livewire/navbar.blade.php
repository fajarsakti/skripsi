<nav class="fixed top-0 z-50 bg-transparent w-screen transition-colors" x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 40" :class="scrolled && 'bg-primary-600'">
    <div class="mx-auto container flex justify-between items-center py-6">
        <div>
            <a href="{{ route('home') }}">
                <img src="{{ asset('build/assets/logo.png') }}" alt="logo" class="max-h-10">
            </a>
        </div>
        <div class="text-primary-900 uppercase">
            @php
                $user = Auth::user();
            @endphp
            <ul class="flex gap-8">
                @auth()
                    <!-- Dropdown -->
                    <div x-data="{ isOpen: false }" class="relative inline-block">
                        <!-- Dropdown Button -->
                        <button
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-900 text-slate-100 ring-slate-100 transition hover:shadow-md hover:ring-2 overflow-hidden"
                            @click="isOpen=!isOpen">
                            <img class="w-full object-cover" src="{{ asset('storage/' . $user->avatar_url) }}"
                                alt="Profile">
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="isOpen" x-transition=""
                            class="absolute right-0 mt-3 flex w-60 flex-col gap-3 rounded-xl bg-black p-4 text-slate-100 shadow-lg">
                            <div class="flex gap-3 items-center">
                                <div
                                    class="flex items-center justify-center rounded-lg h-12 w-12 overflow-hidden border-2 border-slate-600">
                                    <img class="w-full object-cover" src="{{ asset('storage/' . $user->avatar_url) }}"
                                        alt="Profile">
                                </div>
                                <div>
                                    <div class="flex gap-1 text-sm font-semibold">
                                        <span>{{ $user->name }}</span>
                                        <span class="text-sky-400">
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <a href="/dashboard/my-profile"
                                    class="flex items-center gap-3 rounded-md py-2 px-3 hover:bg-slate-800">
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('filament.admin.auth.login') }}"
                                    class="flex items-center gap-3 rounded-md py-2 px-3 hover:bg-slate-800">
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('my-order') }}"
                                    class="flex items-center gap-3 rounded-md py-2 px-3 hover:bg-slate-800">
                                    <span>My Order</span>
                                </a>
                            </div>
                            <form action="{{ route('filament.admin.auth.logout') }}" method="POST" class="max-width">
                                @csrf
                                <button
                                    class="flex justify-center gap-3 rounded-md bg-red-600 py-2 px-3 font-semibold hover:bg-red-500 focus:ring-2 focus:ring-red-400 w-full">
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else()
                    <li>
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="hover:text-primary-200 transition-colors text-lg">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
