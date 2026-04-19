@if( session('message') )
<div
    id="admin-toast"
    role="alert"
    style="
        position: fixed;
        top: 16px;
        right: 16px;
        z-index: 9999;
        min-width: 260px;
        max-width: 380px;
        background: #fff;
        border-left: 4px solid #28a745;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.875rem;
        animation: toast-in 0.3s ease;
    "
>
    <i class="bi bi-check-circle-fill" style="color:#28a745; font-size:1.1rem; flex-shrink:0;"></i>
    <span style="flex:1; color:#1a1a2e; font-weight:500;">{{ session('message') }}</span>
    <button
        onclick="document.getElementById('admin-toast').remove()"
        style="background:none;border:none;cursor:pointer;color:#aaa;font-size:1rem;padding:0;line-height:1;"
        aria-label="Fermer"
    >×</button>
</div>
<style>
    @keyframes toast-in {
        from { opacity:0; transform: translateY(-10px); }
        to   { opacity:1; transform: translateY(0); }
    }
</style>
<script>
    // Auto-dismiss after 4 seconds
    setTimeout(function() {
        var t = document.getElementById('admin-toast');
        if (t) {
            t.style.transition = 'opacity 0.4s';
            t.style.opacity = '0';
            setTimeout(function() { if(t) t.remove(); }, 400);
        }
    }, 4000);
</script>
@endif
