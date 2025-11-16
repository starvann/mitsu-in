<x-base title="Home Page">
  <x-slot:head>
    <style>
      #next {
        font-size: 24pt;
        text-decoration: none;
      }
    </style>
  </x-slot:head>
  <h1>Selamat Datang!</h1>
  <a href="{{ url('login') }}" id="next">&gt;</a>
  {{-- url('login') => http://{server-address}/login --}}
</x-base>