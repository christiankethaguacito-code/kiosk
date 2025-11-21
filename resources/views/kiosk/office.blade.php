@extends('Layouts.app')

@section('title', 'Office Details')
@section('body-class', 'bg-gradient-to-br from-green-600 to-green-800')

@section('content')
<div class="w-screen h-screen overflow-hidden flex flex-col p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-5xl font-bold text-white">{{ $office->name }}</h1>
        <div class="flex gap-4">
            <a href="{{ route('kiosk.building', $office->building_id) }}" class="bg-white text-green-700 px-8 py-4 rounded-lg text-2xl font-semibold hover:bg-gray-100 shadow-lg">
                Back to Building
            </a>
            <a href="{{ route('kiosk.map') }}" class="bg-white text-green-700 px-8 py-4 rounded-lg text-2xl font-semibold hover:bg-gray-100 shadow-lg">
                Back to Map
            </a>
        </div>
    </div>

    <div class="flex gap-8 flex-1 overflow-hidden">
        <div class="w-1/2 bg-white rounded-lg shadow-2xl p-8">
            <h2 class="text-4xl font-bold text-green-700 mb-6">Office Information</h2>
            
            <div class="mb-6">
                <p class="text-gray-600 text-xl mb-2">Building:</p>
                <p class="text-3xl font-bold text-green-800">{{ $office->building->name }}</p>
            </div>

            @if($office->floor_number)
                <div class="mb-6">
                    <p class="text-gray-600 text-xl mb-2">Floor:</p>
                    <p class="text-3xl font-bold text-green-800">{{ $office->floor_number }}</p>
                </div>
            @endif

            @if($office->head_name)
                <div class="mb-6">
                    <p class="text-gray-600 text-xl mb-2">{{ $office->head_title ?? 'Head of Office' }}:</p>
                    <p class="text-3xl font-bold text-green-800">{{ $office->head_name }}</p>
                </div>
            @endif

            <a href="{{ route('kiosk.navigate', $office->building_id) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-8 py-6 rounded-lg text-3xl font-bold shadow-lg mt-8 transition-all duration-200">
                Navigate to this Office
            </a>
        </div>

        <div class="w-1/2 bg-white rounded-lg shadow-2xl p-8 overflow-y-auto">
            <h2 class="text-4xl font-bold text-green-700 mb-6">Services Offered</h2>

            @if($office->services->isEmpty())
                <p class="text-gray-600 text-2xl">No services listed for this office.</p>
            @else
                <ul class="space-y-4">
                    @foreach($office->services as $service)
                        <li class="flex items-start">
                            <span class="text-green-600 text-3xl mr-4">•</span>
                            <span class="text-gray-800 text-2xl leading-relaxed">{{ $service->description }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
