@extends('frontend.layouts.main')
@section('title', 'Detail Paket Wisata')

@section('content')

<div class="container max-w-screen-xl mx-auto px-4">

    <div class="min-h-screen  flex items-center justify-center p-4">
      <div class="container mx-auto">
        <div class="grid grid-cols-1 xl:grid-cols-1 gap-6">

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="max-w-screen-xl mx-auto flex items-center justify-center">
                        <img src="{{ $package->image ? asset('storage/' . $package->image) : asset('images/no-image.png') }}" alt="Product Image" class="relative h-56 rounded-lg md:h-96 object-cover">
                    </div>
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2 text-gray-500">{{ $package->name }}</h2>
                        <p class="text-gray-600 mb-4 text-justify">{{ $package->description }}</p>

                        <div class="flex pt-5">
                            <p class="text-gray-500 mb-5 flex font-bold text-sm">Fasilitas :
                                @foreach ($package->facilities as $facility)
                                    <img class="w-5 h-5 ml-5 " src="{{ $facility->image ? asset('storage/' . $facility->image) : asset('images/no-image.png') }}" alt="logo">
                                @endforeach
                            </p>
                        </div>


                    </div>
                    <div class="p-4 flex justify-end text-sm font-mono text-gray-500">
                        <span>Harga per orang/hari :</span>

                            &nbsp Rp {{ number_format($package->price, 0, '.', ',') }}

                    </div>

                    <div class="p-4 flex justify-end">

                        <button onclick="openModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Checkout
                        </button>

                    </div>
                </div>
                <div class="p-4 flex justify-end text-sm  text-gray-500">
                    @include('frontend.layouts.formModal')
                </div>



        </div>
      </div>
    </div>
</div>


@endsection
