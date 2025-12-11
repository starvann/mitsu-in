<x-base title="Profil Siswa" main-class="page" style="display: flex; flex-direction: column; gap: 8px;">
  <x-slot:head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
    <style>

    </style>
  </x-slot:head>
  <div class="top-section">
    <div class="cover" style="background-image: url('{{ url('assets/img/cover-japan.jpg') }}')">
      <div class="avatar-wrapper">
        <img id="studentAvatar" src="{{ url($user->gmb_profil) }}" />
      </div>
    </div>
    <div class="header-block">
      <div id="studentName" class="student-name">{{ $user->nama }}</div>
    </div>
    @if($user->role === 'stdn' and $user->stat === 'accepted')
    <div class="status-row">
      <div class="progress-box" id="presenceBox">
      </div>
    </div>
    @endif
  </div>
  @if(session('success'))
  <div class="success-msg">
    {{ session('success') }}
  </div>
  @endif
    <div class="btn-group" style="width: 300px; align-self: center;">
      <a href="{{ url('/dashboard/students') }}" role="button">Kembali</a>
      <a href="{{ url("/dashboard/del-user/$user->id") }}" role="button" onclick="return confirm('Yakin?')">Hapus</a>
    </div>
  <div>
    <form action="{{ url('/dashboard/edit-user/'.$user->id) }}" method="post">
      @csrf
      @method('put')
      <div class="user-details">
        <span>Status</span>
        <select name="stat" onchange="this.parentElement.parentElement.submit()">
          <option value="pending" @selected($user->stat == 'pending')>Proses Daftar Ulang</option>
          <option value="accepted" @selected($user->stat != 'pending')>Terdaftar</option>
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
        <p>{{ $user->gender }}</p>
      </div>
      <div class="user-details">
        <span>Tinggi Badan</span>
        <p>{{ $user->tinggi_badan }} cm</p>
      </div>
      <div class="user-details">
        <span>Berat Badan</span>
        <p>{{ $user->berat_badan }} kg</p>
      </div>
      <div class="user-details">
        <span>Status Pernikahan</span>
        <p>{{ $user->status_pernikahan }}</p>
      </div>
      <div class="user-details">
        <span>Golongan Darah</span>
        <p>{{ $user->gol_darah }}</p>
      </div>
      <div class="user-details">
        <span>Agama</span>
        <p>{{ $user->agama }}</p>
      </div>
      <div class="user-details">
        <span>Pernah ke Jepang</span>
        <p>{{ $user->pernah_ke_jepang ? 'Ya' : 'Tidak' }}</p>
      </div>
      <div class="user-details">
        <span>Punya Paspor</span>
        <p>{{ $user->punya_paspor ? 'Ya' : 'Tidak' }}</p>
      </div>
      <div class="user-details">
        <span>Tangan Ahli</span>
        <p>{{ $user->tangan_utama }}</p>
      </div>
      <div class="user-details">
        <span>Alamat Lengkap</span>
        <p>{{ $user->alamat }}</p>
      </div>
      <div class="section-divider"></div>
      <span class="details-title">Pendidikan</span>
      <div class="details-group">
        @foreach($user->pendidikan as $pend)
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
        @forelse($user->pengalaman as $exp)
        <p>{{ $exp }}</p>
        @empty
        <p class="empty">-- Tidak ada --</p>
        @endforelse
      </div>
      <span class="details-title">Struktur Keluarga</span>
      <div class="details-group">
        @foreach($user->struktur_keluarga as $fam)
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
        <p>{{ $user->tujuan_ke_jepang }}</p>
      </div>
      <div class="user-details">
        <span>Tujuan setelah kembali dari Jepang</span>
        <p>{{ $user->tujuan_stlh_kembali }}</p>
      </div>
      <div class="user-details">
        <span>Kelebihan</span>
        <p>{{ $user->kelebihan }}</p>
      </div>
      <div class="user-details">
        <span>Kekurangan</span>
        <p>{{ $user->kekurangan }}</p>
      </div>
      <div class="user-details">
        <span>Hobi</span>
        <p>{{ $user->hobi }}</p>
      </div>
      <div class="user-details">
        <span>Punya Sertifikat JLPT</span>
        <p>{{ $user->sertif_jlpt ?? "Tidak ada" }}</p>
      </div>
      <div class="user-details">
        <span>Punya SIM A</span>
        <p>{{ $user->punya_sim_a ? 'Ya' : 'Tidak' }}</p>
      </div>
      <div class="user-details">
        <span>Sertifikat Lain</span>
        <p @if(!$user->sertif_lain) class="empty" @endif>{{ $user->sertif_lain ?? '-- Tidak Ada --' }}</p>
      </div>
      <span class="details-title">Relasi di Jepang</span>
      @if(!empty($user->relasi_di_jepang))
      <div class="details-group">
        <div>
          <div class="user-details">
            <span>Nama</span>
            <p>{{ $user->relasi_di_jepang['nama'] }}</p>
          </div>
          <div class="user-details">
            <span>Hubungan/Relasi</span>
            <p>{{ $user->relasi_di_jepang['relasi'] }}</p>
          </div>
          <div class="user-details">
            <span>Pekerjaan</span>
            <p>{{ $user->relasi_di_jepang['pekerjaan'] }}</p>
          </div>
          <div class="user-details">
            <span>Umur</span>
            <p>{{ $user->relasi_di_jepang['umur'] }} Tahun</p>
          </div>
          <div class="user-details">
            <span>Alamat</span>
            <p>{{ $user->relasi_di_jepang['alamat'] }}</p>
          </div>
        </div>
      </div>
      @else
      <p class="empty">-- Tidak Punya --</p>
      @endif
      @if($user->catatan_xtra != null)
      <div class="user-details">
        <span>Catatan Extra</span>
        <p>{{ $user->catatan_xtra }}</p>
      </div>
      @endif
      @endif
    </div>
    @isset($ref_users_count)
      <div class="user-details">
        <span>Jumlah Pengguna Kode Ref.</span>
        <p>{{ $ref_users_count }}</p>
      </div>
    @endisset
  </div>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    let presenceBox = query("#presenceBox");
    async function renderPrecenceData() {
      let res = await fetch(`{{ url("/presence/percentage/$user->id") }}`, {credentials: 'include'});
      let data = await res.json();
      presenceBox.innerHTML = `
      ${data.svg}
      <p>${data.hadir} Hari hadir</p>
      <p>${data.alpha} Hari alpha</p>
      <p>${data.darurat} Hari darurat</p>
      <p>${data.izin} Hari izin</p>
      <p>${data.sakit} Hari sakit</p>
      <p>${data.tanggal}</p>`;
    }
    renderPrecenceData();
  </script>
</x-base>
