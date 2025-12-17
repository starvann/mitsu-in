# Mitsu-in

> woy jangan lupa check `.env`, edit `LOCALE = id` dan `FILESYSTEM_DISK = public`!

# How to run
1. Install PHP (enable extension yang diperlukan), dan install composer
2. `cd laravel`
3. `composer setup-dev` atau `composer setup`
4. `php artisan serve`


# Backend Feature List (todo)
## Sistem Auth
- [x] Login & Remember Me
- [x] Daftar
- [x] Dashboard
- [x] Role:
    - [x] Admin (dapat melihat data-data user dan membuat ujian)
    - [x] Student (siswa yang harus presensi, dan mengerjakan ujian)
    - [x] Referrer (user yang hanya bisa mengajak student lain)
- [] Ganti Password
- [] Edit Profil

## Sistem Presensi
- [x] Scan QR
- [x] Upload document untuk bukti (sakit/izin)
- [x] Presentase kehadiran
- [x] Auto alpha ketika tidak presensi sampai jam 09:00

## Sistem Ujian
- [x] CRUD Ujian dan Soal (admin only)
- [x] Mengerjakan Ujian
- [x] Melihat nilai
- [x] Mereset ujian (admin only)

## Grouping System
- [x] CRUD Grup
- [x] Menambahkan/kick user