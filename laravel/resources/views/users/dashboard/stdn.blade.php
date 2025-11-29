<x-base title="Dashboard">
  <div class="container">
    <div>
      <img src="{{ url($user->gmb_profil) }}" width="100" alt="Gambar Profil">
      <h1>{{ $user->nama }}</h1>
      <a href="{{ url('logout') }}" role="button">Log out</a>
    </div>
    @if($user->stat == 'accepted')
    <div>
      @if(!$hasPresence)
      <a href="{{ url('presence/?token='.$token) }}" style="display: flex; flex-direction: column; gap: 0.5rem;">
        <img src="{{ url('presence-qr/'.$user->id) }}" width="150" type="image/svg+xml" alt="Kode QR">
        <small>Klik untuk presensi</small>
      </a>
      @endif
      <img src="{{ url('presence/percentage/'.$user->id) }}" alt="Presentase Presensi">
    </div>
    <div>
      @if(!$hasPresence)
      <form action="{{ url('presence') }}" method="post" enctype="multipart/form-data">
        <h2>Izin</h2>
        @csrf
        @if(!empty($errors->all()))
        <ul>
          @foreach($errors->all() as $err_msg)
          <li>{{ $err_msg }}</li>
          @endforeach
        </ul>
        @endif
        <select name="status" @error('status') aria-invalid="true" @enderror>
          <option value="sakit" @selected(old('status') != 'izin')>Sakit</option>
          <option value="izin" @selected(old('status') == 'izin')>Izin</option>
        </select>
        <textarea name="alasan" placeholder="Alasan yang mendukung..." rows="7" @error('alasan') aria-invalid="true" @enderror required>{{ old('alasan') }}</textarea>
        <input type="file" name="doc_xtra" accept="image/png,image/jpeg,image/webp,application/pdf" @error('doc_xtra') aria-invalid="true" @enderror required>
        <button type="submit">Submit</button>
      </form>
      @else
      <h2>Anda <mark>{{ $status }}</mark></h2>
      @endif
    </div>
    <div>
      <h2>Ujian yang bisa dikerjakan</h2>
      @forelse($exams as $exam)
      <div>
        <h3>{{ $exam->judul }}</h3>
        <p>{{ $exam->deskripsi }}</p>
        <a href="{{ url('exams/'.$exam->id) }}" role="button">Kerjakan</a>
      </div>
      @empty
      <span>-- <i>Kosong</i> --</span>
      @endforelse
    </div>
    @else
    <h2>Kamu masih proses daftar ulang.</h2>
    @endif
  </div>
</x-base>