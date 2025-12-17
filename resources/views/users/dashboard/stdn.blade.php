<x-base title="Dashboard" main-class="page">
    <div class="top-section">
      <div class="cover" style="background-image: url('{{ url('assets/img/cover-japan.jpg') }}')">
        <div class="avatar-wrapper">
          <img id="studentAvatar" src="{{ url($user->gmb_profil) }}" />
        </div>
      </div>
      <div class="header-block">
        <div id="studentName" class="student-name">{{ $user->nama }}</div>
      </div>
      @if($user->stat === 'accepted')
      <div class="status-row">
        @if(!$hasPresence)
        <a class="qr-box" href="{{ url('presence/?token='.$token) }}">
          <img src="{{ url('presence-qr/'.$user->id) }}" type="image/svg+xml" alt="Kode QR"/>
        </a>
        @endif
        <div class="progress-box" id="presenceBox"></div>
      </div>
      @endif
    </div>
    @if(session('success'))
    <div class="success-msg">
      {{ session('success') }}
    </div>
    @endif
    <div style="max-width: 250px; margin: 16px auto;">
      <div class="btn-group">
        <a href="{{ url('logout') }}" role="button">Logout</a>
        <a href="{{ url("dashboard/edit-user/$user->id") }}" role="button">Edit Profil</a>
      </div>
      <a href="{{ url("dashboard/change-pass/$user->id") }}" role="button" style="width: 100%; margin-top: 16px;">Ganti Password</a>
    </div>

    <!-- IZIN -->
    @if($user->stat === 'accepted')
      @if(!$hasPresence)
        @if(!empty($errors->all()))
        <div class="err-messages">
          @foreach($errors->all() as $err_msg)
          <p>{{ $err_msg }}</p>
          @endforeach
        </div>
        @endif
      <form class="card" action="{{ url('presence') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h2 class="section-title">Izin</h2>
        <p class="subtitle">Sertakan dokumen yang mendukung.</p>
        <label>
          Alasan
          <select name="status" @error('status') aria-invalid="true" @enderror>
            <option value="sakit" @selected(!old('status') or old('status') === 'sakit')>Sakit</option>
            <option value="izin" @selected(old('status') === 'izin')>Izin</option>
            <option value="darurat" @selected(old('status') === 'darurat')>Darurat</option>
          </select>
        </label>
        <label>
          Alasan Mendukung
          <input type="text" name="alasan" @error('alasan') aria-invalid="true" @enderror value="{{ old('alasan') }}" />
        </label>
        <label>
          📎 Upload Dokumen
          <input type="file" name="doc_xtra" accept="image/*" @error('doc_xtra') aria-invalid="true" @enderror />
        </label>

        <button class="btn-primary" type="submit" style="margin-top: 14px">Submit</button>
      </form>
      @endif
    <div style="margin: 32px auto; padding: 16px; background-color: #e9e9e9; border-radius: 8px;">
      <h1 style="color: #7b0000; text-align: center; font-size: 20pt; margin-bottom: 16px;">Ujian</h1>
      @forelse($exams as $exam)
      <div class="student-exam-card">
        <span>{{ $exam->judul }}</span>
        @php
        $has_done = $exam->examResults()->where('user_id', Auth::id())->exists();
        @endphp
        <a href="{{ url('exam/'.$exam->id) }}" class="button-secondary" role="button" @if(!$has_done) onclick="return confirm('Yakin?');" @endif>{{ $has_done ? 'Lihat Hasil' : 'Kerjakan' }}</a>
      </div>
      @empty
      <span style="font-style: italic; color: gray; text-align: center; display: block;">-- Kosong --</span>
      @endforelse
    </div>
    @endif
  </div>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    let presenceBox = query("#presenceBox");
    async function renderPrecenceData() {
      let res = await fetch(`{{ url("/presence/percentage/$user->id") }}`);
      let data = await res.json();
      presenceBox.innerHTML = `
      ${data.svg}
      <div>
      <p>${data.hadir} Hari hadir</p>
      <p>${data.alpha} Hari alpha</p>
      <p>${data.darurat} Hari darurat</p>
      <p>${data.izin} Hari izin</p>
      <p>${data.sakit} Hari sakit</p>
      <p style="margin-top: 16px;">${data.tanggal}</p>
      </div>`;
    }
    renderPrecenceData();
  </script>
</x-base>
