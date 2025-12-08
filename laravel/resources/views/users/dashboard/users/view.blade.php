<x-base title="Profil Siswa">
  <div>
    <a href="{{ url('/dashboard/students') }}">Kembali</a>
    <div>
      <img src="{{ url($user->gmb_profil) }}" alt="Gambar Profil">
    </div>
    @if($user->role == 'stdn')
    <img src="{{ url('presence/percentage/'.$user->id) }}" alt="Presentase Presensi">
    @endif
    <form action="{{ url('/dashboard/edit-user/'.$user->id) }}" method="post">
      @csrf
      @method('put')
      <p>Status</p>
      <select name="stat" onchange="this.parentElement.submit()">
        <option value="pending" @selected($user->stat == 'pending')>Proses Daftar Ulang</option>
        <option value="accepted" @selected($user->stat != 'pending')>Terdaftar</option>
      </select>
      <p>
        Kode Referal : @if($user->kode_ref) <a href="{{ url("dashboard/view-user/".$referrer_id) }}">{{ $user->kode_ref }}</a> @else {{ $user->kode_ref_saya ?? '-' }} @endif
      </p>
      <p>Nama : {{ $user->nama }}</p>
      <p>No. HP : {{ $user->no_hp }}</p>
      <p>Umur : {{ $user->umur }}</p>
      <p>Role : {{ $user->role == 'admn' ? 'admin' : ($user->role == 'refl' ? 'referral' : 'siswa') }}</p>
      <p>Gender : {{ $user->gender }}</p>
      <p>Tinggi Badan : {{ $user->tinggi_badan }} cm</p>
      <p>Berat Badan : {{ $user->berat_badan }} kg</p>
      <p>Pernah Menikah : {{ $user->pernah_menikah ? 'Ya' : 'Tidak' }}</p>
      <p>Golongan Darah : {{ $user->gol_darah }}</p>
      <p>Agama : {{ $user->agama }}</p>
      <p>Pernah ke Jepang : {{ $user->pernah_ke_jepang ? 'Ya' : 'Tidak' }}</p>
      <p>Punya Paspor : {{ $user->punya_paspor ? 'Ya' : 'Tidak' }}</p>
      <p>Tangan Ahli : {{ $user->tangan_utama }}</p>
      <p>Alamat : {{ $user->alamat }}</p>
      <span>Pendidikan</span>
      @foreach($user->pendidikan as $pend)
      <p>
        Tahun : {{ $pend['tahun'] }} <br>
        Nama Sekolah : {{ $pend['nama_sekolah'] }} <br>
        Jurusan : {{ $pend['jurusan'] }}
      </p>
      @endforeach
      <span>Pengalaman</span>
      @forelse($user->pengalaman as $exp)
      <p>{{ $exp }}</p>
      @empty
      <p>-- Tidak ada --</p>
      @endforelse
      <span>Struktur Keluarga</span>
      @foreach($user->struktur_keluarga as $fam)
      <p>
        Hubungan/Relasi : {{ $fam['relasi'] }} <br>
        Nama : {{ $fam['nama'] }} <br>
        Umur : {{ $fam['umur'] }} <br>
        Pekerjaan : {{ $fam['pekerjaan'] }} <br>
        Gaji : {{ $fam['gaji'] }}
      </p>
      @endforeach
      <p>Tujuan ke Jepang : {{ $user->tujuan_ke_jepang }}</p>
      <p>Tujuan setelah Kembali dari Jepang : {{ $user->tujuan_stlh_kembali }}</p>
      <p>Kelebihan : {{ $user->kelebihan }}</p>
      <p>Kekurangan : {{ $user->kekurangan }}</p>
      <p>Hobi : {{ $user->hobi }}</p>
      <p>Punya Sertifikat JLPT : {{ $user->punya_sertif_jlpt ? 'Ya' : 'Tidak' }}</p>
      <p>Punya SIM A : {{ $user->punya_sim_a ? 'Ya' : 'Tidak' }}</p>
      <p>Sertifikat Lain : {{ $user->sertif_lain }}</p>
      <span>Relasi di Jepang</span>
      @if($user->relasi_di_jepang != null)
      <p>
        Nama : {{ $user->relasi_di_jepang['nama'] }} <br>
        Hubungan/Relasi : {{ $user->relasi_di_jepang['relasi'] }} <br>
        Pekerjaan : {{ $user->relasi_di_jepang['pekerjaan'] }} <br>
        Umur : {{ $user->relasi_di_jepang['umur'] }} <br>
        Alamat : {{ $user->relasi_di_jepang['alamat'] }} <br>
      </p>
      @else
      <p>-- Tidak Ada --</p>
      @endif
      @if($user->catatan_xtra != null)
      <p>Catatan Extra : {{ $user->catatan_xtra }}</p>
      @endif
    </div>
    @isset($ref_users_count)
      <p>Jumlah Pengguna Kode Referral : {{ $ref_users_count }}</p>
    @endisset
  </div>
</x-base>