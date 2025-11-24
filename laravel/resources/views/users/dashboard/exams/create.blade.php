<x-base>
  <form action="{{ url('create-exam') }}">
    @csrf
    <input type="text" name="judul" placeholder="Judul..." value="{{ old('judul') }}" @error('judul') aria-invalid="true" @enderror required>
    <textarea name="deskripsi" rows="3" placeholder="Deskripsi" @error('deskripsi') aria-invalid="true" @enderror required>{{ old('deskripsi') }}</textarea>
    <ol>
      @if(old('soal'))
        @foreach(old('soal') as $i => $soal)
        <li id="soal{{ $i }}">
          <input type="text" name="soal[{{ $i }}]" placeholder="Soal..." value="{{ $soal['soal'] }}" @error("soal[$i][soal]") aria-invalid="true" @enderror required>
          @foreach($soal['jawaban'] as $j => $jwb)
          <div>
            <input type="radio" name="soal[{{ $i }}][benar]" value="{{ $j }}" @selected(old("soal[$i][benar]") == $j)>
            <input type="text" name="soal[{{ $i }}][jawaban][{{ $j }}]" placeholder="Jawaban..." value="{{ old("soal[$i][jawaban][$j]") }}" required>
          </div>
          @endforeach
        </li>
        @endforeach
      @else
      <li id="soal0">
        <input type="text" name="soal[0][soal]" placeholder="Soal..." required>
        <div>
          <input type="radio" name="soal[0][benar]" value="0">
          <input type="text" name="soal[0][jawaban][0]" placeholder="Jawaban..." required>
        </div>
      </li>
      <button type="button" onclick="tambahJawaban()">Tambah jawaban</button>
      @endif
    </ol>
    <button type="button">Tambah Pertanyaan</button>
    <button type="submit">Buat</button>
  </form>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    function createElement(tag, prop = {}) {
      return Object.assign(document.createElement(tag), prop);
    }
    let ol = query('ol');
    let lastSoalIdx = 0;
    let lastJawabanIdx = 0;
    function tambahJawaban() {
      ol.appendChild(createElement('div', {
        innerHTML: `<input type="radio" name="soal[${lastSoalIdx}][benar]" value="${lastJawabanIdx}">
          <input type="text" name="soal[${lastSoalIdx}][jawaban][${lastJawabanIdx}]" placeholder="Jawaban..." required>`
      }));
      lastJawabanIdx += 1;
    }
    function tambahSoal() {
      ol.appendChild(createElement('div', {
        id: `soal${lastSoalIdx}`,
      }));
    }
  </script>
</x-base>