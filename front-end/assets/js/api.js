// ubah ini kalau nanti path di server beda
const API_BASE_URL = "http://localhost/mitsu-in/backend";

async function apiRequest(path, method = "GET", body) {
  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
    },
  };

  const token = localStorage.getItem("token");
  if (token) {
    options.headers["Authorization"] = `Bearer ${token}`;
  }

  if (body) {
    options.body = JSON.stringify(body);
  }

  const res = await fetch(`${API_BASE_URL}${path}`, options);

  let data;
  try {
    data = await res.json();
  } catch (e) {
    throw new Error("Response bukan JSON");
  }

  if (!res.ok) {
    throw new Error(data.message || "Terjadi kesalahan");
  }

  return data;
}
