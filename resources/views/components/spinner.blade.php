<div id="spinner" class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex flex-column align-items-center justify-content-center" 
     style="z-index: 99999; 
            background: #ffffff;
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;">
    
    {{-- Modern Minimalist Spinner Container --}}
    <div class="loader-viewport d-flex flex-column align-items-center">
        
        {{-- Smooth Pulsing Logo --}}
        <img src="{{ asset('img/logo.png') }}" 
             alt="Hotelia" 
             class="modern-pulse-logo mb-4">
        
        {{-- Smooth Indeterminate Progress Bar --}}
        <div class="progress-track">
            <div class="progress-fill"></div>
        </div>

    </div>
</div>

<style>
    /* 1. Subtle, Professional Logo Animation */
    .modern-pulse-logo {
        height: 125px; /* Increased for better visibility */
        width: auto;
        object-fit: contain;
        animation: saas-pulse 2.5s ease-in-out infinite both;
        will-change: transform, opacity;
    }

    @keyframes saas-pulse {
        0%, 100% {
            transform: translateY(0) scale(0.98);
            opacity: 0.85;
            filter: drop-shadow(0 4px 8px rgba(254, 161, 22, 0.1));
        }
        50% {
            transform: translateY(-4px) scale(1.04);
            opacity: 1;
            filter: drop-shadow(0 12px 24px rgba(254, 161, 22, 0.25));
        }
    }

    /* 2. Increased Height & Indeterminate Progress Animation */
    .progress-track {
        width: 220px;
        height: 6px; /* Increased height so it's clearly visible */
        background: rgba(254, 161, 22, 0.15); /* Minimalist track matching primary color */
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: #FEA116; /* Same primary color */
        border-radius: 10px;
        animation: progress-indeterminate 1.8s ease-in-out infinite;
        box-shadow: 0 0 10px rgba(254, 161, 22, 0.4); /* Subtle ambient glow */
    }

    @keyframes progress-indeterminate {
        0% { left: -60%; width: 30%; }
        50% { left: 20%; width: 80%; }
        100% { left: 120%; width: 30%; }
    }

    /* 3. Smooth Fade-Out the moment the page loads */
    #spinner.hide {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none;
    }
</style>