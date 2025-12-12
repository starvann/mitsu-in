<x-base title="Hasil Ujian" main-class="page" style="display: flex; flex-direction: column; gap: 12px;">
  <a href="{{ url('dashboard/manage-exam') }}" role="button">Kembali</a>
  <h1 style="color: #7b0000;">Hasil "{{ $exam->judul }}"</h1>
  @if($results->isNotEmpty())
    <form action="{{ url("/dashboard/del-all-exam-result/$exam->id") }}" method="post">
      @csrf
      @method('DELETE')
      <button type="submit" onclick="return confirm('Yakin?')">Hapus Semua Hasil Ujian</button>
    </form>
  @endif
  @if(session('success'))
  <div class="success-msg">
    {{ session('success') }}
  </div>
  @endif
  <div>
    @if($results->isNotEmpty())
    <div class="search-box">
      <input type="text" id="search" placeholder="Cari nama/email siswa..." />
    </div>
    @endif
    <div id="results"></div>
  </div>
  <script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const DEL_URL = "{{ url('/dashboard/del-exam-result') }}"
    const F_URL = `{{ url("dashboard/get-exam-results/$exam->id") }}`;
    let searchInput = document.getElementById("search");
    let timeoutId;

    async function get_results(keyword = "") {
      let url = F_URL;
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

    async function renderList(keyword = "") {
      if(keyword.length < 3 && keyword.length > 0) {return;}
      const examResults = await get_results(keyword);
      const list = document.getElementById("results");
      if(!examResults) {return;}
      list.innerHTML = "";
      if(examResults.length < 1) {list.innerHTML = `<p class="empty" style="text-align: center;">-- Kosong --</p>`;}

      examResults.forEach((data) => {
        const el = document.createElement("details");

        el.innerHTML = `
      <summary>
        <div>
          <b>${data.nama}</b>
          <small>${data.email}</small>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
          <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
        </svg>
      </summary>
      <div>
        <p>Nilai : ${data.nilai}</p>
        <p>Benar : ${data.total_benar}</p>
        <p>Salah : ${data.total_salah}</p>
        <form action="${DEL_URL}/${data.id}" method="post">
          <input type="hidden" name="_token" value="${CSRF_TOKEN}">
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" onclick="return confirm('Yakin?')">Hapus (untuk mengerjakan ulang)</button>
        </form>
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