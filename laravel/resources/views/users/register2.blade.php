<x-base title="Register Siswa" body-class="screen-auth" main-class="auth-page">
  <x-slot:head>
    <style>
      /* Styling tambahan khusus dynamic group */
      .repeat-group {
        padding: 16px;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-bottom: 16px;
        background: #fafafa;
      }

      .remove-btn {
        background: #c62828;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        margin-top: 6px;
        cursor: pointer;
        font-size: 13px;
      }

      .btn-add {
        margin-top: 12px;
      }
    </style>
  </x-slot:head>
  <h1 class="title-jp">こんにちは!</h1>
  <p class="subtitle">
    Siap memulai perjalanan kariermu? Yuk, gabung bareng kami!
  </p>
  @if(!empty($errors->all()))
  <div class="err-messages">
    @foreach($errors->all() as $err_msg)
    <p>{{ $err_msg }}</p>
    @endforeach
  </div>
  @endif
  <form action="{{ url('register2') }}" method="post" enctype="multipart/form-data" class="card auth-card">
    @csrf
    {{-- Login Credential --}}
    <input type="hidden" name="email" value="{{ old('email') }}">
    <input type="hidden" name="password" value="{{ session('password') }}">
    <input type="hidden" name="code" value="{{ old('code') }}">

    <label for="kode_ref">
      Kode Referral
      <input type="text" name="kode_ref" id="kode_ref" placeholder="Kode Referral..." @error('kode_ref') aria-invalid="true" @enderror value="{{ old('kode_ref') }}">
    </label>
    <label for="nama">
      Nama Lengkap
      <input type="text" name="nama" placeholder="Nama..." @error('nama') aria-invalid="true" @enderror value="{{ old('nama') }}" required>
    </label>
    <label for="gmb_profil">
      Foto Profil
      <input type="file" name="gmb_profil" @error('gmb_profil') aria-invalid="true" @enderror accept="image/png,image/jpeg,image/webp" required>
    </label>
    <img src="" alt="Preview" id="preview_gmb_profil" style="max-width: 128px; border-radius: 50%; display: none;">
    <label for="no_hp">
      No. Handphone
      <input type="text" name="no_hp" placeholder="No. HP..." @error('no_hp') aria-invalid="true" @enderror value="{{ old('no_hp') }}" required>
    </label>
    <label for="gender">
    Jenis Kelamin
      <select name="gender" id="gender" @error('gender') aria-invalid="true" @enderror>
        <option value="laki-laki" @selected(old('gender') != 'perempuan')>Laki-Laki</option>
        <option value="perempuan" @selected(old('gender') == 'perempuan')>Perempuan</option>
      </select>
    </label>
    <label for="umur">
      Usia
      <input type="number" name="umur" id="umur" placeholder="Usia..." inputmode="numeric" min="15" @error('umur') aria-invalid="true" @enderror value="{{ old('umur') }}" required>
    </label>
    <label for="tinggi_badan">
      Tinggi Badan (cm)
      <input type="text" name="tinggi_badan" id="tinggi_badan" placeholder="Tinggi Badan..." inputmode="numeric" @error('tinggi_badan') aria-invalid="true" @enderror value="{{ old('tinggi_badan') }}" required>
    </label>
    <label for="berat_badan">
      Berat Badan (kg)
      <input type="text" name="berat_badan" id="berat_badan" placeholder="Berat Badan..." inputmode="numeric" @error('berat_badan') aria-invalid="true" @enderror value="{{ old('berat_badan') }}" required>
    </label>

    <div class="section-divider"></div>
    <h2 class="section-title">Kondisi & Latar Belakang</h2>

    <label for="pernah_menikah">
      Pernah Menikah
      <select name="pernah_menikah" id="pernah_menikah" @error('pernah_menikah') aria-invalid="true" @enderror>
        <option value="1" @selected(old('pernah_menikah') == true)>Pernah Menikah</option>
        <option value="0" @selected(!old('pernah_menikah'))>Belum Pernah Menikah</option>
      </select>
    </label>
    <label for="gol_darah">
      Golongan Darah
      <input type="text" name="gol_darah" id="gol_darah" placeholder="Golongan Darah..." @error('gol_darah') aria-invalid="true" @enderror value="{{ old('gol_darah') }}" required>
    </label>
    <label for="agama">
      <input type="text" name="agama" id="agama" placeholder="Agama..." @error('agama') aria-invalid="true" @enderror value="{{ old('agama') }}" required>
    </label>
    <label for="pernah_ke_jepang">
      Pernah ke Jepang
      <select name="pernah_ke_jepang" id="pernah_ke_jepang" @error('pernah_ke_jepang') aria-invalid="true" @enderror>
        <option value="1" @selected(old('pernah_ke_jepang') == true)>Pernah ke Jepang</option>
        <option value="0" @selected(!old('pernah_ke_jepang'))>Belum Pernah ke Jepang</option>
      </select>
    </label>
    <label for="punya_paspor">
      Punya Paspor
      <select name="punya_paspor" id="punya_paspor" @error('punya_paspor') aria-invalid="true" @enderror>
        <option value="1" @selected(old('punya_paspor') == true)>Punya Paspor</option>
        <option value="0" @selected(!old('punya_paspor'))>Belum Punya Paspor</option>
      </select>
    </label>
    <label for="tangan_utama">
      Tangan Ahli
      <select name="tangan_utama" @error('tangan_utama') aria-invalid="true" @enderror>
        <option value="kanan" @selected(old('tangan_utama') == 'kanan' or !old('tangan_utama'))>Kanan</option>
        <option value="kiri" @selected(old('tangan_utama') == 'kiri')>Kiri</option>
        <option value="kiri" @selected(old('tangan_utama') == 'keduanya')>Keduanya</option>
      </select>
    </label>

    <div class="section-divider"></div>
    <h2 class="section-title">Alamat & Pendidikan</h2>

    <label for="alamat">
      Alamat Lengkap
      <textarea name="alamat" id="alamat" rows="10" placeholder="Alamat..." @error('alamat') aria-invalid="true" @enderror>{{ old('alamat') }}</textarea>
    </label>
    <div class="subsection">
      <h2 class="subsection-title">Pendidikan</h2>
      <div id="edus">
        @forelse((old('pendidikan') ?? []) as $i => $edu)
        <div data-edu-idx="{{ $i }}" class="repeat-group edu-group">
          <label>
            Tahun Lulus
            <input type="text" name="pendidikan[{{ $i }}][tahun]" inputmode="numeric" placeholder="Tahun..." value="{{ $edu['tahun'] }}" required>
          </label>
          <label>
            Nama Sekolah
            <input type="text" name="pendidikan[{{ $i }}][nama_sekolah]" placeholder="Nama Sekolah..." value="{{ $edu['nama_sekolah'] }}" required>
          </label>
          <label>
            Jurusan
            <input type="text" name="pendidikan[{{ $i }}][jurusan]" placeholder="Jurusan..." value="{{ $edu['jurusan'] }}" required>
          </label>
          <button type="button" class="remove-btn">Hapus</button>
        </div>
        @empty
        <div data-edu-idx="0" class="repeat-group edu-group">
          <label>
            Tahun Lulus
            <input type="text" name="pendidikan[0][tahun]" inputmode="numeric" placeholder="Tahun..." required>
          </label>
          <label>
            Nama Sekolah
            <input type="text" name="pendidikan[0][nama_sekolah]" placeholder="Nama Sekolah..." required>
          </label>
          <label>
            Jurusan
            <input type="text" name="pendidikan[0][jurusan]" placeholder="Jurusan..." required>
          </label>
          <button type="button" class="remove-btn" >Hapus</button>
        </div>
        @endforelse
      </div>
      <button type="button" onclick="edu_add()" class="btn-primary btn-small btn-add">Tambah Informasi</button>
    </div>
    <div class="subsection">
      <h2 class="subsection-title">Pengalaman</h2>
      <div id="exps">
        @forelse((old('pengalaman') ?? []) as $exp)
        <div class="repeat-group exp-group">
          <label>
            Pengalaman Kerja / Magang
            <input type="text" name="pengalaman[]" placeholder="Pengalaman..." value="{{ $exp }}">
          </label>
          <button type="button" class="remove-btn">Hapus</button>
        </div>
        @empty
        <div class="repeat-group exp-group">
          <label>
            Pengalaman Kerja / Magang
            <input type="text" name="pengalaman[]" placeholder="Pengalaman...">
          </label>
          <button type="button" class="remove-btn">Hapus</button>
        </div>
        @endforelse
      </div>
      <button type="button" onclick="exp_add()" class="btn-primary btn-small btn-add">Tambah Informasi</button>
    </div>

    <div class="section-divider"></div>
    <h2 class="section-title">Keluarga & Tujuan</h2>


    <div class="subsection">
      <h2 class="subsection-title">Susunan Keluarga (utama)</h2>
      <div id="fams">
        @forelse((old('struktur_keluarga') ?? []) as $i => $fam)
        <div data-fam-idx="{{ $i }}" class="repeat-group family-group">
          <label>
            Hubungan Keluarga
            <input type="text" name="struktur_keluarga[{{ $i }}][relasi]" placeholder="Hubungan..." value="{{ $fam['relasi'] }}" required>
          </label>
          <label>
            Nama Lengkap
            <input type="text" name="struktur_keluarga[{{ $i }}][nama]" placeholder="Nama..." value="{{ $fam['nama'] }}" required>
          </label>
          <label>
            Usia
            <input type="number" name="struktur_keluarga[{{ $i }}][umur]" inputmode="numeric" placeholder="Usia..." value="{{ $fam['umur'] }}" required>
          </label>
          <label>
            Pekerjaan
            <input type="text" name="struktur_keluarga[{{ $i }}][pekerjaan]" placeholder="Pekerjaan..." value="{{ $fam['pekerjaan'] }}" required>
          </label>
          <label>
            Gaji (perkiraan per bulan)
            <input type="text" name="struktur_keluarga[{{ $i }}][gaji]" placeholder="Gaji..." value="{{ $fam['gaji'] }}" required>
          </label>
          <button type="button" class="remove-btn">Hapus</button>
        </div>
        @empty
        <div data-fam-idx="0" class="repeat-group family-group">
          <label>
            Hubungan Keluarga
            <input type="text" name="struktur_keluarga[0][relasi]" placeholder="Hubungan..." required>
          </label>
          <label>
            Nama Lengkap
            <input type="text" name="struktur_keluarga[0][nama]" placeholder="Nama..." required>
          </label>
          <label>
            Usia
            <input type="number" name="struktur_keluarga[0][umur]" inputmode="numeric" placeholder="Usia..." required>
          </label>
          <label>
            Pekerjaan
            <input type="text" name="struktur_keluarga[0][pekerjaan]" placeholder="Pekerjaan..." required>
          </label>
          <label>
            Gaji (perkiraan per bulan)
            <input type="text" name="struktur_keluarga[0][gaji]" placeholder="Gaji..." required>
          </label>
          <button type="button" class="remove-btn">Hapus</button>
        </div>
        @endforelse
      </div>
      <button type="button" id="fam_add" class="btn-primary btn-small btn-add">Tambah Informasi</button>
    </div>
    <label>
      Tujuan ke Jepang
      <input type="text" name="tujuan_ke_jepang" placeholder="Tujuan ke Jepang..." @error('tujuan_ke_jepang') aria-invalid="true" @enderror value="{{ old('tujuan_ke_jepang') }}" required>
    </label>

    <div class="section-divider"></div>
    <h2 class="section-title">Rencana & Sertifikat</h2>

    <label>
      Setelah pulang dari Jepang, rencana apa?
      <input type="text" name="tujuan_stlh_kembali" placeholder="Setelah Pulang dari Jepang..." @error('tujuan_stlh_kembali') aria-invalid="true" @enderror value="{{ old('tujuan_stlh_kembali') }}" required>
    </label>
    <label>
      Kelebihan
      <input type="text" name="kelebihan" placeholder="Kelebihan..." @error('stengths') aria-invalid="true" @enderror value="{{ old('kelebihan') }}" required>
    </label>
    <label>
      Kekurangan
      <input type="text" name="kekurangan" placeholder="Kekurangan..." @error('kekurangan') aria-invalid="true" @enderror value="{{ old('kekurangan') }}" required>
    </label>
    <label>
      Hobi
      <input type="text" name="hobi" placeholder="Hobi..." @error('hobi') aria-invalid="true" @enderror value="{{ old('hobi') }}" required>
    </label>
    <div class="subsection">
      <h2 class="subsection-title">Sertifikat yang Dimiliki</h2>
      <label>
        Punya JLPT/Setara
        <select name="punya_sertif_jlpt" @error('punya_sertif_jlpt') aria-invalid="true" @enderror>
          <option value="1" @selected(old('punya_sertif_jlpt') == true)>Punya JLPT/Setara</option>
          <option value="0" @selected(!old('punya_sertif_jlpt'))>Belum Punya JLPT/Setara</option>
        </select>
      </label>
      <label>
        Punya SIM A
        <select name="punya_sim_a" @error('punya_sim_a') aria-invalid="true" @enderror>
          <option value="1" @selected(old('punya_sim_a') == true)>Punya SIM A</option>
          <option value="0" @selected(!old('punya_sim_a'))>Belum Punya SIM A</option>
        </select>
      </label>
      <label>
        Lain-lain
        <input type="text" name="sertif_lain" @error('sertif_lain') aria-invalid="true" @enderror placeholder="Sertifikat Lainnya..." value="{{ old('sertif_lain') }}">
      </label>
    </div>

    <div class="section-divider"></div>
    <h2 class="section-title">Kerabat / Kenalan di Jepang</h2>

    <label>
      Nama Kerabat/Kenalan di Jepang
      <input type="text" name="relasi_di_jepang[nama]" placeholder="Nama..." @error('jp_relasi.nama') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.nama') }}">
    </label>
    <label>
      Hubungan Kerabat/Kenalan di Jepang
      <input type="text" name="relasi_di_jepang[relasi]" placeholder="Hubungan..." @error('jp_relasi.relasi') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.relasi') }}">
    </label>
    <label>
      Pekerjaan Kerabat/Kenalan di Jepang
      <input type="text" name="relasi_di_jepang[pekerjaan]" placeholder="Pekerjaan..." @error('jp_relasi.pekerjaan') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.pekerjaan') }}">
    </label>
    <label>
      Usia Kerabat/Kenalan di Jepang
      <input type="text" name="relasi_di_jepang[umur]" placeholder="Usia..." @error('jp_relasi.umur') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.umur') }}">
    </label>
    <label>
      Alamat Kerabat/Kenalan di Jepang
      <input type="text" name="relasi_di_jepang[alamat]" placeholder="Alamat di Jepang..." @error('jp_relasi.alamat') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.alamat') }}">
    </label>
    <label>
      Catatan Tambahan
      <textarea name="catatan_xtra" rows="10" @error('catatan_xtra') aria-invalid="true" @enderror placeholder="Catatan Tambahan...">{{ old('catatan_xtra') }}</textarea>
    </label>
    <button type="submit" class="btn-primary" style="margin-top: 16px">Register</button>
  </form>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    function createElement(tag, prop = {}) {
      return Object.assign(document.createElement(tag), prop);
    }
    let edus = query('#edus');
    let exps = query('#exps');
    let fams = query('#fams');
    function edu_add() {
      let eduIdx = parseInt(edus.lastElementChild.dataset.eduIdx) + 1;
      let edu = createElement('div', {
        innerHTML: `<label>
            Tahun Lulus
            <input type="text" name="pendidikan[${eduIdx}][tahun]" inputmode="numeric" placeholder="Tahun..." required>
          </label>
          <label>
            Nama Sekolah
            <input type="text" name="pendidikan[${eduIdx}][nama_sekolah]" placeholder="Nama Sekolah..." required>
          </label>
          <label>
            Jurusan
            <input type="text" name="pendidikan[${eduIdx}][jurusan]" placeholder="Jurusan..." required>
          </label>
          <button type="button" class="remove-btn">Hapus</button>`,
        className: 'repeat-group edu-group'
      });
      edu.dataset.eduIdx = eduIdx;
      edus.appendChild(edu);
    }
    function exp_add() {
      exps.appendChild(createElement('div', {
        innerHTML: `<label>
            Pengalaman Kerja / Magang
            <input type="text" name="pengalaman[]" placeholder="Pengalaman...">
          </label>
          <button type="button" class="remove-btn">Hapus</button>`,
        className: 'repeat-group exp-group'
      }));
    }
    function fam_add() {
      let famIdx = parseInt(fams.lastElementChild.dataset.famIdx) + 1;
      let fam = createElement('div', {
        innerHTML: `<label>
            Hubungan Keluarga
            <input type="text" name="struktur_keluarga[${famIdx}][relasi]" placeholder="Hubungan..." required>
          </label>
          <label>
            Nama Lengkap
            <input type="text" name="struktur_keluarga[${famIdx}][nama]" placeholder="Nama..." required>
          </label>
          <label>
            Usia
            <input type="number" name="struktur_keluarga[${famIdx}][umur]" inputmode="numeric" placeholder="Usia..." required>
          </label>
          <label>
            Pekerjaan
            <input type="text" name="struktur_keluarga[${famIdx}][pekerjaan]" placeholder="Pekerjaan..." required>
          </label>
          <label>
            Gaji (perkiraan per bulan)
            <input type="text" name="struktur_keluarga[${famIdx}][gaji]" placeholder="Gaji..." required>
          </label>
          <button type="button" class="remove-btn">Hapus</button>`,
        className: 'repeat-group family-group'
      });
      fam.dataset.famIdx = famIdx;
      fams.appendChild(fam);
    }
  </script>
</x-base>