<x-base main-class="page col">
  <form action="{{ url("dashboard/edit-group/$group->id") }}" method="post">
    <h1>Edit Detail Grup</h1>
    @csrf
    @method('PUT')
    <label>
      Nama
      <input type="text" name="nama" @error('nama') aria-invalid="true" @enderror value="{{ old('nama') ?? $group->nama }}">
    </label>
    <label>
      Deskripsi
      <textarea name="deskripsi" rows="3" @error('nama') aria-invalid="true" @enderror>{{ old('deskripsi') ?? $group->deskripsi }}</textarea>
    </label>
    <button type="submit" style="margin-top: 12px;">Simpan</button>
  </form>
</x-base>