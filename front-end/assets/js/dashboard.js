/* Progress Circle */
const progressCircle = document.getElementById("progressCircle");
const progressValue = document.getElementById("progressValue");

const radius = progressCircle.r.baseVal.value;
const circumference = 2 * Math.PI * radius;

progressCircle.style.strokeDasharray = `${circumference}`;
progressCircle.style.strokeDashoffset = `${circumference}`;

function setProgress(percent) {
  const offset = circumference - (percent / 100) * circumference;
  progressCircle.style.strokeDashoffset = offset;
  progressValue.textContent = `${percent}%`;
}

//  tolong jangan lupa di set
setProgress(50);

/* Show Exam Panel */
const btnKerjakan = document.getElementById("btnKerjakan");
const examPanel = document.getElementById("examPanel");

// ganti backend nanti:
const examReady = true; // true = tombol aktif, false = disable

if (!examReady) {
  btnKerjakan.classList.add("btn-disabled");
  btnKerjakan.disabled = true;
}

btnKerjakan.addEventListener("click", () => {
  if (btnKerjakan.disabled) return;
  examPanel.classList.remove("hidden");
  examPanel.scrollIntoView({ behavior: "smooth" });
});

const paymentStatus = "pending";

const box = document.getElementById("paymentStatusBox");
const text = document.getElementById("paymentStatusText");

if (paymentStatus === "confirmed") {
  box.className = "payment-status confirmed";
  text.textContent = "Sudah dikonfirmasi";
} else if (paymentStatus === "pending") {
  box.className = "payment-status pending";
  text.textContent = "Menunggu konfirmasi";
} else {
  box.className = "payment-status rejected";
  text.textContent = "Ditolak";
}
