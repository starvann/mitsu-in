<x-base title="Ujian {{ $exam->judul }}" main-class="page">
  <div class="header">
    <h1>{{ $exam->judul }}</h1>
    <p>{{ $exam->deskripsi }}</p>
  </div>
  <div class="qbox">
    <div id="soal" data-idx="0" style="display: flex; gap: 8px;">
      <span></span>
      <div>
        <p>...</p>
        <div id="jwbn" class="choices">
          ...
        </div>
      </div>
    </div>
    <div class="nav-buttons">
      <button type="button" id="prev" style="display: none;">&laquo;</button>
      <button type="button" id="next">&raquo;</button>
    </div>
  </div>
  <div id="examNav" class="qnav">
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
    let soalP = query('#soal div p');
    let soalSpan = query('#soal span');
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
        next.textContent = 'Kirim';
        next.onclick = () => {
          if(!confirm('Yakin ingin mengakhiri ujian dan melihat hasil?')) {
            return;
          }
          saveAnswer();
          document.location.href = "/exam-calc-result";
        };
      } else {
        next.innerHTML = '&raquo;';
        next.onclick = nextQuestion;
      }
      if(currIdx === 0) {
        prev.style.display = 'none';
      } else {
        prev.style.display = 'block';
      }
    }
    function saveAnswer() {
      let chosenAnswer = query(`input[name="jwbn-${currIdx}"]:checked`);
      if(chosenAnswer) {
        const saveUrl = "{{ url('exam-save-answer') }}";
        let answerIdx = Array.from(jwbn.children).indexOf(chosenAnswer.parentElement);
        fetch(`${saveUrl}?idx=${currIdx}&choice=${answerIdx}`, {
          credentials: 'include'
        }).then(res => {
          if(!res.ok) {
            throw new Error(`HTTP Error code: ${res.status}`);
          }
          return res.json();
        }).then(data => {
          console.log('Answer saved:', data);
        }).catch(err => {
          console.error('Fetch error:', err);
        });
      }
    }
    function changeQuestion(idx) {
      const url = "{{ url('exam-get-question') }}";
      saveAnswer();
      let data = fetch(`${url}?idx=${idx}`, {
        credentials: 'include'
      }).then(res => {
        if(!res.ok) {
          throw new Error(`HTTP Error code: ${res.status}`);
        }
        return res.json();
      }).then(data => {
        updateCurrIdx(idx);
        soalSpan.innerHTML = `${parseInt(idx) + 1}. `;
        soalP.innerHTML = data.soal;
        jwbn.replaceChildren();
        let i = 0;
        data.jawaban.forEach(pilihan => {
          let choice = createElement('label', {
            'innerHTML': `<input type="radio" name="jwbn-${currIdx}" id="jwbn-${i}">${pilihan}`,
            className: 'checkbox'
          });
          if(data.chosenAnswer === i) {
            choice.children[0].checked = true;
          }
          choice.setAttribute('for', `jwbn-${i}`);
          jwbn.appendChild(choice);
          i++;
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
    changeQuestion(0);
    prev.onclick = prevQuestion;
    next.onclick = nextQuestion;
    examNavButtons.forEach(button => {
      button.onclick = () => {
        changeQuestion(button.dataset.idx);
      }
    });
    
  </script>
</x-base>