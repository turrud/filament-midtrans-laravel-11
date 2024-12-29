@extends('frontend.layouts.main')
@section('title', 'Paket Wisata')

@section('content')

<div class="container max-w-screen-xl mx-auto px-4">

    <div class="min-h-screen  flex items-center justify-center p-4">
      <div class="container mx-auto ">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 ">
            @foreach ($packages as $package)
                <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col h-full ">
                    <img src="{{ $package->image ? asset('storage/' . $package->image) : asset('images/no-image.png') }}" alt="Product Image" class="w-full h-54 object-cover">
                    <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $package->name }}</h2>
                    <p class="text-gray-600 mb-4 text-justify">{{ $package->description }}</p>

                    </div>
                    <div class="mt-auto">
                        <div class="p-4 flex justify-end text-lg font-bold text-gray-500">
                            Rp {{ number_format($package->price, 0, '.', ',') }}
                        </div>
                        <div class="p-4 flex justify-end">
                            <a href="{{ route('viewpackage.show', ['package' => $package->id]) }}">
                            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                 Detail
                            </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
      </div>
    </div>
</div>


@endsection
