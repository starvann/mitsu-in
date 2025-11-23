// ================== REGISTER ==================
const registerForm = document.getElementById("registerForm");

if (registerForm) {
  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = new FormData(registerForm);
    const name = form.get("name");
    const email = form.get("email");
    const password = form.get("password");
    const passwordConfirm = form.get("passwordConfirm");
    const roleCode = form.get("roleCode");

    if (password !== passwordConfirm) {
      alert("Password dan konfirmasi password tidak sama");
      return;
    }

    const payload = { name, email, password, roleCode };

    try {
      // PHP: /auth_register.php
      const data = await apiRequest("/auth_register.php", "POST", payload);

      // backend balikin { message: "...", user: {...} }
      alert(data.message || "Registrasi berhasil, silakan login");
      window.location.href = "login.html";
    } catch (err) {
      alert(err.message);
    }
  });
}

// ================== LOGIN ==================
const loginForm = document.getElementById("loginForm");

if (loginForm) {
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = new FormData(loginForm);
    const email = form.get("email");
    const password = form.get("password");
    const roleCode = (form.get("roleCode") || "").toUpperCase();

    const payload = { email, password, roleCode };

    try {
      // PHP: /auth_login.php
      const data = await apiRequest("/auth_login.php", "POST", payload);

      // { token: "xxx", user: { id, name, role: "STUDENT" | "ADMIN" | "REFERRAL" } }

      localStorage.setItem("token", data.token);
      localStorage.setItem("userRole", data.user.role);

      if (data.user.role === "STUDENT") {
        window.location.href = "student-exams.html";
      } else if (data.user.role === "ADMIN") {
        window.location.href = "admin-dashboard.html";
      } else if (data.user.role === "REFERRAL") {
        window.location.href = "referral-dashboard.html";
      } else {
        alert("Role tidak dikenal, hubungi admin");
      }
    } catch (err) {
      alert(err.message);
    }
  });
}
