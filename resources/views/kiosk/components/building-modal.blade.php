<div class="modal-overlay" id="buildingModal">
    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[85vh] overflow-hidden m-8 animate-slideUp">
        <div class="relative">
            <button 
                onclick="closeBuildingModal()" 
                class="absolute top-6 right-6 z-10 bg-white/90 backdrop-blur text-gray-700 hover:bg-red-500 hover:text-white rounded-full w-16 h-16 flex items-center justify-center text-4xl font-bold shadow-lg transition"
            >
                ×
            </button>
            
            <div id="buildingModalImage" class="w-full h-64 bg-gradient-to-br from-green-600 to-blue-600 relative overflow-hidden">
                <img id="modalImage" src="" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-6 left-8 right-8">
                    <p id="modalCode" class="text-green-300 font-semibold text-lg mb-2"></p>
                    <h2 id="modalTitle" class="text-white text-5xl font-black"></h2>
                </div>
            </div>
            
            <div class="p-8 overflow-y-auto max-h-[calc(85vh-16rem)]">
                <div id="modalDescription" class="text-gray-700 text-xl leading-relaxed mb-8"></div>
                
                <button 
                    onclick="navigateToBuilding()" 
                    id="navigateButton"
                    class="w-full touch-button bg-green-600 text-white rounded-2xl py-6 text-2xl font-bold hover:bg-green-700 flex items-center justify-center gap-3 mb-8 shadow-lg"
                >
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Show Directions
                </button>
                
                <h3 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Offices in this Building
                </h3>
                
                <div id="officesList" class="space-y-4">
                    <!-- Offices will be dynamically inserted here -->
                </div>
                
                <div id="noOffices" class="hidden text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-xl">No offices listed for this building</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(10px);
    z-index: 9999;
    align-items: center;
    justify-center;
    animation: fadeIn 0.3s ease;
}

.modal-overlay.active {
    display: flex;
}

.office-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 2px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.5rem;
    transition: all 0.2s;
}

.office-card:active {
    transform: scale(0.98);
    border-color: #10b981;
}

.service-badge {
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-block;
    margin: 0.25rem;
}
</style>
