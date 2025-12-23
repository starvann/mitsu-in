<x-base title="Kelola {{ ucfirst($roleName) }}" no-main>
  <x-slot:head>
    <style>
      body {
        margin: 0;
        background: #f3ecd2;
        font-family: Arial;
      }

      .card {
        width: 90%;
        max-width: 450px;
        margin: 40px auto;
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
      }

      h1 {
        text-align: center;
        margin: 0 0 20px 0;
        color: #700000;
        font-size: 20px;
      }

      .student {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
        cursor: pointer;
      }

      .student:last-child {
        border-bottom: none;
      }

      .student img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
      }

      .student-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .student-info .name {
        font-size: 15px;
        font-weight: bold;
      }

      .student-info .desc {
        font-size: 13px;
        color: #666;
      }
      #list {
        max-height: 400px;
        overflow-y: auto;
      }
    </style>
  </x-slot:head>
  @if(session('success'))
  <div class="success-msg">
    {{ session('success') }}
  </div>
  @endif
  <div class="card">
    <h1>Data {{ ucfirst($roleName) }}</h1>
    <p>Jumlah Data : {{ $dataCount }} {{ ucfirst($roleName) }}</p>

    <div class="search-box">
      <input type="text" id="search" placeholder="Cari nama/email {{ $roleName }}..." />
    </div>

    <div id="list"></div>
    <a href="{{ url('dashboard') }}" role="button" style="align-self: center;">Kembali</a>
  </div>

  <script>
    const BASEURL = "{{ url('/') }}";
    let searchInput = document.getElementById("search");
    let timeoutId;

    async function get_student(keyword = "") {
      let url = `{{ url('dashboard/get-users') }}?type={{ $roleCode }}`;
      if(keyword !== '') {
        url += `&q=${encodeURIComponent(keyword)}`;
      }
      try {
        const response = await fetch(url);
        const result = await response.json();
        return result;
      } catch (err) {
        console.error("Fetch error:", err);
        return null;
      }
    }

    async function renderList(keyword = "") {
      if(keyword.length < 3 && keyword.length > 0) {return;}
      const dataSiswa = await get_student(keyword);
      const list = document.getElementById("list");
      if(!dataSiswa) {return;}
      list.innerHTML = "";
      if(dataSiswa.length < 1) {list.innerHTML = `<p class="empty" style="text-align: center;">-- Kosong --</p>`;}

      dataSiswa.forEach((s) => {
        const el = document.createElement("div");
        el.className = "student";

        // ketika diklik menuju halaman detail
        el.onclick = () => {
          window.location.href = "{{ url('dashboard/view-user') }}/" + s.id;
        };

        el.innerHTML = `
      <img src="${BASEURL + '/' + s.gmb_profil}">
      <div class="student-info">
        <div class="name">${s.nama}</div>
        <div class="desc">${s.email} • ${s.stat == 'accepted' ? 'Terdaftar' : 'Proses Daftar Ulang'}</div>
      </div>`;

        list.appendChild(el);
      });
    }

    // event pencarian
    searchInput.addEventListener("input", function () {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        renderList(searchInput.value);
      }, 500);
    });

    renderList();
  </script>
</x-base>