<x-base title="Edit Ujian">
  <form action="{{ url("dashboard/edit-exam/$exam->id") }}" method="post">
    <h1>Edit Ujian</h1>
    @csrf
    @method('put')
    @if(!empty($errors->all()))
    <ul>
      @foreach($errors->all() as $err_msg)
      <li>{{ $err_msg }}</li>
      @endforeach
    </ul>
    @endif
    <input type="text" name="judul" placeholder="Judul..." value="{{ old('judul') ?? $exam->judul }}" @error('judul') aria-invalid="true" @enderror required>
    <textarea name="deskripsi" rows="3" placeholder="Deskripsi" @error('deskripsi') aria-invalid="true" @enderror required>{{ old('deskripsi') ?? $exam->deskripsi }}</textarea>
    <ol>
      @foreach(old('soal') ?? $questions as $i => $question)
      <li data-soal-idx="{{ $i }}">
        <input type="text" name="soal[{{ $i }}][soal]" placeholder="Soal..." value="{{ old("soal.$i.soal") ?? $question['soal'] }}" required>
        <div>
        @foreach($question['jawaban'] as $j => $jwb)
          <div data-jwb-idx="{{ $j }}">
            <input type="radio" name="soal[{{ $i }}][benar]" value="{{ $j }}" @checked($question['benar'] ?? $question['jwbn_yg_benar'] == $j)>
            <input type="text" name="soal[{{ $i }}][jawaban][{{ $j }}]" placeholder="Jawaban..." value="{{ old("soal.$i.jawaban.$j") ?? $jwb }}" required>
          </div>
        @endforeach
        </div>
        <button type="button" onclick="tambahJawaban(this.previousElementSibling)">Tambah jawaban</button>
        <button type="button" onclick="hapusJawaban(this.previousElementSibling.previousElementSibling)">Hapus jawaban</button>
      </li>
      @endforeach
      {{-- @endif --}}
    </ol>
    <button type="button" onclick="tambahSoal()">Tambah Pertanyaan</button>
    <button type="button" onclick="hapusSoal()">Hapus pertanyaan</button>
    <label for="ready">
      <input type="checkbox" name="ready" id="ready" role="switch" @checked(old('ready') ?? $exam->ready)>
      Ready
    </label>
    <button type="submit">Edit</button>
  </form>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    function createElement(tag, prop = {}) {
      return Object.assign(document.createElement(tag), prop);
    }
    let ol = query('ol');
    function tambahJawaban(prevElement) {
      console.log(prevElement.lastElementChild.dataset.jwbIdx)
      let jwbIdx = parseInt(prevElement.lastElementChild.dataset.jwbIdx) + 1;
      console.log(jwbIdx);
      let soalIdx = parseInt(prevElement.parentElement.dataset.soalIdx);
      let div = createElement('div', {
        innerHTML: `<input type="radio" name="soal[${soalIdx}][benar]" value="${jwbIdx}">
          <input type="text" name="soal[${soalIdx}][jawaban][${jwbIdx}]" placeholder="Jawaban..." required>`
      });
      div.dataset.jwbIdx = jwbIdx;
      prevElement.appendChild(div);
    }
    function hapusJawaban(prevElement) {
      if (prevElement.children.length > 1) {
        prevElement.removeChild(prevElement.lastElementChild);
      }
    }
    function tambahSoal() {
      let soalIdx = parseInt(ol.lastElementChild.dataset.soalIdx) + 1;
      let li = createElement('li', {
        innerHTML: `<input type="text" name="soal[${soalIdx}][soal]" placeholder="Soal..." required>
        <div>
          <div data-jwb-idx="0">
            <input type="radio" name="soal[${soalIdx}][benar]" value="0" checked>
            <input type="text" name="soal[${soalIdx}][jawaban][0]" placeholder="Jawaban..." required>
          </div>
        </div>
        <button type="button" onclick="tambahJawaban(this.previousElementSibling)">Tambah jawaban</button>
        <button type="button" onclick="hapusJawaban(this.previousElementSibling.previousElementSibling)">Hapus jawaban</button>`
      });
      li.dataset.soalIdx = soalIdx;
      ol.appendChild(li);
    }
    function hapusSoal() {
      if (ol.children.length > 1) {
        ol.removeChild(ol.lastElementChild);
      }
    }
    
  </script>
</x-base>