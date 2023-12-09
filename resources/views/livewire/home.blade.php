{{-- <div>
    <header class="relative h-screen flex justify-center text-center items-center text-white overflow-hidden">
        <img src="{{ asset('storage/I9OLQQuOlyCEjvvK5IAlDH4o4ziMuQ-metaZDYwOWYxMjQzMzcwODQ0MWIwMWU4YjE0ODIxMmU3ODUuanBn-.jpg') }}"
            alt="hero" class="min-w-full absolute -z-50 brightness-50">
        <div class="flex flex-col gap-8">
            <div class="italic text-2xl text-primary-200">Kantor Jasa Penilai Publik Rizky Djunaedy dan Rekan Cabang
                Bandung
            </div>
            <a class="p-6 bg-primary-950 uppercase font-bold text-lg rounded-2xl hover:bg-primary-500 transition-colors"
                href="{{ route('contract-order') }}">Make Order</a>
        </div>
    </header>
</div> --}}
<div class="container mx-auto py-16 px-4">
    <h1 class="text-center text-4xl font-bold mb-8">Kantor Jasa Penilai Publik</h1>
    <h3 class="text-center text-2xl mb-12">Rizky Djunaedy dan Rekan Cabang Bandung</h3>
    <div class="flex justify-center items-center">
        <div class="bg-white rounded-lg shadow p-8 mr-6">
            <h3 class="text-xl font-bold mb-4">Make your order</h3>
            <p class="text-gray-600 mb-6">Please click this button to make order.</p>
            <a href="{{ route('contract-order') }}"
                class="bg-primary-500 hover:bg-secondary-700 text-white font-bold py-2 px-4 rounded">Make Order</a>
        </div>
        <div class="flex-1">
            <img src="https://source.unsplash.com/random" alt="Random Image"
                class="h-64 w-full object-cover rounded-lg shadow-lg">
        </div>
    </div>
</div>
