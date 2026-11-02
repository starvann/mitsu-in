<x-base title="Profil Siswa" main-class="page" style="display: flex; flex-direction: column; gap: 8px;">
  <x-slot:head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
  </x-slot:head>
  @php $userDetail = $user->userDetail; @endphp
  <div class="top-section">
    <div class="cover" style="background-image: url('{{ url('assets/img/cover-japan.jpg') }}')">
      <div class="avatar-wrapper">
        <img id="studentAvatar" src="{{ url("/assets/profiles/".$user->gambar_profil) }}" />
      </div>
    </div>
    <div class="header-block">
      <div id="studentName" class="student-name">{{ $user->nama }}</div>
    </div>
    @if($user->role === 'stdn' and $user->status === 'accepted')
    <div class="status-row">
      <div class="progress-box" id="presenceBox"></div>
    </div>
    @endif
  </div>
  @if(session('success'))
  <div class="success-msg">
    {{ session('success') }}
  </div>
  @endif
  @if($user->role == 'stdn')
  <div class="col" style="width: 300px; align-self: center;">
    <div class="btn-group">
      <a href="{{ $back_url }}" role="button">Kembali</a>
      <a href="{{ url("/dashboard/presences/$user->id") }}" role="button">Presensi</a>
      <a href="{{ url("/dashboard/edit-user/$user->id") }}" role="button">Edit</a>
    </div>
    <a href="{{ url("view-payment/$user->id") }}" role="button" style="width: 100%;">Bukti Pembayaran</a>
  </div>
  @else
  <div class="btn-group" style="width: 300px; align-self: center;">
    <a href="{{ $back_url }}" role="button">Kembali</a>
    <a href="{{ url("/dashboard/edit-user/$user->id") }}" role="button">Edit</a>
  </div>
  @endif
  <div>
    <form action="{{ url('/dashboard/edit-user/'.$user->id) }}" method="post" id="edit-user">
      @csrf
      @method('PUT')
      <input type="hidden" name="stat_only" value="true">
      <div class="user-details">
        <span>Status</span>
        <select name="stat" onchange="query('#edit-user').submit()">
          <option value="pending" @selected($user->status == 'pending')>Proses Daftar Ulang</option>
          <option value="accepted" @selected($user->status != 'pending')>Terdaftar</option>
        </select>
      </div>
      <div class="user-details">
        <span>
          Role
        </span>
        <p>
          {{ $user->role == 'admn' ? 'Admin' : ($user->role == 'refl' ? 'Referrer' : 'Siswa') }}
        </p>
      </div>
      @if($user->role !== 'admn')
      <div class="user-details">
        <span>
          Kode Referral
        </span>
        <p style="font-family: 'Roboto Mono', monospace; font-size: 16px;">
          @if($user->kode_ref) <a href="{{ url("dashboard/view-user/".$referrer_id) }}">{{ $user->kode_ref }}</a> @else {{ $user->kode_ref_saya ?? '-' }} @endif
        </p>
      </div>
      @endif
      @if($user->role === 'stdn')
      <div class="user-details">
        <span>No. Handphone</span>
        <p>{{ $user->no_hp }}</p>
      </div>
      <div class="user-details">
        <span>Umur</span>
        <p>{{ $user->umur }} Tahun</p>
      </div>
      <div class="user-details">
        <span>Jenis Kelamin</span>
        <p>{{ $userDetail->gender ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Tinggi Badan</span>
        <p>{{ $userDetail->tinggi_badan ?? "?" }} cm</p>
      </div>
      <div class="user-details">
        <span>Berat Badan</span>
        <p>{{ $userDetail->berat_badan ?? "?" }} kg</p>
      </div>
      <div class="user-details">
        <span>Status Pernikahan</span>
        <p>{{ $userDetail->status_pernikahan ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Golongan Darah</span>
        <p>{{ $userDetail->golongan_darah ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Agama</span>
        <p>{{ $userDetail->agama ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Pernah ke Jepang</span>
        <p>{{ $userDetail->pernah_ke_jepang ?? false ? 'Ya' : 'Tidak' }}</p>
      </div>
      <div class="user-details">
        <span>Punya Paspor</span>
        <p>{{ $userDetail->punya_paspor ?? false ? 'Ya' : 'Tidak' }}</p>
      </div>
      <div class="user-details">
        <span>Tangan Ahli</span>
        <p>{{ ucfirst($userDetail->tangan_utama ?? "?") }}</p>
      </div>
      <div class="user-details">
        <span>Alamat Lengkap</span>
        <p>{{ $userDetail->alamat ?? "?" }}</p>
      </div>
      <div class="section-divider"></div>
      <span class="details-title">Pendidikan</span>
      <div class="details-group">
        @foreach($userDetail->pendidikan ?? [] as $pend)
        <div>
          <div class="user-details">
            <span>Tahun Lulus</span>
            <p>{{ $pend['tahun'] }}</p>
          </div>
          <div class="user-details">
            <span>Nama Sekolah</span>
            <p>{{ $pend['nama_sekolah'] }}</p>
          </div>
          <div class="user-details">
            <span>Jurusan</span>
            <p>{{ $pend['jurusan'] }}</p>
          </div>
        </div>
        @endforeach
      </div>
      <span class="details-title">Pengalaman</span>
      <div class="user-details">
        @forelse($userDetail->pengalaman ?? [] as $exp)
        <p>{{ $exp }}</p>
        @empty
        <p class="empty">-- Tidak ada --</p>
        @endforelse
      </div>
      <span class="details-title">Struktur Keluarga</span>
      <div class="details-group">
        @foreach($userDetail->struktur_keluarga ?? [] as $fam)
        <div>
          <div class="user-details">
            <span>Hubungan/Relasi</span>
            <p>{{ $fam['relasi'] }}</p>
          </div>
          <div class="user-details">
            <span>Nama</span>
            <p>{{ $fam['nama'] }}</p>
          </div>
          <div class="user-details">
            <span>Umur</span>
            <p>{{ $fam['umur'] }} Tahun</p>
          </div>
          <div class="user-details">
            <span>Pekerjaan</span>
            <p>{{ $fam['pekerjaan'] }}</p>
          </div>
          <div class="user-details">
            <span>Gaji (perkiraan per bulan)</span>
            <p>{{ $fam['gaji'] }}</p>
          </div>
        </div>
        @endforeach
      </div>
      <div class="user-details">
        <span>Tujuan ke Jepang</span>
        <p>{{ $userDetail->tujuan_ke_jepang ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Tujuan setelah kembali dari Jepang</span>
        <p>{{ $userDetail->tujuan_stlh_kembali ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Kelebihan</span>
        <p>{{ $userDetail->kelebihan ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Kekurangan</span>
        <p>{{ $userDetail->kekurangan ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Hobi</span>
        <p>{{ $userDetail->hobi ?? "?" }}</p>
      </div>
      <div class="user-details">
        <span>Punya Sertifikat JLPT</span>
        <p>{{ $userDetail->sertif_jlpt ?? "Tidak ada" }}</p>
      </div>
      <div class="user-details">
        <span>Punya SIM A</span>
        <p>{{ $userDetail->punya_sim_a ?? false ? 'Ya' : 'Tidak' }}</p>
      </div>
      <div class="user-details">
        <span>Sertifikat Lain</span>
        <p @if(!$userDetail?->sertif_lain) class="empty" @endif>{{ $userDetail->sertif_lain ?? '-- Tidak Ada --' }}</p>
      </div>
      <span class="details-title">Relasi di Jepang</span>
      @if(!empty($userDetail->relasi_di_jepang))
      <div class="details-group">
        <div>
          <div class="user-details">
            <span>Nama</span>
            <p>{{ $userDetail->relasi_di_jepang['nama'] }}</p>
          </div>
          <div class="user-details">
            <span>Hubungan/Relasi</span>
            <p>{{ $userDetail->relasi_di_jepang['hubungan'] }}</p>
          </div>
          <div class="user-details">
            <span>Pekerjaan</span>
            <p>{{ $userDetail->relasi_di_jepang['pekerjaan'] }}</p>
          </div>
          <div class="user-details">
            <span>Umur</span>
            <p>{{ $userDetail->relasi_di_jepang['usia'] }} Tahun</p>
          </div>
          <div class="user-details">
            <span>Alamat</span>
            <p>{{ $userDetail->relasi_di_jepang['alamat'] }}</p>
          </div>
        </div>
      </div>
      @else
      <p class="empty">-- Tidak Punya --</p>
      @endif
      @if($userDetail?->catatan_extra != null)
      <div class="user-details">
        <span>Catatan Extra</span>
        <p>{{ $userDetail->catatan_extra }}</p>
      </div>
      @endif
      @endif
      @isset($ref_users_count)
        <div class="user-details">
          <span>Jumlah Pengguna Kode Ref.</span>
          <p>{{ $ref_users_count }}</p>
        </div>
      @endisset
    </form>

    <form action="{{ url("/dashboard/del-user/$user->id") }}" method="post">
      @csrf
      @method('DELETE')
      <button type="submit" onclick="return confirm('Yakin?')">Hapus</button>
    </form>
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
