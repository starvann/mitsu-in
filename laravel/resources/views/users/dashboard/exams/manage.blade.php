<x-base title="Kelola Ujian" main-class="page" style="display: flex; flex-direction: column; gap: 20px;">
  <x-slot:head>
    <style>
      .exam-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .exam-card h2 {
        font-size: 14pt;
      }
      .exam-card p {
        font-size: 10pt;
        color: #555;
      }
      .exam-card .col {
        padding: 16px;
        flex: 1;
      }
      .exam-card .card-actions {
        display: flex;
        gap: 4px;
        padding-right: 16px;
      }
      .exam-card .card-actions form {
        margin: 0;
      }
      button[type="submit"] {
        display: inline-block;
        text-decoration: none;
        padding: 9px 14px;
        border-radius: 4px;
        border: none;
        background: #8b0000;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        max-width: fit-content;
      }
    </style>
  </x-slot:head>
  <a href="{{ url('dashboard') }}" role="button">Kembali</a>
  <h1>Kelola Ujian</h1>
  <a href="{{ url('dashboard/create-exam') }}" role="button">Buat</a>
  <div>
    @forelse($exams as $exam)
    <div class="exam-card">
      <div class="col">
        <h2>{{ $exam->judul }}</h2>
        <p>{{ Str::limit($exam->deskripsi, 48) }}</p>
      </div>
      <div class="card-actions">
        <a href="{{ url("dashboard/edit-exam/$exam->id") }}" role="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
        </a>
        <a href="{{ url("dashboard/exam-result/$exam->id") }}" role="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
        </a>
        <form action="{{ url("dashboard/delete-exam/$exam->id") }}" method="post">
          @csrf
          @method('delete')
          <button type="submit" onclick="return confirm('Yakin ingin menghapus {{ $exam->judul }}?');">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
              <line x1="10" y1="11" x2="10" y2="17"/>
              <line x1="14" y1="11" x2="14" y2="17"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
    @empty
    <span>-- <i>Kosong</i> --</span>
    @endforelse
  </div>
</x-base>