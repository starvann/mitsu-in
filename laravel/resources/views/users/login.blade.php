<x-base title="Login">
  <form action="{{ url('login') }}" method="post">
    <h1>Login</h1>
    <input type="email" name="email" placeholder="Email...">
    <input type="password" name="password" placeholder="Password...">
    <input type="text" name="code" placeholder="Kode...">
    <button type="submit">Login</button>
    <span>Belum punya akun? <a href="{{ url('register') }}">Daftar</a></span>
  </form>
</x-base>