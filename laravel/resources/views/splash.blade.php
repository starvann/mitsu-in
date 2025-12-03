<x-base title="Splash" bodyClass="screen-splash" :noMain="true">
    <div class="splash-inner">
        <div class="splash-logo">MITSU.IN</div>
    </div>

    <script>
    // auto-loncat ke onboarding setelah 2 detik
    setTimeout(() => {
        window.location.href = "/onboarding";
    }, 2000);
    </script>
</x-base>