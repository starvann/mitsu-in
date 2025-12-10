<x-base title="Hasil Ujian" main-class="page col-center">
  <div class="top-section">
      <div class="cover" style="background-image: url('{{ url('assets/img/cover-japan.jpg') }}')">
        <div class="avatar-wrapper">
          <img id="studentAvatar" src="{{ url(auth()->user()->gmb_profil) }}" />
        </div>
      </div>
      <div class="header-block">
        <div id="studentName" class="student-name">{{ auth()->user()->nama }}</div>
      </div>
  </div>
  <h1 style="color: #7b0000;">{{ $judul }}</h1>
  <div class="col-center">
    <div style="background-color: white; padding: 16px; border-radius: 8px;">
      <svg xmlns="http://www.w3.org/2000/svg" id="percentageCircle" width="200" height="200" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="45" fill="none" stroke="#e0e0e0" stroke-width="10"/>
        <circle id="progressCircle" cx="50" cy="50" r="45" fill="none" stroke="{{ $color }}" stroke-width="10" transform="rotate(-90 50 50)" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"/>
        <text x="50" y="50" text-anchor="middle" dy="0.3em" font-family="Arial, sans-serif" font-size="16" fill="{{ $color }}">{{ $score }}</text>
      </svg>
    </div>
    <p>Nilai : {{ $score }}</p>
    <p>Total Benar : {{ $correct }}</p>
    <p>Total Salah : {{ $wrong }}</p>
    <a href="{{ url('dashboard') }}" role="button">Kembali</a>
  </div>
</x-base>