<x-base title="Dashboard" main-class="page" style="gap: 16px; display: flex; flex-direction: column;">
  <x-slot:head>
    <style>
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

      .student div {
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .student div span {
        font-size: 15px;
        font-weight: bold;
      }

      .student div p {
        font-size: 13px;
        color: #666;
      }
    </style>
  </x-slot:head>
  <div class="top-section">
    <div class="cover" style="background-image: url('{{ url('assets/img/cover-japan.jpg') }}')">
      <div class="avatar-wrapper">
        <img id="studentAvatar" src="{{ url($user->gmb_profil) }}" />
      </div>
    </div>
    <div class="header-block">
      <div id="studentName" class="student-name">{{ $user->nama }}</div>
    </div>
  </div>
  <a href="{{ url('logout') }}" role="button" style="align-self: center;">Log out</a>
  <div class="user-details">
    <span>Kode Referral</span>
    <p>{{ $user->kode_ref_saya }}</p>
  </div>
  @if($refUsers->isNotEmpty())
  <div class="user-details">
    <span>Jumlah Pengguna Kode Referral</span>
    <p>{{ $refUsers->count() }}</p>
  </div>
  @endif
  <div>
    @forelse($refUsers as $ref)
    <div class="student">
      <img src="{{ url($ref->gmb_profil) }}" alt="Foto Profil">
      <div>
        <span>{{ $ref->nama }}</span>
        <p>{{  $ref->stat == 'pending' ? 'Proses daftar ulang' : 'Terdaftar' }}</p>
      </div>
    </div>
    @empty
    <p class="empty">Belum ada yang memakai kode referralmu</p>
    @endforelse
  </div>
  
</x-base>