<x-base title="Menunggu Pembayaran" main-class="page col">
  <x-slot:head>
    <style>
      .payment-card {
        padding: 20px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-top: 20px;
      }
      .price {
        font-size: 22px;
        font-weight: 700;
        color: #8B0000;
        margin-bottom: 10px;
        margin-top: 5px;
      }
      .status-info {
        margin-top: 10px;
        font-size: 14px;
        color: #777;
      }
    </style>
  </x-slot:head>
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
    <h2 class="section-title">Detail Pembayaran</h2>
    <div class="price">Rp 250.000</div>
    <p><strong>Transfer ke rekening berikut:</strong></p>
    <p><strong>BCA:</strong> 123456789 – a/n PT MITSU INDOJAYA</p>
    <p><strong>QRIS:</strong> LPK MITS GAKUEN</p>
    <img src="{{ url("assets/img/qris.jpeg") }}" alt="QRIS" style="width:50%; border-radius: 8px; margin-top: 10px;">
    <div id="paymentStatusBox" class="payment-status pending">
      <span id="paymentStatusText">{{ auth()->user()->paymentProof ? 'Menunggu Konfirmasi Admin' : 'Menunggu Bukti Pembayaran'}}</span>
    </div>
    <label>
      Bukti Pembayaran (gambar)
      <input type="file" name="file" id="inputImg" accept="image/png,image/jpeg,image/webp" required>
      <img src="{{ auth()->user()->paymentProof ? url(auth()->user()->paymentProof?->file) : '' }}" alt="Preview" style="width: 100%; height: 100%; display: none; object-fit: cover;" id="prevImg">
    </label>
    <button type="submit">Update</button>
    <p class="status-info">
      Setelah mengirim bukti pembayaran, statusmu akan otomatis menjadi:  
      <strong>Menunggu Konfirmasi Admin</strong>.
    </p>
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