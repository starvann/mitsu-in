<x-base title="Hasil Ujian" main-class="page" style="display: flex; flex-direction: column; gap: 12px;">
    <a href="{{ url('dashboard/manage-exam') }}" role="button">Kembali</a>
    <h1 style="color: #7b0000;">Hasil "{{ $exam->judul }}"</h1>
    @if($results->isNotEmpty())
        <a href="{{ url("dashboard/del-all-exam-result/$exam->id") }}" role="button" onclick="return confirm('Yakin?');">Hapus Semua Hasil</a>
    @endif
    <div>
        @forelse($results as $result)
        <details>
            <summary>
                <div>
                    <b>{{ $result->user->nama }}</b>
                    <small>{{ $result->user->email }}</small>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </summary>
            <div>
                <p>Nilai : {{ $result->nilai }}</p>
                <p>Benar : {{ $result->total_benar }}</p>
                <p>Salah : {{ $result->total_salah }}</p>
                <a href="{{ url("dashboard/del-exam-result/$result->id") }}" role="button" onclick="return confirm('Yakin?');">Hapus (untuk mengerjakan ulang)</a>
            </div>
        </details>
        @empty
        <span>-- <i>Tidak ada hasil</i> --</span>
        @endforelse
    </div>
</x-base>