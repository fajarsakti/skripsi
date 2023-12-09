<div>
    <div class="border rounded-2xl py-24 flex justify-center items-center">
        <div class="container max-w-xl">
            <h2 class="mb-5 text-2xl font-semibold text-primary-900">Fill your order</h2>
            @auth
                <div class="container max-w-xl">
                    <form wire:submit.prevent="submit">
                        {{ $this->form }}

                        <button type="submit"
                            class="py-2 px-3 text-sm font-semibold bg-slate-900 text-white rounded-lg mt-4">
                            Submit
                        </button>
                    </form>
                </div>
            @else
                <a wire:navigate href="{{ route('filament.admin.auth.login') }}" class="py-1 text-primary-500 underline">Login
                    to
                    Make Orders</a>
            @endauth
        </div>
    </div>
</div>
