<x-base title="Kelola Grup" main-class="page col">
  <x-slot:head>
    <style>
      #groups {
        max-height: 400px;
        overflow-y: auto;
      }
    </style>
  </x-slot:head>
  <a href="{{ url('dashboard') }}" role="button">Kembali</a>
  <h1>Grup</h1>
  <button type="button" id="showModal">Buat Grup</button>
  <input type="search" id="search" placeholder="Cari nama/deskripsi grup...">
  <div id="groups"></div>
  <dialog id="createModal">
    <h2>Buat Grup</h2>
    <label>
      Nama
      <input type="text" name="nama" id="nama">
    </label>
    <label>
      Deskripsi
      <textarea name="deskripsi" id="deskripsi" rows="3"></textarea>
    </label>
    <input type="search" name="seacrh" id="searchStdn" placeholder="Cari nama/email user...">
    <div id="users" class="users"></div>
    <div class="btn-group">
      <button type="button" id="cancelBtn">Batal</button>
      <button type="button" id="createGroup">Buat</button>
    </div>
  </dialog>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    const BASEURL = "{{ url('/') }}";
    const csrfToken = "{{ csrf_token() }}";
    let searchInput = query("#search");
    let searchStdnInput = query("#searchStdn");
    let createBtn = query("#createGroup");
    let cancelBtn = query("#cancelBtn");
    let showModal = query("#showModal");
    let createModal = query("#createModal");
    let groupName = query("#nama");
    let groupDesc = query("#deskripsi");
    let chosenUsers = [];
    let timeoutId;

    async function get_groups(keyword = "") {
      let url = `{{ url('dashboard/get-groups') }}`;
      if(keyword !== '') {
        url += `?q=${encodeURIComponent(keyword)}`;
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
    async function get_students(keyword = "") {
      let url = `{{ url('dashboard/get-users') }}?type=no-admn`;
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
      const dataGrup = await get_groups(keyword);
      const list = query("#groups");
      if(!dataGrup) {return;}
      list.innerHTML = "";
      if(dataGrup.length < 1) {list.innerHTML = `<p class="empty" style="text-align: center;">-- Kosong --</p>`;}

      dataGrup.forEach((data) => {
        const el = document.createElement("div");

        el.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#7b0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <div>
          <span>${data.nama}</span>
          <p>${data.deskripsi}</p>
        </div>
        <a href="{{ url('dashboard/view-group/') }}/${data.id}" role="button">Detail</a>`;

        list.appendChild(el);
      });
    }

    async function renderStdnList(keyword = "") {
      if(keyword.length < 3 && keyword.length > 0) {return;}
      const dataSiswa = await get_students(keyword);
      const list = query("#users");
      if(!dataSiswa) {return;}
      list.innerHTML = "";
      if(dataSiswa.length < 1) {list.innerHTML = `<p class="empty" style="text-align: center;">-- Kosong --</p>`;}

      dataSiswa.forEach((data) => {
        const el = document.createElement("div");
        let selected = chosenUsers.includes(data.id) ? 'checked' : '';
        el.innerHTML = `
        <img src="${BASEURL}/${data.gmb_profil}">
        <div>
          <span>${data.nama}</span>
          <p>${data.email}</p>
        </div>
        <input type="checkbox" name="user-${data.id}" value="${data.id}" ${selected} onclick="userToggle(this)">`;

        list.appendChild(el);
      });
    }

    async function createGroup() {
      try {
        let res = await fetch(`{{ url('dashboard/create-group') }}`, {
          method: "POST",
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            'nama': groupName.value,
            'deskripsi': groupDesc.value,
            'users_id': chosenUsers
          })
        });
        if(!res.ok) {
          console.log('not ok:', res.status);
          return;
        }
        groupName.value = "";
        groupDesc.value = "";
        chosenUsers = [];
        let data = await res.text();
        renderList();
        console.log(data);
        createModal.close();
      } catch (err) {
        alert('Error!');
        window.location.reload();
        console.error(err);
      }
    }

    function userToggle(el) {
      let id = parseInt(el.value);
      if(el.checked) {
        if(chosenUsers.includes(id)) {return;}
        chosenUsers.push(id);
      } else {
        if(!chosenUsers.includes(id)) {return;}
        chosenUsers = chosenUsers.filter(item => item !== id);
      }
    }

    // event pencarian
    searchInput.addEventListener("input", function () {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        renderList(searchInput.value);
      }, 500);
    });

    searchStdnInput.addEventListener("input", function () {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        renderStdnList(searchStdnInput.value);
      }, 500);
    });

    showModal.onclick = () => {
      createModal.showModal();
    };

    cancelBtn.onclick = () => {
      createModal.close();
    };

    createBtn.onclick = () => {
      createGroup();
    };

    renderList();
    renderStdnList();
  </script>
</x-base>