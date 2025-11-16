<x-base title="Register">
  <form action="{{ url('register') }}" method="post">
    <h1>Register</h1>
    <input type="text" name="ref_code" placeholder="Kode Referensi..." value="{{ old('ref_code') }}" required>
    <input type="text" name="name" placeholder="Nama..." value="{{ old('name') }}" required>
    <input type="text" name="hp_number" placeholder="No. HP..." value="{{ old('hp_number') }}" required>
    <select name="gender">
      <option value="laki-laki" @if(old('gender') != 'perempuan') selected @endif>Laki-Laki</option>
      <option value="perempuan" @if(old('gender') == 'perempuan') selected @endif>Perempuan</option>
    </select>
    <input type="text" name="age" placeholder="Usia..." inputmode="numeric" value="{{ old('age') }}" required>
    <input type="text" name="body_h" placeholder="Tinggi Badan..." inputmode="numeric" value="{{ old('body_h') }}" required>
    <input type="text" name="body_w" placeholder="Berat Badan..." inputmode="numeric" value="{{ old('body_w') }}" required>
    <select name="have_married">
      <option value="true" @if(old('have_married')) selected @endif>Pernah Menikah</option>
      <option value="false" @if(!old('have_married')) selected @endif>Belum Pernah Menikah</option>
    </select>
    <input type="text" name="blood_type" placeholder="Golongan Darah..." value="{{ old('blood_type') }}" required>
    <input type="text" name="religion" placeholder="Agama..." value="{{ old('religion') }}" required>
    <select name="have_come_to_jp">
      <option value="true" @if(old('have_come_to_jp')) selected @endif>Pernah ke Jepang</option>
      <option value="false" @if(!old('have_come_to_jp')) selected @endif>Belum Pernah ke Jepang</option>
    </select>
    <select name="have_pasport">
      <option value="true" @if(old('have_pasport')) selected @endif>Punya Paspor</option>
      <option value="false" @if(!old('have_pasport')) selected @endif>Belum Punya Paspor</option>
    </select>
    <select name="main_hand">
      <option value="kanan" @if(old('main_hand') != 'kiri') selected @endif>Punya Paspor</option>
      <option value="kiri" @if(old('gender') == 'kiri') selected @endif>Belum Punya Paspor</option>
    </select>
    <textarea name="address" cols="30" rows="10">{{ old('address') }}</textarea>
    <fieldset>
      
    </fieldset>
    <button type="submit">Register</button>
  </form>
</x-base>