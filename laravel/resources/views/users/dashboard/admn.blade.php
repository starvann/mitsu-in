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

      h1 {
        margin: 0 0 25px 0;
        font-size: 22px;
        color: #700000;
      }

      a[role="button"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 12px;
        background: #700000;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        text-decoration: none;
        transition: 0.3s;
      }

      a[role="button"]:hover {
        opacity: 0.9;
      }
    </style>
    </x-slot:head>
    <div class="card">
        <h1>Hi Admin!</h1>
        <a href="{{ url('dashboard/students') }}" role="button">Data Siswa</a>
        <a href="{{ url('dashboard/manage-exam') }}" role="button">Kelola Ujian</a>
        <a href="{{ url('dashboard/create-exam') }}" role="button">Buat Ujian</a>
        <a href="{{ url('logout') }}" role="button">Log out</a>
    </div>
</x-base>