<x-base title="Dashboard">
    <section style="display: flex; flex-direction: column; gap: 1rem;">
        <h1>Hi Admin!</h1>
        <a href="{{ url('dashboard/students') }}" role="button">Data Siswa</a>
        <a href="{{ url('dashboard/exams') }}" role="button">Kelola Ujian Ujian</a>
        <a href="{{ url('dashboard/create-exam') }}" role="button">Buat Ujian</a>
        <a href="{{ url('logout') }}" role="button">Log out</a>
    </section>
</x-base>