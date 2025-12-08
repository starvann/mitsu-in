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
  <form action="{{ url('register2') }}" method="post" enctype="multipart/form-data">
    <h1>Register</h1>
    @csrf
    @if(!empty($errors->all()))
    <ul>
      @foreach($errors->all() as $err_msg)
      <li>{{ $err_msg }}</li>
      @endforeach
    </ul>
    @endif
    {{-- Login Credential --}}
    <input type="hidden" name="email" value="{{ old('email') }}">
    <input type="hidden" name="password" value="{{ session('password') }}">
    <input type="hidden" name="code" value="{{ old('code') }}">
    {{-- Part 1 --}}
    <label for="kode_ref">
      Kode Referral
      <input type="text" name="kode_ref" id="kode_ref" placeholder="Kode Referensi..." @error('kode_ref') aria-invalid="true" @enderror value="{{ old('kode_ref') }}">
    </label>
    <label for="nama">
      Nama Lengkap
      <input type="text" name="nama" placeholder="Nama..." @error('nama') aria-invalid="true" @enderror value="{{ old('nama') }}" required>
    </label>
    <label for="gmb_profil">
      Foto Profil
      <input type="file" name="gmb_profil" @error('gmb_profil') aria-invalid="true" @enderror accept="image/png,image/jpeg,image/webp" required>
    </label>
    <input type="text" name="no_hp" placeholder="No. HP..." @error('no_hp') aria-invalid="true" @enderror value="{{ old('no_hp') }}" required>
    <select name="gender" @error('gender') aria-invalid="true" @enderror>
      <option value="laki-laki" @selected(old('gender') != 'perempuan')>Laki-Laki</option>
      <option value="perempuan" @selected(old('gender') == 'perempuan')>Perempuan</option>
    </select>
    <input type="text" name="umur" placeholder="Usia..." inputmode="numeric" @error('umur') aria-invalid="true" @enderror value="{{ old('umur') }}" required>
    <input type="text" name="tinggi_badan" placeholder="Tinggi Badan..." inputmode="numeric" @error('tinggi_badan') aria-invalid="true" @enderror value="{{ old('tinggi_badan') }}" required>
    <input type="text" name="berat_badan" placeholder="Berat Badan..." inputmode="numeric" @error('berat_badan') aria-invalid="true" @enderror value="{{ old('berat_badan') }}" required>
    <select name="pernah_menikah" @error('pernah_menikah') aria-invalid="true" @enderror>
      <option value="1" @selected(old('pernah_menikah') == true)>Pernah Menikah</option>
      <option value="0" @selected(!old('pernah_menikah'))>Belum Pernah Menikah</option>
    </select>
    <input type="text" name="gol_darah" placeholder="Golongan Darah..." @error('gol_darah') aria-invalid="true" @enderror value="{{ old('gol_darah') }}" required>
    <input type="text" name="agama" placeholder="Agama..." @error('agama') aria-invalid="true" @enderror value="{{ old('agama') }}" required>
    <select name="pernah_ke_jepang" @error('pernah_ke_jepang') aria-invalid="true" @enderror>
      <option value="1" @selected(old('pernah_ke_jepang') == true)>Pernah ke Jepang</option>
      <option value="0" @selected(!old('pernah_ke_jepang'))>Belum Pernah ke Jepang</option>
    </select>
    <select name="punya_paspor" @error('punya_paspor') aria-invalid="true" @enderror>
      <option value="1" @selected(old('punya_paspor') == true)>Punya Paspor</option>
      <option value="0" @selected(!old('punya_paspor'))>Belum Punya Paspor</option>
    </select>
    <select name="tangan_utama" @error('tangan_utama') aria-invalid="true" @enderror>
      <option value="kanan" @selected(old('tangan_utama') != 'kiri')>Kanan</option>
      <option value="kiri" @selected(old('tangan_utama') == 'kiri')>Kiri</option>
    </select>
    {{-- Part 2 --}}
    <textarea name="alamat" rows="10" placeholder="Alamat..." @error('alamat') aria-invalid="true" @enderror>{{ old('alamat') }}</textarea>
    <div>
      <h2>Pendidikan</h2>
      <div id="edus">
        @forelse((old('pendidikan') ?? []) as $i => $edu)
        <div data-edu-idx="{{ $i }}">
          <input type="text" name="pendidikan[{{ $i }}][tahun]" inputmode="numeric" placeholder="Tahun..." value="{{ $edu['tahun'] }}" required>
          <input type="text" name="pendidikan[{{ $i }}][nama_sekolah]" placeholder="Nama Sekolah..." value="{{ $edu['nama_sekolah'] }}" required>
          <input type="text" name="pendidikan[{{ $i }}][jurusan]" placeholder="Jurusan..." value="{{ $edu['jurusan'] }}" required>
        </div>
        @empty
        <div data-edu-idx="0">
          <input type="text" name="pendidikan[0][tahun]" inputmode="numeric" placeholder="Tahun..." required>
          <input type="text" name="pendidikan[0][nama_sekolah]" placeholder="Nama Sekolah..." required>
          <input type="text" name="pendidikan[0][jurusan]" placeholder="Jurusan..." required>
        </div>
        @endforelse
      </div>
      <button type="button" onclick="edu_add()">Tambah Informasi</button>
    </div>
    <div>
      <h2>Pengalaman</h2>
      <div id="exps">
        @forelse((old('pengalaman') ?? []) as $exp)
        <div>
          <input type="text" name="pengalaman[]" placeholder="Pengalaman..." value="{{ $exp }}">
        </div>
        @empty
        <div>
          <input type="text" name="pengalaman[]" placeholder="Pengalaman...">
        </div>
        @endforelse
      </div>
      <button type="button" onclick="exp_add()">Tambah Informasi</button>
    </div>
    <div>
      <h2>Susunan Keluarga</h2>
      <div id="fams">
        @forelse((old('struktur_keluarga') ?? []) as $i => $fam)
        <div data-fam-idx="{{ $i }}">
          <input type="text" name="struktur_keluarga[{{ $i }}][relasi]" placeholder="Hubungan..." value="{{ $fam['relasi'] }}" required>
          <input type="text" name="struktur_keluarga[{{ $i }}][nama]" placeholder="Nama..." value="{{ $fam['nama'] }}" required>
          <input type="text" name="struktur_keluarga[{{ $i }}][umur]" inputmode="numeric" placeholder="Usia..." value="{{ $fam['umur'] }}" required>
          <input type="text" name="struktur_keluarga[{{ $i }}][pekerjaan]" placeholder="Pekerjaan..." value="{{ $fam['pekerjaan'] }}" required>
          <input type="text" name="struktur_keluarga[{{ $i }}][gaji]" placeholder="Gaji..." value="{{ $fam['gaji'] }}" required>
        </div>
        @empty
        <div data-fam-idx="0">
          <input type="text" name="struktur_keluarga[0][relasi]" placeholder="Hubungan..." required>
          <input type="text" name="struktur_keluarga[0][nama]" placeholder="Nama..." required>
          <input type="text" name="struktur_keluarga[0][umur]" inputmode="numeric" placeholder="Usia..." required>
          <input type="text" name="struktur_keluarga[0][pekerjaan]" placeholder="Pekerjaan..." required>
          <input type="text" name="struktur_keluarga[0][gaji]" placeholder="Gaji..." required>
        </div>
        @endforelse
      </div>
      <button type="button" id="fam_add">Tambah Informasi</button>
    </div>
    {{-- Part 3 --}}
    <div>
      <h2>Informasi Pribadi</h2>
      <input type="text" name="tujuan_ke_jepang" placeholder="Tujuan ke Jepang..." @error('tujuan_ke_jepang') aria-invalid="true" @enderror value="{{ old('tujuan_ke_jepang') }}" required>
      <input type="text" name="tujuan_stlh_kembali" placeholder="Setelah Pulang dari Jepang..." @error('tujuan_stlh_kembali') aria-invalid="true" @enderror value="{{ old('tujuan_stlh_kembali') }}" required>
      <input type="text" name="kelebihan" placeholder="Kelebihan..." @error('stengths') aria-invalid="true" @enderror value="{{ old('kelebihan') }}" required>
      <input type="text" name="kekurangan" placeholder="Kekurangan..." @error('kekurangan') aria-invalid="true" @enderror value="{{ old('kekurangan') }}" required>
      <input type="text" name="hobi" placeholder="Hobi..." @error('hobi') aria-invalid="true" @enderror value="{{ old('hobi') }}" required>
    </div>
    <div>
      <h2>Sertifikat yang Dimiliki</h2>
      <select name="punya_sertif_jlpt" @error('punya_sertif_jlpt') aria-invalid="true" @enderror>
        <option value="1" @selected(old('punya_sertif_jlpt') == true)>Punya JLPT/Setara</option>
        <option value="0" @selected(!old('punya_sertif_jlpt'))>Belum Punya JLPT/Setara</option>
      </select>
      <select name="punya_sim_a" @error('punya_sim_a') aria-invalid="true" @enderror>
        <option value="1" @selected(old('punya_sim_a') == true)>Punya SIM A</option>
        <option value="0" @selected(!old('punya_sim_a'))>Belum Punya SIM A</option>
      </select>
      <input type="text" name="sertif_lain" @error('sertif_lain') aria-invalid="true" @enderror placeholder="Sertifikat Lainnya..." value="{{ old('sertif_lain') }}">
    </div>
    <div>
      <h2>Kerabat/Kenalan di Jepang</h2>
      <input type="text" name="relasi_di_jepang[nama]" placeholder="Nama..." @error('jp_relasi.nama') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.nama') }}">
      <input type="text" name="relasi_di_jepang[relasi]" placeholder="Hubungan..." @error('jp_relasi.relasi') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.relasi') }}">
      <input type="text" name="relasi_di_jepang[pekerjaan]" placeholder="Pekerjaan..." @error('jp_relasi.pekerjaan') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.pekerjaan') }}">
      <input type="text" name="relasi_di_jepang[umur]" placeholder="Usia..." @error('jp_relasi.umur') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.umur') }}">
      <input type="text" name="relasi_di_jepang[alamat]" placeholder="Alamat di Jepang..." @error('jp_relasi.alamat') aria-invalid="true" @enderror value="{{ old('relasi_di_jepang.alamat') }}">
    </div>
    <textarea name="catatan_xtra" rows="10" @error('catatan_xtra') aria-invalid="true" @enderror placeholder="Catatan Tambahan...">{{ old('catatan_xtra') }}</textarea>
    <button type="submit">Register</button>
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
        innerHTML: `<input type="text" name="pendidikan[${eduIdx}][tahun]" inputmode="numeric" placeholder="Tahun..." required>
          <input type="text" name="pendidikan[${eduIdx}][nama_sekolah]" placeholder="Nama Sekolah..." required>
          <input type="text" name="pendidikan[${eduIdx}][jurusan]" placeholder="Jurusan..." required>`
      });
      edu.dataset.eduIdx = eduIdx;
      edus.appendChild(edu);
    }
    function exp_add() {
      exps.appendChild(createElement('div', {
        innerHTML: `<input type="text" name="pengalaman[]" placeholder="Pengalaman...">`
      }));
    }
    function fam_add() {
      let famIdx = parseInt(fams.lastElementChild.dataset.famIdx) + 1;
      let fam = createElement('div', {
        innerHTML: `<input type="text" name="struktur_keluarga[${famIdx}][relasi]" placeholder="Hubungan..." required>
          <input type="text" name="struktur_keluarga[${famIdx}][nama]" placeholder="Nama..." required>
          <input type="text" name="struktur_keluarga[${famIdx}][umur]" inputmode="numeric" placeholder="Usia..." required>
          <input type="text" name="struktur_keluarga[${famIdx}][pekerjaan]" placeholder="Pekerjaan..." required>
          <input type="text" name="struktur_keluarga[${famIdx}][gaji]" placeholder="Gaji..." required>`
      });
      fam.dataset.famIdx = famIdx;
      fams.appendChild(fam);
    }
  </script>
</x-base>