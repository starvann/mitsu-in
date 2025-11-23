const studentForm = document.getElementById("studentForm");

if (studentForm) {
  studentForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = new FormData(studentForm);
    const payload = Object.fromEntries(form.entries());

    try {
      // endpoint PHP: /students_profile.php
      await apiRequest("/students_profile.php", "POST", payload);

      alert("Data pendaftaran berhasil disimpan.");
      // setelah isi data, arahkan ke halaman list ujian 
      window.location.href = "student-exams.html";
    } catch (err) {
      alert(err.message);
    }
  });
}
