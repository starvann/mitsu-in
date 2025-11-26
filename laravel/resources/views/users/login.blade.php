<x-base title="Login">
  <form action="{{ url('login') }}" method="post">
    <h1>Login</h1>
    @csrf
    @if(!empty($errors->all()))
    <ul>
      @foreach($errors->all() as $err_msg)
      <li>{{ $err_msg }}</li>
      @endforeach
    </ul>
    @endif
    @session('err')
    <div>{{ session('err') }}</div>
    @endsession
    <input type="email" name="email" placeholder="Email..." @error('email') aria-invalid="true" @enderror value="{{ old('email') }}" required>
    <input type="password" name="password" placeholder="Password..." @error('password') aria-invalid="true" @enderror value="{{ old('password') }}" required>
    <input type="text" name="code" placeholder="Kode..." @error('code') aria-invalid="true" @enderror value="{{ old('code') }}" required>
    <label for="remember_me">
      <input type="checkbox" name="remember_me" id="remember_me" @checked(old('remember_me'))>
      Remember Me
    </label>
    <button type="submit">Login</button>
    <span>Belum punya akun? <a href="{{ url('register') }}">Daftar</a></span>
  </form>
</x-base>