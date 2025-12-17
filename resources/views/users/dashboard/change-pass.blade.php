<x-base title="Ganti Password" main-class="page">
  @if(!empty($errors->all()))
  <div class="err-messages">
    @foreach($errors->all() as $err_msg)
    <p>{{ $err_msg }}</p>
    @endforeach
  </div>
  @endif
  <form action="{{ url("dashboard/change-pass/$id") }}" method="post">
    @csrf
    @method('PUT')
    <h1>Ganti Password</h1>
    <hr>
    <label>
      Password Lama
      <input type="password" name="password_lama" @error('password_lama') aria-invalid="true" @enderror>
    </label>
    <label>
      Password Baru
      <input type="password" name="password_baru" @error('password_baru') aria-invalid="true" @enderror>
    </label>
    <label>
      Ulangi Password Baru
      <input type="password" name="ulangi_password_baru" @error('ulangi_password_baru') aria-invalid="true" @enderror>
    </label>
    <div class="btn-group">
      <a href="{{ url('dashboard') }}" role="button">Kembali</a>
      <button type="submit">Ganti Password</button>
    </div>
  </form>
</x-base>