<x-base title="Dashboard">
  <div class="container">
    <img src="{{ url(auth()->user()->gmb_profil) }}" width="100" alt="Gambar Profil">
    <h1>{{ auth()->user()->name }}</h1>
    <a href="{{ url('logout') }}" role="button">Log out</a>
  </div>
  <div>
    @forelse($refUsers as $ref)
    <div>
      <h2>{{ $ref->nama }}</h2>
      <span>{{  $ref->stat == 'pending' ? 'Proses daftar ulang' : 'Terdaftar' }}</span>
    </div>
    @empty
    <span><i>Belum ada yang memakai kode referralmu</i></span>
    @endforelse
  </div>
  
</x-base>