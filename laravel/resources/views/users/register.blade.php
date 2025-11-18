<x-base title="Register">
  <form action="{{ url('register') }}" method="post">
    <h1>Register</h1>
    @csrf
    <input type="email" name="email" placeholder="Email..." value="{{ old('email') }}" required>
    <input type="password" name="password" placeholder="Password..." value="{{ session('password') }}" required="">
    <input type="text" name="code" placeholder="Kode..." value="{{ old('code') }}" required>
    <button type="submit">Register</button>
    <span>Sudah punya akun? <a href="{{ url('login') }}">Login</a></span>
  </form>
</x-base>