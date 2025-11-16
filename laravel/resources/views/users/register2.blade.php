<x-base title="Register">
  <form action="{{ url('register2') }}" method="post">
    <h1>Register</h1>
    {{-- @isset($errors) {{ dump($errors->all()) }} @endisset --}}
    @csrf
    {{-- Login Credential --}}
    <input type="hidden" name="email" value="{{ old('email') }}">
    <input type="hidden" name="password" value="{{ old('password') }}">
    <input type="hidden" name="code" value="{{ old('code') }}">
    {{-- Others --}}
    <input type="text" name="ref_code" placeholder="Kode Referensi..." value="{{ old('ref_code') }}">
    <input type="text" name="name" placeholder="Nama..." value="{{ old('name') }}" required>
    <input type="text" name="hp_number" placeholder="No. HP..." value="{{ old('hp_number') }}" required>
    <select name="gender">
      <option value="laki-laki" @selected(old('gender') != 'perempuan')>Laki-Laki</option>
      <option value="perempuan" @selected(old('gender') == 'perempuan')>Perempuan</option>
    </select>
    <input type="text" name="age" placeholder="Usia..." inputmode="numeric" value="{{ old('age') }}" required>
    <input type="text" name="body_h" placeholder="Tinggi Badan..." inputmode="numeric" value="{{ old('body_h') }}" required>
    <input type="text" name="body_w" placeholder="Berat Badan..." inputmode="numeric" value="{{ old('body_w') }}" required>
    <select name="have_married">
      <option value="true" @selected(old('have_married') == 'true')>Pernah Menikah</option>
      <option value="false" @selected(!old('have_married'))>Belum Pernah Menikah</option>
    </select>
    <input type="text" name="blood_type" placeholder="Golongan Darah..." value="{{ old('blood_type') }}" required>
    <input type="text" name="religion" placeholder="Agama..." value="{{ old('religion') }}" required>
    <select name="have_come_to_jp">
      <option value="true" @selected(old('have_come_to_jp') == 'true')>Pernah ke Jepang</option>
      <option value="false" @selected(!old('have_come_to_jp'))>Belum Pernah ke Jepang</option>
    </select>
    <select name="have_passport">
      <option value="true" @selected(old('have_passport') == 'true')>Punya Paspor</option>
      <option value="false" @selected(!old('have_passport'))>Belum Punya Paspor</option>
    </select>
    <select name="main_hand">
      <option value="kanan" @selected(old('main_hand') != 'kiri')>Kanan</option>
      <option value="kiri" @selected(old('main_hand') == 'kiri')>Kiri</option>
    </select>
    <textarea name="address" rows="10" placeholder="Alamat...">{{ old('address') }}</textarea>
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
    <div>
      <h2>Informasi Pribadi</h2>
      <input type="text" name="purpose_to_jp" placeholder="Tujuan ke Jepang..." value="{{ old('purpose_to_jp') }}" required>
      <input type="text" name="purpose_after_comeback" placeholder="Setelah Pulang dari Jepang..." value="{{ old('purpose_after_comeback') }}" required>
      <input type="text" name="strengths" placeholder="Kelebihan..." value="{{ old('strengths') }}" required>
      <input type="text" name="weaknesses" placeholder="Kekurangan..." value="{{ old('weaknesses') }}" required>
      <input type="text" name="hobies" placeholder="Hobi..." value="{{ old('hobies') }}" required>
    </div>
    <div>
      <h2>Sertifikat yang Dimiliki</h2>
      <select name="has_jlpt_cert">
        <option value="true" @if(old('has_jlpt_cert') == 'true') selected @endif>Punya JLPT/Setara</option>
        <option value="false" @if(!old('has_jlpt_cert')) selected @endif>Belum Punya JLPT/Setara</option>
      </select>
      <select name="has_sim_a">
        <option value="true" @if(old('has_sim_a') == 'true') selected @endif>Punya SIM A</option>
        <option value="false" @if(!old('has_sim_a')) selected @endif>Belum Punya SIM A</option>
      </select>
      <input type="text" name="other_cert" placeholder="Sertifikat Lainnya..." value="{{ old('other_cert') }}">
    </div>
    <div>
      <h2>Kerabat/Kenalan di Jepang</h2>
      <input type="text" name="jp_relations[name]" placeholder="Nama..." value="{{ old('jp_relations.name') }}">
      <input type="text" name="jp_relations[relation]" placeholder="Hubungan..." value="{{ old('jp_relations.relation') }}">
      <input type="text" name="jp_relations[job]" placeholder="Pekerjaan..." value="{{ old('jp_relations.job') }}">
      <input type="text" name="jp_relations[age]" placeholder="Usia..." value="{{ old('jp_relations.age') }}">
      <input type="text" name="jp_relations[address]" placeholder="Alamat di Jepang..." value="{{ old('jp_relations.address') }}">
    </div>
    <textarea name="extra_notes" rows="10" placeholder="Catatan Tambahan">{{ old('extra_notes') }}</textarea>
    <button type="submit">Register</button>
  </form>
</x-base>