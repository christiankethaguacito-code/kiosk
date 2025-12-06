@extends('layouts.app')

@section('title', 'Navigation')
@section('body-class', 'bg-gradient-to-br from-green-600 to-green-800')

@section('content')
<div class="w-screen h-screen overflow-hidden flex flex-col p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-5xl font-bold text-white">Navigation to {{ $building->name }}</h1>
        <a href="{{ route('kiosk.idle') }}" class="bg-white text-green-700 px-8 py-4 rounded-lg text-2xl font-semibold hover:bg-gray-100 shadow-lg">
            Return to Start
        </a>
    </div>

    <div class="flex-1 bg-white rounded-lg shadow-2xl overflow-hidden relative">
        <canvas id="navigationCanvas" class="w-full h-full"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const canvas = document.getElementById('navigationCanvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;

    const kioskX = 151;
    const kioskY = 85;

    const buildingX = {{ $building->map_x ?? 0 }};
    const buildingY = {{ $building->map_y ?? 0 }};

    const scaleX = canvas.width / 302.596;
    const scaleY = canvas.height / 275.484;

    function drawNavigation() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        ctx.strokeStyle = '#dc2626';
        ctx.lineWidth = 5;
        ctx.setLineDash([]);

        ctx.beginPath();
        ctx.moveTo(kioskX * scaleX, kioskY * scaleY);
        ctx.lineTo(buildingX * scaleX, buildingY * scaleY);
        ctx.stroke();

        ctx.fillStyle = '#16a34a';
        ctx.beginPath();
        ctx.arc(kioskX * scaleX, kioskY * scaleY, 10, 0, 2 * Math.PI);
        ctx.fill();

        ctx.fillStyle = '#dc2626';
        ctx.beginPath();
        ctx.arc(buildingX * scaleX, buildingY * scaleY, 12, 0, 2 * Math.PI);
        ctx.fill();

        ctx.fillStyle = '#1f2937';
        ctx.font = 'bold 20px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('KIOSK START', kioskX * scaleX, (kioskY - 15) * scaleY);
        ctx.fillText('{{ $building->name }}', buildingX * scaleX, (buildingY - 15) * scaleY);
    }

    drawNavigation();

    window.addEventListener('resize', () => {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        drawNavigation();
    });
</script>
@endsection
