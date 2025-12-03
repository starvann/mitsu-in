<x-base title="Onboarding" bodyClass="screen-onboard" mainClass="onboard-page">
  <div class="onboard-illustration">
    <img src="{{ url('assets/img/kucing.png')}}" alt="Lucky cat">
  </div>

  <div>
    <p class="onboard-text-main">Step into your future</p>
    <p class="onboard-text-sub">- the Japanese way!</p>
  </div>
  <div class="onboard-next-wrapper">
    <button class="round-button" onclick="window.location.href='/login'">
      →
    </button>
  </div>
</x-base>