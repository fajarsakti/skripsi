<nav class="fixed top-0 z-50 bg-transparent w-screen transition-colors" x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 40" :class="scrolled && 'bg-gray-600'">
    <div class="mx-auto container flex justify-between items-center py-6">
        <div>
            <a href="{{ route('home') }}">
                <img src="{{ asset('storage/logokjpp.jpeg') }}" alt="logo" class="max-h-10">
            </a>
        </div>
        <div class="text-primary-900 uppercase">
            <ul class="flex gap-8">
                @auth()
                    <li>
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="hover:text-primary-200 transition-colors">Dashboard</a>
                    </li>

                    <li>
                        <a href="{{ route('my-order') }}" class="hover:text-primary-200 transition-colors">My Order</a>
                    </li>

                    <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-left block px-2 hover:text-primary-200 w-full transition-colors">SIGN
                            OUT</button>
                    </form>
                @else()
                    <li>
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="hover:text-primary-200 transition-colors">Login</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
