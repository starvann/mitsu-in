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
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/>
      </svg>
    </button>
  </div>
</x-base>