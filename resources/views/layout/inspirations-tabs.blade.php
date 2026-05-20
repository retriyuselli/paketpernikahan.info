@php
    $itBlog       = request()->routeIs('blog.*') || request()->routeIs('blog.index');
    $itRealWedding = request()->routeIs('real-wedding.*') || request()->routeIs('real-wedding.index');
@endphp

<div id="inspirations-tabs" style="display:none; position:sticky; top:calc(52px + env(safe-area-inset-top)); z-index:30; background:#fff; border-bottom:1px solid #f0f0f0;">
    <div style="display:flex; align-items:center;">
        <a href="{{ route('blog.index') }}"
           style="flex:1; display:flex; align-items:center; justify-content:center; padding:10px 4px 8px; font-size:13px; font-weight:600; border-bottom: 2px solid {{ $itBlog ? 'var(--sage-green)' : 'transparent' }}; color: {{ $itBlog ? 'var(--sage-green)' : '#9CA3AF' }}; text-decoration:none; transition:color .2s;">
            Blog
        </a>
        <a href="{{ route('real-wedding.index') }}"
           style="flex:1; display:flex; align-items:center; justify-content:center; padding:10px 4px 8px; font-size:13px; font-weight:600; border-bottom: 2px solid {{ $itRealWedding ? 'var(--sage-green)' : 'transparent' }}; color: {{ $itRealWedding ? 'var(--sage-green)' : '#9CA3AF' }}; text-decoration:none; transition:color .2s;">
            Real Wedding
        </a>
        <button type="button" onclick="document.getElementById('ins-event-modal').style.display='flex'"
           style="flex:1; display:flex; align-items:center; justify-content:center; padding:10px 4px 8px; font-size:13px; font-weight:600; border-bottom:2px solid transparent; color:#9CA3AF; background:none; border-top:none; border-left:none; border-right:none; cursor:pointer;">
            Event
            <span style="margin-left:4px; font-size:9px; background:#F3F4F6; color:#6B7280; border-radius:4px; padding:1px 4px; font-weight:700;">Soon</span>
        </button>
    </div>
</div>

{{-- Event Coming Soon Modal --}}
<div id="ins-event-modal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; padding:16px; background:rgba(0,0,0,0.45);">
    <div style="background:#fff; border-radius:20px; padding:28px 24px; max-width:320px; width:100%; text-align:center; display:flex; flex-direction:column; align-items:center; gap:12px;">
        <div style="width:56px; height:56px; border-radius:50%; background:var(--soft-pink); display:flex; align-items:center; justify-content:center;">
            <svg width="26" height="26" fill="none" stroke="var(--sage-green)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <p style="font-size:16px; font-weight:700; color:var(--dark-gray); margin:0;">Event Segera Hadir</p>
        <p style="font-size:13px; color:#6B7280; margin:0; line-height:1.5;">Fitur Event sedang dalam pengembangan. Nantikan pembaruan selanjutnya!</p>
        <button onclick="document.getElementById('ins-event-modal').style.display='none'"
                style="width:100%; padding:10px; border-radius:12px; background:var(--sage-green); color:var(--cream); font-size:13px; font-weight:700; border:none; cursor:pointer; margin-top:4px;">
            Mengerti
        </button>
    </div>
</div>

<script>
(function () {
    if (window.Capacitor && window.Capacitor.isNativePlatform && window.Capacitor.isNativePlatform()) {
        var tabs = document.getElementById('inspirations-tabs');
        if (tabs) tabs.style.display = 'block';
    }
})();
</script>
