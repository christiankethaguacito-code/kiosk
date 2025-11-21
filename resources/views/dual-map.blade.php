@extends('layouts.dual-mode')

@section('title', 'Campus Map')

@section('styles')
<style>
    .building-marker {
        position: absolute;
        width: 40px;
        height: 40px;
        background: #10b981;
        border: 3px solid white;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
    }
    .building-marker:hover { transform: translate(-50%, -50%) scale(1.2); }
    .building-marker.dragging { opacity: 0.7; cursor: move; }
    
    #navigationCanvas {
        position: absolute;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 5;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-900 p-8">
    <div class="max-w-7xl mx-auto">
        <header class="bg-green-600 text-white p-6 rounded-t-xl flex justify-between items-center">
            <h1 class="text-3xl font-bold">Campus Interactive Map</h1>
            <a href="{{ route('kiosk.idle') }}" class="text-2xl">🏠 Home</a>
        </header>

        <div class="bg-white p-4 rounded-b-xl shadow-2xl">
            <div id="mapContainer" class="relative border-4 border-gray-300 rounded-lg overflow-hidden"
                 style="width: 100%; height: 800px;">
                
                <img id="campusMap" 
                     src="{{ $mapImage }}" 
                     alt="Campus Map"
                     class="w-full h-full object-contain">
                
                <canvas id="navigationCanvas"></canvas>

                @foreach($buildings as $building)
                <div class="building-marker @auth {{ auth()->check() ? 'draggable' : '' }} @endauth"
                     data-building-id="{{ $building->id }}"
                     data-building-name="{{ $building->name }}"
                     style="left: {{ $building->endpoint_x }}px; top: {{ $building->endpoint_y }}px;"
                     onclick="openBuildingModal({{ $building->id }})">
                    <div class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold">
                        {{ substr($building->name, 0, 3) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div id="buildingModal" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-8">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div id="modalContent"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const mapContainer = document.getElementById('mapContainer');
    const canvas = document.getElementById('navigationCanvas');
    const ctx = canvas.getContext('2d');
    const kioskX = {{ $kioskX }};
    const kioskY = {{ $kioskY }};

    canvas.width = mapContainer.offsetWidth;
    canvas.height = mapContainer.offsetHeight;

    window.addEventListener('resize', () => {
        canvas.width = mapContainer.offsetWidth;
        canvas.height = mapContainer.offsetHeight;
    });

    @auth
    function isEditModeEnabled() {
        return document.documentElement.__x?.$data?.editMode || false;
    }
    
    document.querySelectorAll('.building-marker.draggable').forEach(marker => {
        let isDragging = false;
        let startX, startY;

        marker.addEventListener('mousedown', function(e) {
            if (!isEditModeEnabled()) return;
            e.preventDefault();
            isDragging = true;
            marker.classList.add('dragging');
            
            startX = e.clientX - parseInt(marker.style.left);
            startY = e.clientY - parseInt(marker.style.top);
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const rect = mapContainer.getBoundingClientRect();
            const newX = Math.max(0, Math.min(e.clientX - rect.left, rect.width));
            const newY = Math.max(0, Math.min(e.clientY - rect.top, rect.height));
            
            marker.style.left = newX + 'px';
            marker.style.top = newY + 'px';
        });

        document.addEventListener('mouseup', async function() {
            if (!isDragging) return;
            isDragging = false;
            marker.classList.remove('dragging');
            
            const buildingId = marker.dataset.buildingId;
            const newX = parseFloat(marker.style.left);
            const newY = parseFloat(marker.style.top);
            
            await ajaxPost(`/admin/inline/buildings/${buildingId}/coordinates`, {
                map_x: newX,
                map_y: newY
            });
        });
    });

    mapContainer.addEventListener('click', function(e) {
        if (!isEditModeEnabled()) return;
        if (e.target.classList.contains('building-marker')) return;

        const rect = mapContainer.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const name = prompt('Enter building name:');
        if (!name) return;

        ajaxPost('/admin/inline/buildings', {
            name: name,
            map_x: x,
            map_y: y
        }).then(() => location.reload());
    });
    @endauth

    async function openBuildingModal(buildingId) {
        showLoading();
        try {
            const response = await fetch(`/api/buildings/${buildingId}`);
            if (!response.ok) throw new Error('Failed to load building data');
            const building = await response.json();
            hideLoading();
        
        document.getElementById('modalContent').innerHTML = `
            <div class="p-8" x-data="{ nameEditing: false }">
                <button onclick="closeModal()" class="float-right text-3xl hover:text-red-500 transition">&times;</button>
                
                <div class="relative mb-6">
                    <img src="${building.image_path ? '/storage/' + building.image_path : '/images/placeholder.jpg'}" 
                         class="w-full h-64 object-cover rounded-lg shadow-lg">
                    
                    @auth
                    <div x-show="editMode" x-cloak class="absolute top-2 right-2">
                        <button onclick="changeBuildingImage(${buildingId})"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg transition">
                            📷 Change
                        </button>
                    </div>
                    @endauth
                </div>

                <div>
                    <div x-show="!nameEditing">
                        <h2 class="text-3xl font-bold editable-text" 
                            @auth @click="if(editMode) nameEditing = true" @endauth>
                            ${building.name}
                        </h2>
                    </div>
                    
                    @auth
                    <div x-show="nameEditing" x-cloak>
                        <input type="text" value="${building.name}" 
                               x-ref="nameInput"
                               @keyup.enter="updateBuildingName(${buildingId}, $event.target.value); nameEditing = false"
                               @keyup.escape="nameEditing = false"
                               class="text-3xl font-bold border-2 border-blue-500 rounded px-2 w-full">
                    </div>
                    @endauth
                </div>

                <div class="mt-8">
                    <h3 class="text-2xl font-bold mb-4">Offices</h3>
                    <div id="officesList">
                        ${building.offices.map(office => `
                            <div class="bg-gray-100 p-4 rounded-lg mb-3" x-data="{ expanded: false }">
                                <div class="flex justify-between items-center cursor-pointer" 
                                     @click="expanded = !expanded">
                                    <span class="font-semibold text-lg">${office.name}</span>
                                    <span x-text="expanded ? '▼' : '▶'"></span>
                                </div>
                                
                                <div x-show="expanded" x-cloak class="mt-4" x-transition>
                                    <p class="text-gray-700 font-semibold">${office.head_name || 'No head assigned'}</p>
                                    <p class="text-sm text-gray-500 mb-3">${office.head_title || ''}</p>
                                    
                                    <ul class="mt-3 space-y-2">
                                        ${office.services.map(service => `
                                            <li class="flex justify-between items-center bg-white p-2 rounded">
                                                <span>• ${service.description}</span>
                                                @auth
                                                <button x-show="editMode" x-cloak 
                                                        onclick="deleteService(${service.id})"
                                                        class="text-red-500 hover:text-red-700 font-bold transition">✕</button>
                                                @endauth
                                            </li>
                                        `).join('')}
                                    </ul>
                                    
                                    @auth
                                    <div x-show="editMode" x-cloak class="mt-3">
                                        <button onclick="addService(${office.id})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow transition">
                                            ➕ Add Service
                                        </button>
                                    </div>
                                    @endauth
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    @auth
                    <div x-show="editMode" x-cloak class="mt-4">
                        <button onclick="addOffice(${buildingId})"
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                            + Add Office
                        </button>
                    </div>
                    @endauth
                </div>

                <div class="mt-8">
                    <button onclick="drawNavigation(${building.endpoint_x}, ${building.endpoint_y})"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg text-xl">
                        🗺️ Navigate Here
                    </button>
                </div>
            </div>
        `;
        
            document.getElementById('buildingModal').classList.remove('hidden');
        } catch (error) {
            hideLoading();
            showToast('Failed to load building details', 'error');
            console.error(error);
        }
    }

    function closeModal() {
        document.getElementById('buildingModal').classList.add('hidden');
        clearNavigation();
    }

    function drawNavigation(destX, destY) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        ctx.strokeStyle = '#ef4444';
        ctx.lineWidth = 5;
        ctx.setLineDash([10, 5]);
        
        ctx.beginPath();
        ctx.moveTo(kioskX, kioskY);
        ctx.lineTo(destX, destY);
        ctx.stroke();
        
        ctx.setLineDash([]);
        ctx.fillStyle = '#10b981';
        ctx.beginPath();
        ctx.arc(kioskX, kioskY, 8, 0, 2 * Math.PI);
        ctx.fill();
        
        ctx.fillStyle = '#ef4444';
        ctx.beginPath();
        ctx.arc(destX, destY, 8, 0, 2 * Math.PI);
        ctx.fill();
        
        closeModal();
        
        setTimeout(() => clearNavigation(), 60000);
    }

    function clearNavigation() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    async function updateBuildingName(buildingId, newName) {
        await ajaxPost(`/admin/inline/buildings/${buildingId}/name`, { name: newName });
        location.reload();
    }

    function changeBuildingImage(buildingId) {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = async function(e) {
            if (!e.target.files[0]) return;
            try {
                await ajaxPost(`/admin/inline/buildings/${buildingId}/image`, { image: e.target.files[0] });
                setTimeout(() => location.reload(), 1000);
            } catch (error) {
                console.error('Image upload failed:', error);
            }
        };
        input.click();
    }

    async function addOffice(buildingId) {
        const name = prompt('Enter office name:');
        if (!name) return;
        
        try {
            await ajaxPost(`/admin/inline/buildings/${buildingId}/offices`, { name: name });
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            console.error('Add office failed:', error);
        }
    }

    async function addService(officeId) {
        const description = prompt('Enter service description:');
        if (!description) return;
        
        try {
            await ajaxPost(`/admin/inline/offices/${officeId}/services`, { description: description });
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            console.error('Add service failed:', error);
        }
    }

    async function deleteService(serviceId) {
        if (!confirm('Delete this service?')) return;
        try {
            await ajaxDelete(`/admin/inline/services/${serviceId}`);
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            console.error('Delete service failed:', error);
        }
    }
</script>
@endsection
