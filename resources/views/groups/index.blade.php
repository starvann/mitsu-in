<x-base>
  <div>
    <h1>Grup</h1>
    <button type="button" id="toggleModal">Buat Grup</button>
    <input type="search" name="seacrh" id="search">
    <div id="groups"></div>
    <dialog id="createModal">
      <h2>Buat Grup</h2>
      <input type="text" name="nama" id="nama">
      <textarea name="deskripsi" id="deskripsi" cols="3"></textarea>
      <div id="users"></div>
      <div class="btn-group">
        <button type="button" id="cancelBtn">Batal</button>
        <button type="button" id="createGroup">Buat</button>
      </div>
    </dialog>
  </div>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
    const BASEURL = "{{ url('/') }}";
    let searchInput = query("#search");
    let createBtn = query("#createGroup");
    let cancelBtn = query("#cancelBtn");
    let toggleModalBtn = query("#toggleModal");
    let createModal = query("#createModal");
    let timeoutId;

    async function get_student(keyword = "") {
      let url = `{{ url('dashboard/get-groups') }}`;
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
      const dataGrup = await get_student(keyword);
      const list = document.getElementById("list");
      if(!dataGrup) {return;}
      list.innerHTML = "";
      if(dataGrup.length < 1) {list.innerHTML = `<p class="empty" style="text-align: center;">-- Kosong --</p>`;}

      dataGrup.forEach((data) => {
        const el = document.createElement("div");
        el.className = "grup";

        el.innerHTML = `
        <div></div>
        <div>
          <span>${data.nama}</span>
          <p>${data.deskripsi}</p>
        </div>
        <a href="{{ url('dashboard/view-group/') }}/${data.id}" role="button">Detail</a>`;

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

    toggleModalBtn.onclick = () => {
      createModal.open = !createModal.open;
    };

    cancelBtn.onclick = () => {
      createModal.open = !createModal.open;
    };

    renderList();
  </script>
</x-base>