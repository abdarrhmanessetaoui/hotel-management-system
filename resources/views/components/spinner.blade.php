<div id="spinner" class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex flex-column align-items-center justify-content-center" 
     style="z-index: 99999; 
            background: #ffffff;
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s step-end;">
    
    <div class="loader-viewport d-flex flex-column align-items-center">
        
        {{-- Smooth Cinematic Breathing Logo --}}
        <img src="{{ asset('img/logo.png') }}" 
             alt="Hotelia" 
             class="modern-pulse-logo mb-5">
        
        {{-- Refined Indeterminate Progress Bar --}}
        <div class="progress-track">
            <div class="progress-fill"></div>
        </div>

    </div>
</div>

<style>
    /* 1. Cinematic Breathing Animation */
    .modern-pulse-logo {
        height: 100px;
        width: auto;
        object-fit: contain;
        animation: cinematic-breath 3s ease-in-out infinite both;
        will-change: transform, opacity;
    }

    @keyframes cinematic-breath {
        0%, 100% {
            transform: scale(0.96);
            opacity: 0.8;
            filter: drop-shadow(0 5px 15px rgba(255, 126, 33, 0.15));
        }
        50% {
            transform: scale(1.05);
            opacity: 1;
            filter: drop-shadow(0 15px 35px rgba(255, 126, 33, 0.3));
        }
    }

    /* 2. Sleek Progress Bar */
    .progress-track {
        width: 280px;
        height: 6px;
        background: rgba(255, 126, 33, 0.1); 
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        position: absolute;
        top: 0;
        left: -100%;
        width: 60%;
        height: 100%;
        background: #FF7E21; /* Match logo exactly */
        border-radius: 10px;
        animation: progress-indeterminate 2.2s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        box-shadow: 0 0 15px rgba(255, 126, 33, 0.5);
    }

    @keyframes progress-indeterminate {
        0% { left: -100%; width: 30%; }
        50% { left: 20%; width: 70%; }
        100% { left: 100%; width: 30%; }
    }

    #spinner.hide {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none;
    }
</style>

