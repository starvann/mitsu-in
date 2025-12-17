<x-base title="Menunggu Pembayaran" main-class="page col">
  <div class="top-section">
    <div class="cover" style="background-image: url('{{ url('assets/img/cover-japan.jpg') }}')">
      <div class="avatar-wrapper">
        <img id="studentAvatar" src="{{ url($user->gmb_profil) }}" />
      </div>
    </div>
    <div class="header-block">
      <div id="studentName" class="student-name">{{ $user->nama }}</div>
    </div>
  </div>
  <section class="card" style="margin-top: 16px">
    <h2 class="section-title">Status Pembayaran</h2>
    <div id="paymentStatusBox" class="payment-status pending">
      <span id="paymentStatusText">Menunggu konfirmasi</span>
    </div>
  </section>
  <a href="{{ url('pending') }}" role="button" style="margin-top: 16px; align-self: center;">Refresh</a>
</x-base>