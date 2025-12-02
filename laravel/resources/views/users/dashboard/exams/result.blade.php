<x-base title="Hasil Ujian">
    <a href="{{ url('dashboard/manage-exam') }}" role="button">Kembali</a>
    <h1>Hasil "{{ $exam->judul }}"</h1>
    @if($results->isNotEmpty())
        <a href="{{ url("dashboard/del-all-exam-result/$exam->id") }}" role="button" onclick="return confirm('Yakin?');">Hapus Semua Hasil</a>
    @endif
    <div>
        @forelse($results as $result)
        <details>
            <summary>
                <b>{{ $result->user->nama }}</b>
                <small>{{ $result->user->email }}</small>
            </summary>
            <p>
                Nilai : {{ $result->nilai }}<br>
                Benar : {{ $result->total_benar }}<br>
                Salah : {{ $result->total_salah }}<br>
                <a href="{{ url("dashboard/del-exam-result/$result->id") }}" role="button" onclick="return confirm('Yakin?');">Hapus (untuk mengerjakan ulang)</a>
            </p>
        </details>
        @empty
        <span>-- <i>Tidak ada hasil</i> --</span>
        @endforelse
    </div>
</x-base>