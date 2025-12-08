// =======================================
// DATA UJIAN (sekarang dummy, nanti dari admin/backend)
// =======================================
const examData = {
    title: "Ujian Seleksi Tahap 1",
    description: "Jawab dengan jujur dan sesuai kemampuanmu.",
    questions: [
      {
        id: 1,
        text: "Apa motivasimu untuk mengikuti program ke Jepang?",
        type: "single",
        options: [
          { value: "1", label: "Ingin bekerja di Jepang" },
          { value: "2", label: "Belajar budaya dan bahasa Jepang" },
          { value: "3", label: "Alasan lainnya" }
        ]
      }
  
      // nanti admin/backend bisa nambah lagi:
      // {
      //   id: 2,
      //   text: "Pertanyaan kedua...",
      //   type: "single",
      //   options: [ ... ]
      // }
    ]
  };
  
  
  // =======================================
  // RENDER KE HTML (pakai .choice-list + .choice-item)
  // =======================================
  function renderExam(data) {
    const root = document.getElementById("examRoot");
    if (!root) return;
  
    let html = `
      <div class="exam-header">
        <h2 class="section-title">${data.title}</h2>
        <p class="subtitle">${data.description}</p>
      </div>
  
      <form id="examForm">
    `;
  
    data.questions.forEach((q, index) => {
      html += `
        <div class="exam-question">
          ${index + 1}. ${q.text}
        </div>
        <div class="choice-list">
      `;
  
      if (q.type === "single") {
        q.options.forEach(opt => {
          const inputId = `q${q.id}-${opt.value}`;
  
          html += `
            <div class="choice-item">
              <input type="radio" id="${inputId}" name="q${q.id}" value="${opt.value}">
              <label for="${inputId}">${opt.label}</label>
            </div>
          `;
        });
      }
  
      html += `</div>`;
    });
  
    html += `
        <button type="submit" class="btn-primary" style="margin-top:16px;">
          Submit Jawaban
        </button>
      </form>
    `;
  
    root.innerHTML = html;
  
    // handler submit (frontend aja)
    const examForm = document.getElementById("examForm");
    examForm.addEventListener("submit", function (e) {
      e.preventDefault();
  
      // TODO: nanti backend temenmu baca jawaban dari form
      alert("Jawaban ujian sudah terkirim. Admin akan memeriksa hasilnya.");
  
      // contoh redirect:
      // window.location.href = "student-dashboard.html";
    });
  }
  
  renderExam(examData);
  