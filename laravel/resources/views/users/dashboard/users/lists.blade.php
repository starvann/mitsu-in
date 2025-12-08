<x-base title="Kelola User">
  <div>
    <a href="{{ url('dashboard') }}" role="button">Kembali</a>
    <h1>Data Siswa</h1>
    <hr>
    <div>
      @foreach($users as $user)
      <div>
        <img src="{{ url('assets/') }}" alt="">
        <div>
          <h2><a href="{{ url('/dashboard/view-user/'.$user->id) }}">{{ $user->nama }}</a></h2>
          <small>{{ $user->email }}</small>
          <small>{{ $user->stat == 'pending' ? 'Proses daftar ulang' : 'Terdaftar' }}</small>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</x-base>