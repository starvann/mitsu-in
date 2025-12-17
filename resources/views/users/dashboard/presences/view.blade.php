<x-base title="Detail Presensi" main-class="page col-center">
  <x-slot:head>
    <style>
      .user-details p img {
        width: 100%;
      }
      .bshadow {
        padding: 16px;
        border-radius: 8px;
        width: 300px;
        box-shadow: 0 0 5px 1px rgba(0,0,0,0.1);
      }
    </style>
  </x-slot:head>
  <div class="hbox">
    <img src="{{ url($presence->user->gmb_profil) }}" alt="Profil">
    <div>
      <span>{{ $presence->user->nama }}</span>
      <p>{{ $presence->user->email }}</p>
    </div>
  </div>
  <div class="bshadow">
    <div class="user-details">
      <span>Waktu & Tanggal Presensi</span>
      <p>{{ $presence->created_at->translatedFormat('H:i:s d F Y') }}</p>
    </div>
    <div class="user-details">
      <span>Status</span>
      <p>{{ ucfirst($presence->status) }}</p>
    </div>
    @if($presence->status != 'hadir' and $presence->status != 'alpha')
    <div class="user-details">
      <span>Alasan</span>
      <p>{{ $presence->alasan }}</p>
    </div>
    <div class="user-details">
      <span>Dokumen Tambahan</span>
      <p>
        <img src="{{ url($presence->doc_xtra) }}" alt="Dokumen Tambahan">
      </p>
    </div>
    @endif
  </div>
  <a href="{{ url("dashboard/presences") }}/{{ $presence->user->id }}" role="button">Kembali</a>
</x-base>