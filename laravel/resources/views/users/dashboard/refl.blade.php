<x-base title="Dashboard" main-class="page" style="gap: 16px; display: flex; flex-direction: column;">
  <x-slot:head>
    <style>
      .student {
        display: flex;
        flex-direction: column;
        padding: 16px 0;
        margin: auto;
        max-width: 300px;
        cursor: pointer;
      }

      .student span {
        font-size: 15px;
        font-weight: bold;
      }

      .student p {
        font-size: 13px;
        color: #666;
      }
      .ref-card {
        background-color: white;
        padding: 16px;
        border-radius: 8px;
      }
      .ref-card > span {
        font-size: 18pt; 
        font-weight: 600;
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
  <div class="ref-card">
    <span>Kode Referral : {{ $user->kode_ref_saya }}</span>
    <div class="section-divider"></div>
    @forelse($refUsers as $ref)
    <div class="student">
      <span>{{ $ref->nama }}</span>
      <p>{{  $ref->stat == 'pending' ? 'Proses daftar ulang' : 'Terdaftar' }}</p>
    </div>
    @empty
    <p class="empty">Belum ada yang memakai kode referralmu</p>
    @endforelse
    <div class="section-divider"></div>
  </div>
  
</x-base>