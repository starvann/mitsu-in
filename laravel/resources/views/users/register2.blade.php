<x-base title="Register 2">
  <form action="{{ url('register2') }}" method="post">
    <h1>Register</h1>
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
    <input type="text" name="ref_code" placeholder="Kode Referensi..." @error('ref_code') aria-invalid="true" @enderror value="{{ old('ref_code') }}">
    <input type="text" name="name" placeholder="Nama..." @error('name') aria-invalid="true" @enderror value="{{ old('name') }}" required>
    <input type="text" name="hp_number" placeholder="No. HP..." @error('hp_number') aria-invalid="true" @enderror value="{{ old('hp_number') }}" required>
    <select name="gender" @error('gender') aria-invalid="true" @enderror>
      <option value="laki-laki" @selected(old('gender') != 'perempuan')>Laki-Laki</option>
      <option value="perempuan" @selected(old('gender') == 'perempuan')>Perempuan</option>
    </select>
    <input type="text" name="age" placeholder="Usia..." inputmode="numeric" @error('age') aria-invalid="true" @enderror value="{{ old('age') }}" required>
    <input type="text" name="body_h" placeholder="Tinggi Badan..." inputmode="numeric" @error('body_h') aria-invalid="true" @enderror value="{{ old('body_h') }}" required>
    <input type="text" name="body_w" placeholder="Berat Badan..." inputmode="numeric" @error('body_w') aria-invalid="true" @enderror value="{{ old('body_w') }}" required>
    <select name="have_married" @error('have_married') aria-invalid="true" @enderror>
      <option value="1" @selected(old('have_married') == true)>Pernah Menikah</option>
      <option value="0" @selected(!old('have_married'))>Belum Pernah Menikah</option>
    </select>
    <input type="text" name="blood_type" placeholder="Golongan Darah..." @error('blood_type') aria-invalid="true" @enderror value="{{ old('blood_type') }}" required>
    <input type="text" name="religion" placeholder="Agama..." @error('religion') aria-invalid="true" @enderror value="{{ old('religion') }}" required>
    <select name="have_come_to_jp" @error('have_come_to_jp') aria-invalid="true" @enderror>
      <option value="1" @selected(old('have_come_to_jp') == true)>Pernah ke Jepang</option>
      <option value="0" @selected(!old('have_come_to_jp'))>Belum Pernah ke Jepang</option>
    </select>
    <select name="have_passport" @error('have_passport') aria-invalid="true" @enderror>
      <option value="1" @selected(old('have_passport') == true)>Punya Paspor</option>
      <option value="0" @selected(!old('have_passport'))>Belum Punya Paspor</option>
    </select>
    <select name="main_hand" @error('main_hand') aria-invalid="true" @enderror>
      <option value="kanan" @selected(old('main_hand') != 'kiri')>Kanan</option>
      <option value="kiri" @selected(old('main_hand') == 'kiri')>Kiri</option>
    </select>
    {{-- Part 2 --}}
    <textarea name="address" rows="10" placeholder="Alamat..." @error('address') aria-invalid="true" @enderror>{{ old('address') }}</textarea>
    <div>
      <h2>Pendidikan</h2>
      @if(old('education'))
        @foreach(old('education') as $edu)
      <div>
        <input type="text" name="education[{{ $loop->iteration }}][year]" inputmode="numeric" placeholder="Tahun..." value="{{ $edu['year'] }}" required>
        <input type="text" name="education[{{ $loop->iteration }}][school_name]" placeholder="Nama Sekolah..." value="{{ $edu['school_name'] }}" required>
        <input type="text" name="education[{{ $loop->iteration }}][major]" placeholder="Jurusan..." value="{{ $edu['major'] }}" required>
      </div>
        @endforeach
      @else
      <div>
        <input type="text" name="education[0][year]" inputmode="numeric" placeholder="Tahun..." required>
        <input type="text" name="education[0][school_name]" placeholder="Nama Sekolah..." required>
        <input type="text" name="education[0][major]" placeholder="Jurusan..." required>
      </div>
      @endif
      <button type="button" id="edu_add">Tambah Informasi</button>
    </div>
    <div>
      <h2>Pengalaman</h2>
      @if(old('experience'))
        @foreach(old('experience') as $exp)
      <input type="text" name="experience[]" placeholder="Pengalaman..." value="{{ $exp }}" required>
        @endforeach
      @else
      <input type="text" name="experience[]" placeholder="Pengalaman..." required>
      @endif
      <button type="button" id="exp_add">Tambah Informasi</button>
    </div>
    <div>
      <h2>Susunan Keluarga</h2>
      @if(old('family_structure'))
        @foreach(old('family_structure') as $fam)
      <div>
        <input type="text" name="family_structure[{{ $loop->iteration }}][relation]" placeholder="Hubungan..." value="{{ $fam['relation'] }}" required>
        <input type="text" name="family_structure[{{ $loop->iteration }}][name]" placeholder="Nama..." value="{{ $fam['name'] }}" required>
        <input type="text" name="family_structure[{{ $loop->iteration }}][age]" inputmode="numeric" placeholder="Usia..." value="{{ $fam['age'] }}" required>
        <input type="text" name="family_structure[{{ $loop->iteration }}][job]" placeholder="Pekerjaan..." value="{{ $fam['job'] }}" required>
        <input type="text" name="family_structure[{{ $loop->iteration }}][salary]" placeholder="Gaji..." value="{{ $fam['salary'] }}" required>
      </div>
        @endforeach
      @else
      <div>
        <input type="text" name="family_structure[0][relation]" placeholder="Hubungan..." required>
        <input type="text" name="family_structure[0][name]" placeholder="Nama..." required>
        <input type="text" name="family_structure[0][age]" inputmode="numeric" placeholder="Usia..." required>
        <input type="text" name="family_structure[0][job]" placeholder="Pekerjaan..." required>
        <input type="text" name="family_structure[0][salary]" placeholder="Gaji..." required>
      </div>
      @endif
      <button type="button" id="fam_add">Tambah Informasi</button>
    </div>
    {{-- Part 3 --}}
    <div>
      <h2>Informasi Pribadi</h2>
      <input type="text" name="purpose_to_jp" placeholder="Tujuan ke Jepang..." @error('purpose_to_jp') aria-invalid="true" @enderror value="{{ old('purpose_to_jp') }}" required>
      <input type="text" name="purpose_after_comeback" placeholder="Setelah Pulang dari Jepang..." @error('purpose_after_comeback') aria-invalid="true" @enderror value="{{ old('purpose_after_comeback') }}" required>
      <input type="text" name="strengths" placeholder="Kelebihan..." @error('stengths') aria-invalid="true" @enderror value="{{ old('strengths') }}" required>
      <input type="text" name="weaknesses" placeholder="Kekurangan..." @error('weaknesses') aria-invalid="true" @enderror value="{{ old('weaknesses') }}" required>
      <input type="text" name="hobies" placeholder="Hobi..." @error('hobies') aria-invalid="true" @enderror value="{{ old('hobies') }}" required>
    </div>
    <div>
      <h2>Sertifikat yang Dimiliki</h2>
      <select name="has_jlpt_cert" @error('has_jlpt_cert') aria-invalid="true" @enderror>
        <option value="1" @selected(old('has_jlpt_cert') == true)>Punya JLPT/Setara</option>
        <option value="0" @selected(!old('has_jlpt_cert'))>Belum Punya JLPT/Setara</option>
      </select>
      <select name="has_sim_a" @error('has_sim_a') aria-invalid="true" @enderror>
        <option value="1" @selected(old('has_sim_a') == true)>Punya SIM A</option>
        <option value="0" @selected(!old('has_sim_a'))>Belum Punya SIM A</option>
      </select>
      <input type="text" name="other_cert" @error('other_cert') aria-invalid="true" @enderror placeholder="Sertifikat Lainnya..." value="{{ old('other_cert') }}">
    </div>
    <div>
      <h2>Kerabat/Kenalan di Jepang</h2>
      <input type="text" name="jp_relations[name]" placeholder="Nama..." @error('jp_relation.name') aria-invalid="true" @enderror value="{{ old('jp_relations.name') }}">
      <input type="text" name="jp_relations[relation]" placeholder="Hubungan..." @error('jp_relation.relation') aria-invalid="true" @enderror value="{{ old('jp_relations.relation') }}">
      <input type="text" name="jp_relations[job]" placeholder="Pekerjaan..." @error('jp_relation.job') aria-invalid="true" @enderror value="{{ old('jp_relations.job') }}">
      <input type="text" name="jp_relations[age]" placeholder="Usia..." @error('jp_relation.age') aria-invalid="true" @enderror value="{{ old('jp_relations.age') }}">
      <input type="text" name="jp_relations[address]" placeholder="Alamat di Jepang..." @error('jp_relation.address') aria-invalid="true" @enderror value="{{ old('jp_relations.address') }}">
    </div>
    <textarea name="extra_notes" rows="10" @error('extra_notes') aria-invalid="true" @enderror placeholder="Catatan Tambahan...">{{ old('extra_notes') }}</textarea>
    <button type="submit">Register</button>
  </form>
</x-base>