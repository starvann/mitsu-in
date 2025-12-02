<x-base title="Kelola Ujian">
  <h1>Kelola Ujian</h1>
  <div>
    <div>
      <a href="{{ url('dashboard') }}" role="button">Kembali</a>
      <a href="{{ url('dashboard/create-exam') }}" role="button">Buat</a>
    </div>
  </div>
  <div>
    @forelse($exams as $exam)
    <div>
      <h2>{{ $exam->judul }}</h2>
      <p>{{ Str::limit($exam->deskripsi, 48) }}</p>
      <div>
        <a href="{{ url("dashboard/edit-exam/$exam->id") }}" role="button">Edit</a>
        <a href="{{ url("dashboard/exam-result/$exam->id") }}" role="button">Hasil</a>
        <form action="{{ url("dashboard/delete-exam/$exam->id") }}" method="post">
          @csrf
          @method('delete')
          <button type="submit" onclick="return confirm('Yakin ingin menghapus {{ $exam->judul }}?');">Hapus</button>
        </form>
      </div>
    </div>
    @empty
    <span>-- <i>Kosong</i> --</span>
    @endforelse
  </div>
</x-base>