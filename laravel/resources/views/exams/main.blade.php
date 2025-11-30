<x-base title="Ujian {{ $exam->judul }}">
  <div>
    <h1>{{ $exam->judul }}</h1>
    <p>{{ $exam->deskripsi }}</p>
  </div>
  <div id="soal" data-idx="0">
    <p>{{ $question['soal'] }}</p>
    <div id="jwbn">
      @foreach($question['jawaban'] as $i => $jwb)
      <label for="jwbn-{{ $i }}">
        <input type="radio" name="jwbn-0" id="jwbn-{{ $i }}">
        {{ $jwb }}
      </label>
      @endforeach
    </div>
    <div>
      <button type="button" id="prev" onclick="prevQuestion()">&laquo;</button>
      <button type="button" id="next" oncancel="nextQuestion()">&raquo;</button>
    </div>
  </div>
  <div id="examNav">
    @for($i = 0; $i < $questions_count; $i++)
    <button type="button" data-idx="{{ $i }}">{{ $i + 1 }}</button>
    @endfor
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
    // vars
    let currIdx = 0;
    let soalDiv = query('#soal');
    let soalP = query('#soal p');
    let jwbn = query('#jwbn');
    let prev = query('#prev');
    let next = query('#next');
    let examNavButtons = queryAll('#examNav button');
    let questionsCount = examNavButtons.length;
    // question navigation
    function updateCurrIdx(idx = -1) {
      if(idx >= 0) {
        soalDiv.dataset.idx = idx;
      }
      currIdx = parseInt(soalDiv.dataset.idx);
      if(currIdx === (questionsCount - 1)) {
        next.textContent = 'Selesai';
      } else {
        next.textContent = '&raquo;';
        if(currIdx === 0)) {
          prev.style.display = 'none';
        } else {
          prev.style.display = 'block';
        }
      }
    }
    function changeQuestion(idx) {
      let data = fetch(`{{ url('exams/get-question') }}?idx=${idx}`, {
        credentials: 'include'
      }).then(res => {
        if(!res.ok) {
          throw new Error(`HTTP Error code: ${res.status}`);
        }
        return res.json();
      }).then(data => {
        updateCurrIdx(idx);
        soalP.innerContent = data.soal;
        jwbn.replaceChildren();
        let i = 0;
        data.jawaban.forEach(pilihan => {
          let choice = createElement('label', {
            'for': `jwbn-${i}`,
            'textContent': `<input type="radio" name="jwbn-0" id="jwbn-${i}">${pilihan}`
          });
          if(data.chosenAnswer === i) {
            choice.checked = true;
          }
          jwbn.appendChild(choice);
        });
      }).catch(err => {
        console.error('Fetch error:', err);
      });
    }
    function nextQuestion() {
      updateCurrIdx();
      if(currIdx < (questionsCount - 1)) {
        changeQuestion(currIdx + 1);
      }
    }
    function prevQuestion() {
      updateCurrIdx();
      if(currIdx > 0) {
        changeQuestion(currIdx - 1);
      }
    }
    updateCurrIdx();
    examNavButtons.forEach(button => {
      button.onclick = () => {
        changeQuestion(this.dataset.idx);
      }
    });
    
  </script>
</x-base>