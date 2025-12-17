<x-base title="Detail Grup" main-class="page col">
  <a href="{{ url('dashboard/groups') }}" role="button">Kembali</a>
  <h1>Detail <span>{{ $group->nama }}</span></h1>
  <p>{{ $group->deskripsi }}</p>
  <input type="search" id="search" placeholder="Cari nama/email user...">
  <div class="btn-group">
    <form action="{{ url("dashboard/del-group/$group->id") }}" method="post">
      @csrf
      @method('DELETE')
      <button type="submit" onclick="return confirm('Yakin?')">Hapus</button>
    </form>
    <a href="{{ url("dashboard/edit-group/$group->id") }}" role="button">Edit</a>
    <button type="button" id="showModal">Tambah User</button>
  </div>
  <div id="users" class="users"></div>
  <button type="button" onclick="save()" style="width: 100%;">Simpan Anggota Grup</button>
  <dialog id="addModal">
    <h1>Tambah User</h1>
    <input type="search" id="search2" placeholder="Cari nama/email user...">
    <div id="users2" class="users"></div>
    <button type="button" id="cancelBtn">Tutup</button>
  </dialog>
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
    const BASEURL = "{{ url('/') }}";
    const csrfToken = "{{ csrf_token() }}";
    let searchInput = query("#search");
    let searchInput2 = query("#search2");
    let cancelBtn = query("#cancelBtn");
    let showModal = query("#showModal");
    let addModal = query("#addModal");
    let chosenUsers = [];
    let timeoutId;

    async function get_students_of_group(keyword = "") {
      let url = `{{ url('dashboard/get-user-of-group') }}/{{ $group->id }}`;
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

    async function renderStdnList(keyword = "", users2 = false) {
      if(keyword.length < 3 && keyword.length > 0) {return;}
      let dataSiswa = [];
      if(users2) {
        dataSiswa = await get_students(keyword);
      } else {
        dataSiswa = await get_students_of_group(keyword);
      }
      let list = users2 ? query("#users2") : query("#users");
      if(!dataSiswa) {return;}
      list.innerHTML = "";
      if(dataSiswa.length < 1) {list.innerHTML = `<p class="empty" style="text-align: center;">-- Kosong --</p>`;}

      console.log(users2);
      dataSiswa.forEach((data) => {
        let id = data.id;
        if(users2 === true && chosenUsers.includes(id)) {return;}
        if(!users2) {console.log(data.nama);}
        const el = document.createElement("div");
        let selected = chosenUsers.includes(id) ? 'checked' : '';
        el.dataset.id = id;
        el.innerHTML = `
        <img src="${BASEURL}/${data.gmb_profil}" onclick="viewUser(${id})">
        <div onclick="viewUser(${id})">
          <span>${data.nama}</span>
          <p>${data.email}</p>
        </div>`;
        if(users2) {
          el.innerHTML += `<button type="button" onclick="addUser(this, ${id})"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg></button>`;
        } else {
          if(!chosenUsers.includes(id)) chosenUsers.push(id);
          el.innerHTML += `<button type="button" onclick="rmUser(this, ${id})"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/></svg></button>`;
        }

        list.appendChild(el);
      });
    }

    searchInput.addEventListener("input", function () {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        console.log('search');
        renderStdnList(searchInput.value);
      }, 500);
    });

    searchInput2.addEventListener("input", function () {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        renderStdnList(searchInput2.value, true);
      }, 500);
    });

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

    function addUser(el, id) {
      if(chosenUsers.includes(id)) {return;}
      let list = query("#users");
      let hr = query("#users hr");
      if(!hr) {
        list.prepend(createElement('hr'));
      }
      let sel = `#users2>div[data-id="${id}"]`
      let imgSrc = query(`${sel} img`).src;
      let nama = query(`${sel} div span`).innerHTML;
      let email = query(`${sel} div p`).innerHTML;
      let newElement = createElement('div');
      newElement.innerHTML = `
        <img src="${imgSrc}" onclick="viewUser(${id})">
        <div onclick="viewUser(${id})">
          <span>${nama}</span>
          <p>${email}</p>
        </div>
        <button type="button" onclick="rmUser(this, ${id})"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/></svg></button>`;
      list.prepend(newElement);
      el.parentElement.remove();
      chosenUsers.push(id);
    }

    function rmUser(el, id) {
      if(!chosenUsers.includes(id)) {return;}
      chosenUsers = chosenUsers.filter(item => item !== id);
      el.parentElement.remove();
      let list = query("#users");
      if(list.children[0].tagName == "HR") {
        list.children[0].remove();
      }
      setTimeout(() => {
      }, 200);
    }

    async function save() {
      let url = `{{ url("dashboard/edit-group/$group->id") }}`;
      try {
        const response = await fetch(url, {
          method: "PUT",
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            'users_id': chosenUsers
          })
        });
        const result = await response.text();
        window.location.reload();
      } catch (err) {
        console.error("Fetch error:", err);
        return false;
      }
    }

    function viewUser(id) {
      return window.open(`{{ url('dashboard/view-user/') }}/${id}`, '_blank');
    }

    showModal.onclick = () => {
      addModal.showModal();
    };

    cancelBtn.onclick = () => {
      addModal.close();
    };

    renderStdnList();
    renderStdnList("", true);
  </script>
</x-base>