<div class="bg-white shadow-lg border-b-2 border-green-600 px-6 py-3">
    <div class="max-w-4xl mx-auto flex items-center gap-4">
        <a href="{{ route('kiosk.idle') }}" class="touch-button bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-semibold">Back</span>
        </a>
        
        <div class="flex-1 relative">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Search buildings, offices..." 
                class="w-full px-4 py-2 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                autocomplete="off"
            >
            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            
            <div id="searchResults" class="hidden absolute top-full left-0 right-0 mt-2 max-h-96 overflow-y-auto bg-white rounded-lg shadow-xl border-2 border-gray-200 p-4 z-50">
                <div id="searchResultsContent"></div>
            </div>
        </div>
        
        <button onclick="clearNavigation()" class="touch-button bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span class="font-semibold">Clear</span>
        </button>
    </div>
</div>
