<x-base title="Profil Siswa" style="display: flex; flex-direction: column; gap: 8px;">
  <x-slot:head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
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
  </div>
  <a href="{{ url('/dashboard/students') }}" role="button" style="align-self: center;">Kembali</a>
  @if(!empty($errors->all()))
  <div class="err-messages">
    @foreach($errors->all() as $err_msg)
    <p>{{ $err_msg }}</p>
    @endforeach
  </div>
  @endif
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
        <span>Role</span>
        <select name="role">
          <option value="stdn" @selected($user->stat == '')>Proses Daftar Ulang</option>
          <option value="refl" @selected($user->stat != 'pending')>Terdaftar</option>
          <option value="admn" @selected($user->stat != 'pending')>Terdaftar</option>
        </select>
      </div>
      <label>
        Nama Lengkap
        <input type="text" name="nama" @error('nama') aria-invalid="true" @enderror value="{{ old('nama') ?? $user->nama }}" required>
      </label>
      <label>
        Foto Profil
        <input type="file" name="gmb_profil" id="gmb_profil" @error('gmb_profil') aria-invalid="true" @enderror accept="image/png,image/jpeg,image/webp">
        <img src="{{ url($user->gmb_profil) }}" id="prevImg" style="border-radius: 50%; width: 80px; height: 80px; display: none; object-fit: cover;">
      </label>
      <label>
        No. Handphone
        <input type="text" name="no_hp" @error('no_hp') aria-invalid="true" @enderror value="{{ old('no_hp') ?? $user->no_hp }}" required>
      </label>
      <label>
      Jenis Kelamin
        <select name="gender" id="gender" @error('gender') aria-invalid="true" @enderror>
          <option value="laki-laki" @selected((old('gender') ?? $user->gender) != 'perempuan')>Laki-Laki</option>
          <option value="perempuan" @selected((old('gender') ?? $user->gender) == 'perempuan')>Perempuan</option>
        </select>
      </label>
      <label>
        Usia
        <input type="number" name="umur" id="umur" inputmode="numeric" min="15" @error('umur') aria-invalid="true" @enderror value="{{ old('umur') ?? $user->umur }}" required>
      </label>
      <label>
        Tinggi Badan (cm)
        <input type="text" name="tinggi_badan" id="tinggi_badan" inputmode="numeric" @error('tinggi_badan') aria-invalid="true" @enderror value="{{ old('tinggi_badan') ?? $user->tinggi_badan }}" required>
      </label>
      <label>
        Berat Badan (kg)
        <input type="text" name="berat_badan" id="berat_badan" inputmode="numeric" @error('berat_badan') aria-invalid="true" @enderror value="{{ old('berat_badan') ?? $user->berat_badan }}" required>
      </label>

      <div class="section-divider"></div>
      <h2 class="section-title">Kondisi & Latar Belakang</h2>

      <label>
        Status Pernikahan
        <input type="text" name="status_pernikahan" id="status_pernikahan" placeholder="Belum menikah, Sudah menikah, Cerai, dll..." @error('status_pernikahan') aria-invalid="true" @enderror value="{{ old('status_pernikahan') ?? $user->status_pernikahan }}" required>
      </label>
      <label>
        Golongan Darah
        <input type="text" name="gol_darah" id="gol_darah" @error('gol_darah') aria-invalid="true" @enderror value="{{ old('gol_darah') ?? $user->gol_darah }}" required>
      </label>
      <label>
        Agama
        <input type="text" name="agama" id="agama" @error('agama') aria-invalid="true" @enderror value="{{ old('agama') ?? $user->agama }}" required>
      </label>
      <label>
        Pernah ke Jepang
        <select name="pernah_ke_jepang" id="pernah_ke_jepang" @error('pernah_ke_jepang') aria-invalid="true" @enderror>
          <option value="1" @selected(old('pernah_ke_jepang') ?? $user->pernah_ke_jepang)>Pernah ke Jepang</option>
          <option value="0" @selected(!(old('pernah_ke_jepang') ?? $user->pernah_ke_jepang))>Belum Pernah ke Jepang</option>
        </select>
      </label>
      <label>
        Punya Paspor
        <select name="punya_paspor" id="punya_paspor" @error('punya_paspor') aria-invalid="true" @enderror>
          <option value="1" @selected(old('punya_paspor') ?? $user->punya_paspor)>Punya Paspor</option>
          <option value="0" @selected(!(old('punya_paspor') ?? $user->punya_paspor))>Belum Punya Paspor</option>
        </select>
      </label>
      <label>
        Tangan Ahli
        <select name="tangan_utama" @error('tangan_utama') aria-invalid="true" @enderror>
          <option value="kanan" @selected((old('tangan_utama') ?? $user->tangan_utama) == 'kanan')>Kanan</option>
          <option value="kiri" @selected((old('tangan_utama') ?? $user->tangan_utama) == 'kiri')>Kiri</option>
          <option value="kiri" @selected((old('tangan_utama') ?? $user->tangan_utama) == 'keduanya')>Keduanya</option>
        </select>
      </label>

      <div class="section-divider"></div>
      <h2 class="section-title">Alamat & Pendidikan</h2>

      <label>
        Alamat Lengkap
        <textarea name="alamat" id="alamat" rows="10" @error('alamat') aria-invalid="true" @enderror>{{ old('alamat') ?? $user->alamat }}</textarea>
      </label>
      <div class="subsection">
        <h2 class="subsection-title">Pendidikan</h2>
        <div id="edus">
          @foreach((old('pendidikan') ?? $user->pendidikan) as $i => $edu)
          <div data-edu-idx="{{ $i }}" class="repeat-group edu-group">
            <label>
              Tahun Lulus
              <input type="number" name="pendidikan[{{ $i }}][tahun]" inputmode="numeric" value="{{ $edu['tahun'] }}" required>
            </label>
            <label>
              Nama Sekolah
              <input type="text" name="pendidikan[{{ $i }}][nama_sekolah]" value="{{ $edu['nama_sekolah'] }}" required>
            </label>
            <label>
              Jurusan
              <input type="text" name="pendidikan[{{ $i }}][jurusan]" value="{{ $edu['jurusan'] }}" required>
            </label>
            <button type="button" class="remove-btn" onclick="delEdu(this.parentElement)">Hapus</button>
          </div>
          @endforeach
        </div>
        <button type="button" onclick="edu_add()" class="btn-primary btn-small btn-add">Tambah Informasi</button>
      </div>
      <div class="subsection">
        <h2 class="subsection-title">Pengalaman</h2>
        <div id="exps">
          @foreach((old('pengalaman') ?? $user->pengalaman) as $exp)
          <div class="repeat-group exp-group">
            <label>
              Pengalaman Kerja / Magang
              <input type="text" name="pengalaman[]" value="{{ $exp }}">
            </label>
            <button type="button" class="remove-btn" onclick="delExp(this.parentElement)">Hapus</button>
          </div>
        </div>
        <button type="button" onclick="exp_add()" class="btn-primary btn-small btn-add">Tambah Informasi</button>
      </div>

      <div class="section-divider"></div>
      <h2 class="section-title">Keluarga & Tujuan</h2>

      <div class="subsection">
        <h2 class="subsection-title">Susunan Keluarga (utama)</h2>
        <div id="fams">
          @forelse((old('struktur_keluarga') ?? $user->struktur_keluarga) as $i => $fam)
          <div data-fam-idx="{{ $i }}" class="repeat-group family-group">
            <label>
              Hubungan Keluarga
              <input type="text" name="struktur_keluarga[{{ $i }}][relasi]" value="{{ $fam['relasi'] }}" required>
            </label>
            <label>
              Nama Lengkap
              <input type="text" name="struktur_keluarga[{{ $i }}][nama]" value="{{ $fam['nama'] }}" required>
            </label>
            <label>
              Usia
              <input type="number" name="struktur_keluarga[{{ $i }}][umur]" inputmode="numeric" value="{{ $fam['umur'] }}" required>
            </label>
            <label>
              Pekerjaan
              <input type="text" name="struktur_keluarga[{{ $i }}][pekerjaan]" value="{{ $fam['pekerjaan'] }}" required>
            </label>
            <label>
              Gaji (perkiraan per bulan)
              <input type="text" name="struktur_keluarga[{{ $i }}][gaji]" placeholder="Rp10.000.000..." value="{{ $fam['gaji'] }}" required>
            </label>
            <button type="button" class="remove-btn" onclick="delFam(this.parentElement)">Hapus</button>
          </div>
        </div>
        <button type="button" onclick="fam_add()" class="btn-primary btn-small btn-add">Tambah Informasi</button>
      </div>

      <div class="section-divider"></div>
      <h2 class="section-title">Rencana & Sertifikat</h2>

      <label>
        Tujuan ke Jepang
        <input type="text" name="tujuan_ke_jepang" @error('tujuan_ke_jepang') aria-invalid="true" @enderror value="{{ old('tujuan_ke_jepang') ?? $user->tujuan_ke_jepang }}" required>
      </label>
      <label>
        Setelah pulang dari Jepang, rencana apa?
        <input type="text" name="tujuan_stlh_kembali" @error('tujuan_stlh_kembali') aria-invalid="true" @enderror value="{{ old('tujuan_stlh_kembali') ?? $user->tujuan_stlh_kembali }}" required>
      </label>
      <label>
        Kelebihan
        <input type="text" name="kelebihan" @error('stengths') aria-invalid="true" @enderror value="{{ old('kelebihan') ?? $user->kelebihan }}" required>
      </label>
      <label>
        Kekurangan
        <input type="text" name="kekurangan" @error('kekurangan') aria-invalid="true" @enderror value="{{ old('kekurangan') ?? $user->kekurangan }}" required>
      </label>
      <label>
        Hobi
        <input type="text" name="hobi" @error('hobi') aria-invalid="true" @enderror value="{{ old('hobi') ?? $user->hobi }}" required>
      </label>
      <div class="subsection">
        <h2 class="subsection-title">Sertifikat yang Dimiliki</h2>
        <label>
          Sertifikat JLPT/Setara
          <input type="text" name="sertif_jlpt" @error('sertif_jlpt') aria-invalid="true" @enderror value="{{ old('sertif_jlpt') ?? $user->sertif_jlpt }}">
        </label>
        <label>
          Punya SIM A
          <select name="punya_sim_a" @error('punya_sim_a') aria-invalid="true" @enderror>
            <option value="1" @selected((old('punya_sim_a') ?? $user->punya_sim_a))>Punya SIM A</option>
            <option value="0" @selected(!(old('punya_sim_a') ?? $user->punya_sim_a))>Belum Punya SIM A</option>
          </select>
        </label>
        <label>
          Sertifikat Lainnya
          <input type="text" name="sertif_lain" @error('sertif_lain') aria-invalid="true" @enderror value="{{ old('sertif_lain') ?? $user->sertif_lain }}">
        </label>
      </div>

      <div class="section-divider"></div>
      <h2 class="section-title">Kerabat / Kenalan di Jepang</h2>

      @forelse((old('relasi_di_jepang') ?? $user->relasi_di_jepang ?? []) as $key => $val)
      <label>
        {{ ucfirst($key) }} Kerabat/Kenalan di Jepang
        <input type="{{ $key != 'usia' ? 'text' : 'number' }}" name="relasi_di_jepang[{{ $key }}]" @error("relasi_di_jepang.$key") aria-invalid="true" @enderror value="{{ $val }}">
      </label>
      @empty
      <label>
        Nama Kerabat/Kenalan di Jepang
        <input type="text" name="relasi_di_jepang[nama]">
      </label>
      <label>
        Hubungan Kerabat/Kenalan di Jepang
        <input type="text" name="relasi_di_jepang[hubungan]">
      </label>
      <label>
        Pekerjaan Kerabat/Kenalan di Jepang
        <input type="text" name="relasi_di_jepang[pekerjaan]">
      </label>
      <label>
        Usia Kerabat/Kenalan di Jepang
        <input type="number" name="relasi_di_jepang[usia]">
      </label>
      <label>
        Alamat Kerabat/Kenalan di Jepang
        <input type="text" name="relasi_di_jepang[alamat]">
      </label>
      @endforelse
      <label>
        Catatan Tambahan
        <textarea name="catatan_xtra" rows="10" @error('catatan_xtra') aria-invalid="true" @enderror>{{ old('catatan_xtra') ?? $user->catatan_xtra }}</textarea>
      </label>
      <button type="submit">Simpan</button>
    </form>
  </div>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    function queryAll(s) {
      return document.querySelectorAll(s);
    }
    function createElement(tag, prop = {}) {
      return Object.assign(document.createElement(tag), prop);
    }
    let edus = query('#edus');
    let exps = query('#exps');
    let fams = query('#fams');
    let prevImg = query("#prevImg");
    let inputImg = query("#gmb_profil");
    inputImg.onchange = () => {
      const reader = new FileReader();
      reader.readAsDataURL(inputImg.files[0]);
      reader.onload = () => {
        prevImg.src = reader.result;
        prevImg.style.display = "block";
      };
    };
    function edu_add() {
      let eduIdx = parseInt(edus.lastElementChild.dataset.eduIdx) + 1;
      let edu = createElement('div', {
        innerHTML: `<label>
            Tahun Lulus
            <input type="number" name="pendidikan[${eduIdx}][tahun]" inputmode="numeric" required>
          </label>
          <label>
            Nama Sekolah
            <input type="text" name="pendidikan[${eduIdx}][nama_sekolah]" required>
          </label>
          <label>
            Jurusan
            <input type="text" name="pendidikan[${eduIdx}][jurusan]" required>
          </label>
          <button type="button" class="remove-btn" onclick="delEdu(this.parentElement)">Hapus</button>`,
        className: 'repeat-group edu-group'
      });
      edu.dataset.eduIdx = eduIdx;
      edus.appendChild(edu);
    }
    function delEdu(edu) {
      if (edus.children.length < 2) {return;}
      edus.removeChild(edu);
      const eduGroups = queryAll('.edu-group');
      eduGroups.forEach((box, idx) => {
        box.dataset.soalIdx = idx;
        const inputs = box.querySelectorAll('input[name^="pendidikan["], textarea[name^="pendidikan["]');
        inputs.forEach(input => {
          const name = input.getAttribute('name');
          const newName = name.replace(/pendidikan\[\d+\]/, `pendidikan[${idx}]`);
          input.name = newName;
        });
      });
    }
    function exp_add() {
      exps.appendChild(createElement('div', {
        innerHTML: `<label>
            Pengalaman Kerja / Magang
            <input type="text" name="pengalaman[]">
          </label>
          <button type="button" class="remove-btn" onclick="delExp(this.parentElement)">Hapus</button>`,
        className: 'repeat-group exp-group'
      }));
    }
    function delExp(exp) {
      if (exps.children.length < 2) {return;}
      exps.removeChild(exp);
    }
    function fam_add() {
      let famIdx = parseInt(fams.lastElementChild.dataset.famIdx) + 1;
      let fam = createElement('div', {
        innerHTML: `<label>
            Hubungan Keluarga
            <input type="text" name="struktur_keluarga[${famIdx}][relasi]" required>
          </label>
          <label>
            Nama Lengkap
            <input type="text" name="struktur_keluarga[${famIdx}][nama]" required>
          </label>
          <label>
            Usia
            <input type="number" name="struktur_keluarga[${famIdx}][umur]" inputmode="numeric" required>
          </label>
          <label>
            Pekerjaan
            <input type="text" name="struktur_keluarga[${famIdx}][pekerjaan]" required>
          </label>
          <label>
            Gaji (perkiraan per bulan)
            <input type="text" name="struktur_keluarga[${famIdx}][gaji]" placeholder="Rp10.000.000..." required>
          </label>
          <button type="button" class="remove-btn" onclick="delFam(this.parentElement)">Hapus</button>`,
        className: 'repeat-group family-group'
      });
      fam.dataset.famIdx = famIdx;
      fams.appendChild(fam);
    }
    function delFam(fam) {
      if (fams.children.length < 2) {return;}
      fams.removeChild(fam);
      const famGroups = queryAll('.fam-group');
      famGroups.forEach((box, idx) => {
        box.dataset.soalIdx = idx;
        const inputs = box.querySelectorAll('input[name^="struktur_keluarga["], textarea[name^="struktur_keluarga["]');
        inputs.forEach(input => {
          const name = input.getAttribute('name');
          const newName = name.replace(/struktur_keluarga\[\d+\]/, `struktur_keluarga[${idx}]`);
          input.name = newName;
        });
      });
    }
  </script>
</x-base>