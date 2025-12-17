<x-base title="Lihat Bukti Pembayaran" main-class="page col-center">
  <div class="hbox">
    <img src="{{ url($user->gmb_profil) }}" alt="Foto Profil">
    <div>
      <span>{{ $user->nama }}</span>
      <p>{{ $user->email }}</p>
    </div>
  </div>
  <div style="width: 340px; background-color: white; border-radius: 8px; padding: 16px;" class="col">
    <div class="user-details">
      <span>Bukti Pembayaran</span>
      <p>
        <img src="{{ $user->paymentProof ? url($user->paymentProof->file) : '' }}" alt="Bukti" style="width: 100%; height: auto;">
      </p>
    </div>
    <form action="{{ url('/dashboard/edit-user/'.$user->id) }}" method="post" id="edit-user">
      @csrf
      @method('PUT')
      <input type="hidden" name="stat_only" value="true">
      <div class="user-details">
        <span>Status</span>
        <select name="stat" onchange="query('#edit-user').submit()">
          <option value="pending" @selected($user->stat == 'pending')>Proses Daftar Ulang</option>
          <option value="accepted" @selected($user->stat != 'pending')>Terdaftar</option>
        </select>
      </div>
    </form>
  </div>
  <a href="{{ url('dashboard/students') }}" role="button">Kembali</a>
  <script>
    function query(s) {
      return document.querySelector(s);
    }
  </script>
</x-base>