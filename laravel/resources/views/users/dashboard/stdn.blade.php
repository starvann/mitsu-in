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
      <div class="status-row">
        @if(!$hasPresence)
        <a class="qr-box" href="{{ url('presence/?token='.$token) }}">
          <img src="{{ url('presence-qr/'.$user->id) }}" type="image/svg+xml" alt="Kode QR"/>
        </a>
        @endif
        <div class="progress-box">
          <img src="{{ url('presence/percentage/'.$user->id) }}" type="image/svg+xml" alt="Presentase Presensi">
        </div>
      </div>
    </div>
    @if($user->stat === 'pending')
    <section class="card" style="margin-top: 16px">
      <h2 class="section-title">Status Pembayaran</h2>
      <div id="paymentStatusBox" class="payment-status pending">
        <span id="paymentStatusText">Menunggu konfirmasi</span>
      </div>
    </section>
    @endif
    <a href="{{ url('logout') }}">Logout</a>

    <!-- IZIN -->
    @if($user->stat === 'accepted')
      @if(!$hasPresence)
        @if(!empty($errors->all()))
        <ul class="err-messages">
          @foreach($errors->all() as $err_msg)
          <li>{{ $err_msg }}</li>
          @endforeach
        </ul>
        @endif
      <form class="card" action="{{ url('presence') }}" method="post" enctype="multipart/form-data">
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
        <div class="file-label">
          <span>📎 Upload Dokumen</span>
          <input type="file" name="doc_xtra" accept="image/*" @error('doc_xtra') aria-invalid="true" @enderror />
        </div>

        <button class="btn-primary" type="submit" style="margin-top: 14px">Submit</button>
      </form>
      @endif
    <div>
      @forelse($exams as $exam)
      <div class="izin-footer">
        <span>{{ $exam->judul }}</span>
        @php
        $has_done = $exam->examResults()->where('user_id', Auth::id())->exists();
        @endphp
        <a href="{{ url('exam/'.$exam->id) }}" class="button-secondary" role="button" @if(!$has_done) onclick="return confirm('Yakin?');" @endif>{{ $has_done ? 'Lihat Hasil' : 'Kerjakan' }}</a>
      </div>
      @empty
      @endforelse
    </div>
    @endif
  </div>
</x-base>