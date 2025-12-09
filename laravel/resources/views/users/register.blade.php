<x-base title="Register" body-class="screen-auth" main-class="auth-page">
  <h1 class="title-jp">こんにちは!</h1>
  <p class="subtitle">Siap memulai perjalanan kariermu? Yuk, gabung bareng kami!</p>
  @if(!empty($errors->all()))
  <div class="err-messages">
    @foreach($errors->all() as $err_msg)
    <p>{{ $err_msg }}</p>
    @endforeach
  </div>
  @endif
  <form action="{{ url('register') }}" method="post" enctype="multipart/form-data" id="registerForm" class="card auth-card">
    @csrf
    <label>
      Nama Lengkap
      <input type="text" name="nama" @error('nama') aria-invalid="true" @enderror value="{{ old('nama') }}" required>
    </label>
    <label>
      Email
      <input type="email" name="email" @error('email') aria-invalid="true" @enderror value="{{ old('email') }}" required>
    </label>
    <label>
      Password
      <input type="password" name="password" @error('password') aria-invalid="true" @enderror required>
    </label>
    <label>
      Ulangi Password
      <input type="password" name="password_confirm" @error('password_confirm') aria-invalid="true" @enderror required>
    </label>
    <label for="gmb_profil" id="f-profil">
      Foto Profil
      <input type="file" name="gmb_profil" id="gmb_profil" @error('gmb_profil') aria-invalid="true" @enderror accept="image/png,image/jpeg,image/webp">
    </label>
    <label for="kode">
      Kode
      <input type="text" name="kode" id="kode" placeholder="Masukkan kode yang diberikan petugas..." @error('kode') aria-invalid="true" @enderror value="{{ old('kode') ?? 'stdn' }}" required>
    </label>
    
    <p class="helper">
      Sudah punya akun? <a href="{{ url('login') }}">Login</a>
    </p>
    <button type="submit" class="btn-primary">Register</button>
  </form>
  <script>
    let fProfil = document.getElementById("f-profil");
    let kode = document.getElementById("kode");
    kode.oninput = checkFProfil;
    function checkFProfil() {
      if(kode.value === 'stdn') {
        fProfil.style.display = 'none';
      } else {
        fProfil.style.display = 'flex';
      }
    }
    checkFProfil();
  </script>
</x-base>