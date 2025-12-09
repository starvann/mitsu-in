<x-base title="Login" body-class="screen-auth" main-class="auth-page">
  <h1 class="title-jp">こんにちは!</h1>
  <p class="subtitle">Siap memulai perjalanan kariermu? Yuk, gabung bareng kami!</p>
  @if(!empty($errors->all()))
  <div class="err-messages">
    @foreach($errors->all() as $err_msg)
    <p>{{ $err_msg }}</p>
    @endforeach
  </div>
  @endif
  <form action="{{ url('login') }}" method="post" id="loginForm" class="card auth-card">
    @csrf
    <label>
      Email
      <input type="email" name="email" @error('email') aria-invalid="true" @enderror value="{{ old('email') }}" required>
    </label>
    <label>
      Password
      <input type="password" name="password" @error('password') aria-invalid="true" @enderror required>
    </label>
    <label for="remember_me" class="checkbox">
      <input type="checkbox" name="remember_me" id="remember_me" @checked(old('remember_me'))>
      Remember Me
    </label>
    <button type="submit" class="btn-primary">Sign In</button>
    <p class="helper">
      Belum memiliki akun? <a href="{{ url('register') }}">Daftar di sini</a>
    </p>
  </form>
</x-base>