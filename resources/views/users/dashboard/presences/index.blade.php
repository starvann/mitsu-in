<x-base title="List Presensi" main-class="page col-center">
  <x-slot:head>
    <style>
      a.red {
        background-color: red;
      }
      a.green {
        background-color: forestgreen;
      }
      a.yellow {
        background-color: goldenrod;
      }
      .dgroup {
        width: 300px;
        background-color: white;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: center;
        border-radius: 8px;
      }
      .dgroup span {
        font-weight: 600;
        font-size: 16px;
      }
      .dgroup>div {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
      }
      .dgroup>div>a {
        width: 100%;
      }
    </style>
  </x-slot:head>
  <div class="hbox">
    <img src="{{ url($user->gmb_profil) }}" alt="Profil">
    <div>
      <span>{{ $user->nama }}</span>
      <p>{{ $user->email }}</p>
    </div>
  </div>
  <div>
    @forelse ($presences as $month => $presences2)  
    <div class="dgroup">
      <span>{{ $month }}</span>
      <div>
        @forelse ($presences2 as $pre)
          <a href="{{ url("dashboard/view-presence/$pre->id") }}" role="button" class="{{ $pre->status == 'hadir' ? 'green' : ($pre->status == 'alpha' ? 'red' : 'yellow') }}">{{ $pre->created_at->day }}</a>
        @empty
          <p class="empty">Kosong</p>
        @endforelse
      </div>
    </div>
    @empty
    <p class="empty">Kosong</p>
    @endforelse
  </div>
  <a href="{{ url("dashboard/view-user/$user->id") }}" role="button">Kembali</a>
</x-base>