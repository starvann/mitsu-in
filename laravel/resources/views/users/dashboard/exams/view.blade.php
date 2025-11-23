<x-base title="Kelola Ujian">
  <h1>Kelola Ujian</h1>
  <div>
    <div>
      <a href="{{ url('dashboard') }}" role="button">Kembali</a>
      <a href="{{ url('create-exam') }}" role="button">Buat</a>
      <button type="button" id="search-toggle">Cari</button>
    </div>
    <input type="search" name="search" id="search">
  </div>
  <div>
    @forelse($exams as $exam)
    <div>
      <h2>{{ $exam->nama }}</h2>
      <p>{{ Str::limit($exam->deskripsi, 48) }}</p>
      <div>
        <a href="{{ url('edit-exam/'.$exam->id) }}">Edit</a>
        <form action="{{ url('delete-exam') }}" method="post">
          @csrf
          <button type="submit">Hapus</button>
        </form>
      </div>
    </div>
    @empty
    <span>-- <i>Kosong</i> --</span>
    @endforelse
  </div>
</x-base>