<x-base title="Register">
  <form action="{{ url('register') }}" method="post">
    <h1>Register</h1>
    @csrf
    <input type="email" name="email" placeholder="Email..." value="{{ old('email') }}" required>
    <input type="password" name="password" placeholder="Password..." value="{{ old('password') }}" required="">
    <input type="text" name="code" placeholder="Kode..." value="{{ old('code') }}" required>
    <button type="submit">Register</button>
  </form>
</x-base>