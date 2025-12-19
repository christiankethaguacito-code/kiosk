<?php $__env->startSection('title', 'SKSU Access - Campus Kiosk'); ?>
<?php $__env->startSection('body-class', 'overflow-hidden font-sans'); ?>

<?php $__env->startSection('head'); ?>
<style>
    /* Full Screen Campus Background with Dark Overlay */
    .campus-bg {
        background: url('/images/background.jpg') center/cover no-repeat fixed;
        position: relative;
        min-height: 100vh;
        overflow: hidden;
    }
    
    .campus-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }
    
    /* All content above overlay */
    .content-layer {
        position: relative;
        z-index: 10;
    }
    
    /* Time & Date Widget - Top Right */
    .time-widget {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 100;
        text-align: right;
    }
    
    /* Announcement Card - Full Width in 75% Container */
    .announcement-card {
        position: relative;
        width: 90%;
        max-width: 1500px;
        height: 65vh;
        margin: 0 auto;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7);
        background-size: cover;
        background-position: center;
        background-color: #1a1a1a;
        transition: background-image 0.5s ease-in-out;
    }
    
    /* CTA Button - Green Pill */
    .cta-button {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.85;
        }
    }
    
    /* Fade transition */
    [x-cloak] {
        display: none !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="campus-bg flex items-center justify-center" x-data="kioskScreen()">
    
    <!-- Time & Date Widget - Top Right (15% Header) -->
    <div class="time-widget content-layer">
        <div class="time text-5xl font-black" x-text="currentTime"></div>
        <div class="date text-sm font-semibold" x-text="currentDate"></div>
    </div>
    
    <!-- Main Content Container with Proportional Layout -->
    <div class="content-layer w-full h-screen flex flex-col">
        
        <!-- Header Space (15%) -->
        <div class="h-[15vh]"></div>
        
        <!-- Announcement Card Container (75%) -->
        <div class="h-[75vh] flex items-center justify-center px-8">
            <!-- Announcement Card -->
            <?php if($announcements->count() > 0): ?>
            <div class="announcement-card" 
                 x-data="{ currentSlide: 0, slides: <?php echo e($announcements->count()); ?> }"
                 x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 5000)"
                 :style="'background-image: url(' + [
                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($announcement->image_path ? Storage::url($announcement->image_path) : "/images/background.jpg"); ?>'<?php echo e(!$loop->last ? ',' : ''); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 ][currentSlide] + ')'">
                 
                 <!-- Announcement Title Overlay -->
                 <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-8">
                     <h3 class="text-white text-4xl font-bold drop-shadow-lg" x-text="[
                        <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        '<?php echo e($announcement->title); ?>'<?php echo e(!$loop->last ? ',' : ''); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     ][currentSlide]"></h3>
                 </div>
                 
                 <!-- Slide Indicators -->
                 <div class="absolute bottom-4 right-8 flex gap-2">
                     <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <div class="w-3 h-3 rounded-full transition-all duration-300"
                          :class="currentSlide === <?php echo e($index); ?> ? 'bg-white scale-125' : 'bg-white/50'"></div>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 </div>
            </div>
            <?php else: ?>
            <!-- No Announcements Fallback -->
            <div class="announcement-card flex items-center justify-center" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                <div class="text-center text-white">
                    <h2 class="text-5xl font-bold mb-4">SKSU Access Campus</h2>
                    <p class="text-2xl opacity-80">Interactive Campus Directory</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Footer Space (10%) - Touch to Continue -->
        <a href="<?php echo e(route('kiosk.map')); ?>" class="h-[10vh] flex items-center justify-center cursor-pointer group">
            <p class="text-white text-2xl font-bold animate-pulse" style="text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);">
                Touch the Screen to Continue
            </p>
        </a>
        
    </div>
    
</div>

<script>
const buildings = <?php echo json_encode($buildings, 15, 512) ?>;

// Start preloading all building images immediately when page loads
(function preloadAllBuildingImages() {
    let loadedCount = 0;
    let totalImages = 0;
    let currentFile = '';
    
    // Function to update progress
    const updateProgress = () => {
        console.clear();
        console.log(`🖼️ Caching building images: ${currentFile}`);
        console.log(`📊 Progress: ${loadedCount}/${totalImages}`);
    };
    
    buildings.forEach(building => {
        // Try JPG first, then PNG from public folder
        const publicJpg = `/images/buildings/${building.code}.jpg`;
        const publicPng = `/images/buildings/${building.code}.png`;
        
        // Preload public images
        [publicJpg, publicPng].forEach(src => {
            totalImages++;
            const img = new Image();
            img.onload = () => {
                loadedCount++;
                currentFile = src.split('/').pop();
                updateProgress();
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            img.onerror = () => {
                loadedCount++;
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            img.src = src;
        });
        
        // Preload database images
        if (building.image_path) {
            totalImages++;
            const dbImg = new Image();
            const dbSrc = `/storage/${building.image_path}`;
            dbImg.onload = () => {
                loadedCount++;
                currentFile = dbSrc.split('/').pop();
                updateProgress();
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            dbImg.onerror = () => {
                loadedCount++;
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            dbImg.src = dbSrc;
        }
        
        // Preload gallery images
        if (building.image_gallery && building.image_gallery.length > 0) {
            building.image_gallery.forEach(imgPath => {
                totalImages++;
                const galleryImg = new Image();
                const gallerySrc = `/storage/${imgPath}`;
                galleryImg.onload = () => {
                    loadedCount++;
                    currentFile = gallerySrc.split('/').pop();
                    updateProgress();
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`✅ All ${totalImages} building images cached successfully!`);
                        console.log(`🚀 Map will load instantly when you continue`);
                    }
                };
                galleryImg.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`✅ All ${totalImages} building images cached successfully!`);
                        console.log(`🚀 Map will load instantly when you continue`);
                    }
                };
                galleryImg.src = gallerySrc;
            });
        }
    });
    
    console.log(`🔄 Started caching ${totalImages} building images in background...`);
})();

function kioskScreen() {
    return {
        currentTime: '',
        currentDate: '',
        
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },
        
        updateClock() {
            const now = new Date();
            
            // Format: 10:00 AM
            this.currentTime = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            
            // Format: Wednesday, Nov 06, 2025
            this.currentDate = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            });
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\PatisoyFinal\Navi\resources\views/kiosk/welcome.blade.php ENDPATH**/ ?>