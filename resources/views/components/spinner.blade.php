<div id="spinner" class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex flex-column align-items-center justify-content-center" 
     style="z-index: 99999; 
            background: #ffffff;
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s;">
    
    {{-- Instant-Motion Brand Container --}}
    <div class="loader-viewport d-flex flex-column align-items-center">
        {{-- Breathing Logo - Starting instantly --}}
        <img src="{{ asset('img/logo.png') }}" 
             alt="Hotelia" 
             class="cinematic-breathing-logo mb-4">
        
        {{-- Progress Track - Initial 35% for immediate feel --}}
        <div class="progress-track" style="width: 160px; height: 3px; background: #f0f0f0; border-radius: 20px; overflow: hidden; position: relative;">
            <div id="spinner-progress-fill" 
                 style="width: 35%; height: 100%; background: #FEA116; border-radius: 20px; transition: width 0.6s ease-out; animation-play-state: running;"></div>
        </div>
    </div>
</div>

<style>
    /* 1. Primary Breathing Animation - No Delay, Instant State */
    .cinematic-breathing-logo {
        height: 100px;
        width: auto;
        object-fit: contain;
        animation: cinematic-breathe 2.5s cubic-bezier(0.45, 0, 0.55, 1) infinite both;
        will-change: transform, filter;
    }

    @keyframes cinematic-breathe {
        0%, 100% {
            transform: scale(0.95);
            filter: drop-shadow(0 0 15px rgba(254, 161, 22, 0.1));
        }
        50% {
            transform: scale(1.08);
            filter: drop-shadow(0 0 35px rgba(254, 161, 22, 0.3));
        }
    }

    /* 2. Professional Exit Sequence */
    #spinner.hide {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none;
    }
</style>