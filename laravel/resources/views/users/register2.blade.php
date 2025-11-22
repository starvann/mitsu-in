<x-base title="Register 2">
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
    <input type="text" name="kode_ref" placeholder="Kode Referensi..." @error('kode_ref') aria-invalid="true" @enderror value="{{ old('kode_ref') }}">
    <input type="text" name="nama" placeholder="Nama..." @error('nama') aria-invalid="true" @enderror value="{{ old('nama') }}" required>
    <input type="file" name="gmb_profil" @error('gmb_profil') aria-invalid="true" @enderror accept="image/png,image/jpeg,image/webp" required>
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
      @forelse((old('pendidikan') ?? []) as $edu)
      <div>
        <input type="text" name="pendidikan[{{ $loop->iteration }}][tahun]" inputmode="numeric" placeholder="Tahun..." value="{{ $edu['tahun'] }}" required>
        <input type="text" name="pendidikan[{{ $loop->iteration }}][nama_sekolah]" placeholder="Nama Sekolah..." value="{{ $edu['nama_sekolah'] }}" required>
        <input type="text" name="pendidikan[{{ $loop->iteration }}][jurusan]" placeholder="Jurusan..." value="{{ $edu['jurusan'] }}" required>
      </div>
      @empty
      <div>
        <input type="text" name="pendidikan[0][tahun]" inputmode="numeric" placeholder="Tahun..." required>
        <input type="text" name="pendidikan[0][nama_sekolah]" placeholder="Nama Sekolah..." required>
        <input type="text" name="pendidikan[0][jurusan]" placeholder="Jurusan..." required>
      </div>
      @endforelse
      <button type="button" id="edu_add">Tambah Informasi</button>
    </div>
    <div>
      <h2>Pengalaman</h2>
      @forelse((old('pengalaman') ?? []) as $exp)
      <input type="text" name="pengalaman[]" placeholder="Pengalaman..." value="{{ $exp }}">
      @empty
      <input type="text" name="pengalaman[]" placeholder="Pengalaman...">
      @endforelse
      <button type="button" id="exp_add">Tambah Informasi</button>
    </div>
    <div>
      <h2>Susunan Keluarga</h2>
      @forelse((old('struktur_keluarga') ?? []) as $fam)
      <div>
        <input type="text" name="struktur_keluarga[{{ $loop->iteration }}][relasi]" placeholder="Hubungan..." value="{{ $fam['relasi'] }}" required>
        <input type="text" name="struktur_keluarga[{{ $loop->iteration }}][nama]" placeholder="Nama..." value="{{ $fam['nama'] }}" required>
        <input type="text" name="struktur_keluarga[{{ $loop->iteration }}][umur]" inputmode="numeric" placeholder="Usia..." value="{{ $fam['umur'] }}" required>
        <input type="text" name="struktur_keluarga[{{ $loop->iteration }}][pekerjaan]" placeholder="Pekerjaan..." value="{{ $fam['pekerjaan'] }}" required>
        <input type="text" name="struktur_keluarga[{{ $loop->iteration }}][gaji]" placeholder="Gaji..." value="{{ $fam['gaji'] }}" required>
      </div>
      @empty
      <div>
        <input type="text" name="struktur_keluarga[0][relasi]" placeholder="Hubungan..." required>
        <input type="text" name="struktur_keluarga[0][nama]" placeholder="Nama..." required>
        <input type="text" name="struktur_keluarga[0][umur]" inputmode="numeric" placeholder="Usia..." required>
        <input type="text" name="struktur_keluarga[0][pekerjaan]" placeholder="Pekerjaan..." required>
        <input type="text" name="struktur_keluarga[0][gaji]" placeholder="Gaji..." required>
      </div>
      @endforelse
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
</x-base>