<x-base title="Buat Ujian" main-class="container" no-main>
  <x-slot:head>
    <style>
      body {
        margin: 0;
        background: #f3ecd2;
        font-family: Arial;
      }
      .container {
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
      }
      h1 {
        margin: 0 0 5px 0;
        font-size: 26px;
        color: #700000;
      }
      .subtitle {
        color: #777;
        font-size: 14px;
        margin-bottom: 25px;
        text-align: left;
      }

      .question-box {
        display: flex;
        flex-direction: column;
        gap: 12px;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
      }

      .question-title {
        font-weight: bold;
        margin-bottom: 10px;
      }

      .option-group {
        margin-bottom: 10px;
        display: flex;
        gap: 10px;
      }

      .option-group input[type="text"] {
        flex: 1;
      }

      .btn-group {
        display: flex;
        gap: 10px;
      }
      .btn-group button {
        flex: 1;
      }

      button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        color: white;
        background: #700000;
      }

      button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
      }

      .btn-add {
        margin-top: 10px;
      }

      .btn-save {
        background: #700000;
        color: white;
        margin-top: 20px;
      }
      form {
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
      }
    </style>
  </x-slot:head>
  <div class="container">
    <a href="{{ url('dashboard/manage-exam') }}" role="button">Kembali</a>
    @if(!empty($errors->all()))
    <ul class="err-messages">
      @foreach($errors->all() as $err_msg)
      <li>{{ $err_msg }}</li>
      @endforeach
    </ul>
    @endif
    <form action="{{ url('dashboard/create-exam') }}" method="post">
      <h1>Buat Ujian</h1>
      <div class="subtitle">Masukkan pertanyaan dan kunci jawaban</div>
      @csrf
      <label>
        Judul
        <input type="text" name="judul" placeholder="Judul..." value="{{ old('judul') }}" @error('judul') aria-invalid="true" @enderror required>
      </label>
      <label>
        Deskripsi
        <textarea name="deskripsi" rows="3" placeholder="Deskripsi" @error('deskripsi') aria-invalid="true" @enderror required>{{ old('deskripsi') }}</textarea>
      </label>
      <label for="siap_rilis" class="checkbox">
        <input type="checkbox" name="siap_rilis" id="siap_rilis" role="switch" checked>
        Siap Rilis
      </label>
      <label for="acak_soal" class="checkbox">
        <input type="checkbox" name="acak_soal" id="acak_soal" role="switch" checked>
        Acak Soal
      </label>
      <div class="section-divider"></div>
      <div id="questions">
        @if(old('soal'))
          @foreach(old('soal') as $i => $soal)
          <div class="question-box" data-soal-idx="{{ $i }}">
            <label>
              Soal
              <textarea name="soal[{{ $i }}][soal]" rows="3" placeholder="..." @error("soal.".$i.".soal") aria-invalid="true" @enderror required>{{ $soal['soal'] }}</textarea>
            </label>
            <div>
              @foreach($soal['jawaban'] as $j => $jwb)
              <div data-jwb-idx="{{ $j }}" class="option-group">
                <input type="radio" name="soal[{{ $i }}][jwbn_yg_benar]" value="{{ $j }}" @checked($soal['jwbn_yg_benar'] ?? null == $j)>
                <input type="text" name="soal[{{ $i }}][jawaban][{{ $j }}]" placeholder="Jawaban..." value="{{ $jwb }}" @error("soal.".$i.".jawaban.".$j) aria-invalid="true" @enderror required>
              </div>
              @endforeach
            </div>
            <div class="btn-group">
              <button type="button" onclick="tambahJawaban(this.parentElement.previousElementSibling)">Tambah jawaban</button>
              <button type="button" onclick="hapusJawaban(this.parentElement.previousElementSibling, this)" @if(count($soal['jawaban']) <= 1) disabled @endif>Kurangi jawaban</button>
            </div>
            <button type="button" onclick="hapusSoal(this.parentElement)">Hapus Pertanyaan</button>
          </div>
          @endforeach
        @else
        <div class="question-box" data-soal-idx="0">
          <label>
            Soal
            <textarea name="soal[0][soal]" rows="3" placeholder="..." required></textarea>
          </label>
          <div>
            <div data-jwb-idx="0" class="option-group">
              <input type="radio" name="soal[0][jwbn_yg_benar]" value="0" checked>
              <input type="text" name="soal[0][jawaban][0]" placeholder="Jawaban..." required>
            </div>
          </div>
          <div class="btn-group">
            <button type="button" onclick="tambahJawaban(this.parentElement.previousElementSibling)">Tambah jawaban</button>
            <button type="button" onclick="hapusJawaban(this.parentElement.previousElementSibling, this)" disabled>Kurangi jawaban</button>
          </div>
          <button type="button" onclick="hapusSoal(this.parentElement)">Hapus Pertanyaan</button>
        </div>
        @endif
      </div>
      <button type="button" onclick="tambahSoal()">Tambah Pertanyaan</button>
      <button type="submit">Buat</button>
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
    let questions = query('#questions');
    function checkDelBtn(btn) {
      if(!btn.hasAttribute('disabled') && btn.parentElement.previousElementSibling.children.length <= 1) {
        btn.disabled = true;
      } else if (btn.hasAttribute('disabled') === true && btn.parentElement.previousElementSibling.children.length > 1) {
        btn.removeAttribute('disabled');
      }
    }
    function tambahJawaban(prevElement) {
      console.log(prevElement.lastElementChild.dataset.jwbIdx)
      let jwbIdx = parseInt(prevElement.lastElementChild.dataset.jwbIdx) + 1;
      console.log(jwbIdx);
      let soalIdx = parseInt(prevElement.parentElement.dataset.soalIdx);
      let div = createElement('div', {
        innerHTML: `<input type="radio" name="soal[${soalIdx}][jwbn_yg_benar]" value="${jwbIdx}">
          <input type="text" name="soal[${soalIdx}][jawaban][${jwbIdx}]" placeholder="Jawaban..." required>`,
        className: 'option-group'
      });
      div.dataset.jwbIdx = jwbIdx;
      prevElement.appendChild(div);
      checkDelBtn(prevElement.nextElementSibling.lastElementChild);
    }
    function hapusJawaban(prevElement, currBtn) {
      if (prevElement.children.length > 1) {
        prevElement.removeChild(prevElement.lastElementChild);
      }
      checkDelBtn(currBtn);
    }
    function tambahSoal() {
      let soalIdx = parseInt(questions.lastElementChild.dataset.soalIdx) + 1;
      let qbox = createElement('div', {
        innerHTML: `<label>
            Soal
            <textarea name="soal[${soalIdx}][soal]" rows="3" placeholder="..." required></textarea>
          </label>
          <div>
            <div data-jwb-idx="0" class="option-group">
              <input type="radio" name="soal[${soalIdx}][jwbn_yg_benar]" value="0" checked>
              <input type="text" name="soal[${soalIdx}][jawaban][0]" placeholder="Jawaban..." required>
            </div>
          </div>
          <div class="btn-group">
            <button type="button" onclick="tambahJawaban(this.parentElement.previousElementSibling)">Tambah jawaban</button>
            <button type="button" onclick="hapusJawaban(this.parentElement.previousElementSibling, this)" disabled>Kurangi jawaban</button>
          </div>
          <button type="button" onclick="hapusSoal(this.parentElement)">Hapus Pertanyaan</button>`,
        className: 'question-box'
      });
      qbox.dataset.soalIdx = soalIdx;
      questions.appendChild(qbox);
    }
    function hapusSoal(qbox) {
      if (questions.children.length < 2) {return;}
      questions.removeChild(qbox);
      const qboxes = queryAll('.question-box');
      qboxes.forEach((box, idx) => {
        box.dataset.soalIdx = idx;
        const inputs = box.querySelectorAll('input[name^="soal["], textarea[name^="soal["]');
        inputs.forEach(input => {
          const name = input.getAttribute('name');
          const newName = name.replace(/soal\[\d+\]/, `soal[${idx}]`);
          input.name = newName;
        });
      });
    }
  </script>
</x-base>