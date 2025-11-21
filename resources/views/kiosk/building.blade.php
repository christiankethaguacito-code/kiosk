@extends('Layouts.app')

@section('title', 'Building Details')
@section('body-class', 'bg-gradient-to-br from-green-600 to-green-800')

@section('content')
<div class="w-screen h-screen overflow-hidden flex flex-col p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-5xl font-bold text-white">{{ $building->name }}</h1>
        <a href="{{ route('kiosk.map') }}" class="bg-white text-green-700 px-8 py-4 rounded-lg text-2xl font-semibold hover:bg-gray-100 shadow-lg">
            Back to Map
        </a>
    </div>

    <div class="flex gap-8 flex-1 overflow-hidden">
        <div class="w-1/2 bg-white rounded-lg shadow-2xl overflow-hidden">
            @if($building->image_path)
                <img src="{{ asset('storage/' . $building->image_path) }}" alt="{{ $building->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                    <span class="text-gray-500 text-3xl">No image available</span>
                </div>
            @endif
        </div>

        <div class="w-1/2 bg-white rounded-lg shadow-2xl p-8 overflow-y-auto">
            <h2 class="text-4xl font-bold text-green-700 mb-6">Offices in this Building</h2>

            @if($building->offices->isEmpty())
                <p class="text-gray-600 text-2xl">No offices listed for this building.</p>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($building->offices as $office)
                        <a href="{{ route('kiosk.office', $office->id) }}" class="bg-green-50 hover:bg-green-100 border-2 border-green-600 rounded-lg p-6 transition-all duration-200 hover:shadow-lg">
                            <h3 class="text-2xl font-bold text-green-800 mb-2">{{ $office->name }}</h3>
                            @if($office->floor_number)
                                <p class="text-gray-700 text-xl">Floor {{ $office->floor_number }}</p>
                            @endif
                            @if($office->head_name)
                                <p class="text-gray-600 text-lg mt-2">{{ $office->head_title }}: {{ $office->head_name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
