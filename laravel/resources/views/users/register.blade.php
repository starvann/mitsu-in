<x-base title="Register">
  <form action="{{ url('register') }}" method="post">
    <h1>Register</h1>
    @csrf
    @if(!empty($errors->all()))
    <ul>
      @foreach($errors->all() as $err_msg)
      <li>{{ $err_msg }}</li>
      @endforeach
    </ul>
    @endif
    <input type="email" name="email" placeholder="Email..." @error('email') aria-invalid="true" @enderror value="{{ old('email') }}" required>
    <input type="password" name="password" placeholder="Password..." @error('password') aria-invalid="true" @enderror value="{{ session('password') }}" required="">
    <input type="text" name="code" placeholder="Kode..." @error('code') aria-invalid="true" @enderror value="{{ old('code') }}" required>
    <button type="submit">Register</button>
    <span>Sudah punya akun? <a href="{{ url('login') }}">Login</a></span>
  </form>
</x-base>