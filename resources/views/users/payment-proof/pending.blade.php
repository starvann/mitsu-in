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
  <form action="{{ url('pending') }}" class="card" style="margin-top: 16px" method="post" enctype="multipart/form-data">
    @csrf
    <h2 class="section-title">Status Pembayaran</h2>
    <div id="paymentStatusBox" class="payment-status pending">
      <span id="paymentStatusText">Menunggu konfirmasi</span>
    </div>
    <label>
      Bukti Pembayaran (gambar)
      <input type="file" name="file" id="inputImg" accept="image/png,image/jpeg,image/webp" required>
      <img src="{{ auth()->user()->paymentProof ? url(auth()->user()->paymentProof?->file) : '' }}" alt="Preview" style="width: 100%; height: 100%; display: none; object-fit: cover;" id="prevImg">
    </label>
    <button type="submit">Update</button>
  </form>

  <a href="{{ url('pending') }}" role="button" style="margin-top: 16px; align-self: center;">Refresh</a>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    let prevImg = query("#prevImg");
    let inputImg = query("#inputImg");
    if(prevImg.src != '') {
      prevImg.style.display = 'block';
    }
    inputImg.onchange = () => {
      const reader = new FileReader();
      reader.readAsDataURL(inputImg.files[0]);
      reader.onload = () => {
        prevImg.src = reader.result;
        prevImg.style.display = "block";
      };
    };
  </script>
</x-base>