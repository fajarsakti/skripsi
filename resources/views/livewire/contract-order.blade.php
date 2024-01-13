<div>
    @livewire('header', ['title' => 'Make Your Order'])
    <div class="rounded-2xl py-24 flex justify-center items-center">
        <div class="max-w-xl">
            @auth
                <div class="container max-w-xl">
                    <form wire:submit.prevent="submit">
                        {{ $this->form }}

                        <button type="submit"
                            class="py-2 px-3 text-sm font-semibold bg-primary-500 text-white rounded-lg mt-4 hover:bg-slate-600">
                            Submit
                        </button>
                    </form>
                </div>
            @else
                <a wire:navigate href="{{ route('filament.admin.auth.login') }}"
                    class="bg-primary-900 rounded-3xl py-3 px-8 font-medium inline-block mr-4 text-white hover:bg-secondary-900 hover:border-t-neutral-950 duration-300">Login
                    to Make Orders</a>
            @endauth
        </div>
    </div>
</div>
