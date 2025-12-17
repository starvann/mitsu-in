<x-base title="Dashboard">
    <x-slot:head>
    <style>
      body {
        margin: 0;
        background: #f3ecd2;
        font-family: Arial;
      }

      .card {
        width: 90%;
        max-width: 400px;
        margin: 60px auto;
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        text-align: center;
      }

      .card a {
        width: 100%;
      }

      h1 {
        margin: 0 0 25px 0;
        font-size: 22px;
        color: #700000;
      }


      
    </style>
    </x-slot:head>
    <div class="card">
        <h1>Hi Admin!</h1>
        <div class="btn-group">
          <a href="{{ url('dashboard/students') }}" role="button">Data Siswa</a>
          <a href="{{ url('dashboard/referrals') }}" role="button">Data Referral</a>
        </div>
        <div class="btn-group">
          <a href="{{ url('dashboard/admins') }}" role="button">Data Admin</a>
          <a href="{{ url('dashboard/groups') }}" role="button">Kelola Grup</a>
        </div>
        <a href="{{ url('dashboard/manage-exam') }}" role="button">Kelola Ujian</a>
        <div class="btn-group">
          <a href="{{ url('dashboard/change-pass') }}/{{ auth()->id() }}" role="button">Ganti Password</a>
          <a href="{{ url('dashboard/edit-user') }}/{{ auth()->id() }}" role="button">Edit Profil</a>
        </div>
        <a href="{{ url('logout') }}" role="button">Log out</a>
    </div>
</x-base>