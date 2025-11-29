# Mitsu-in

> woy jangan lupa check `.env`, edit `LOCALE = id` dan `FILESYSTEM_DISK = public`!

# How to run
1. Install PHP (enable extension yang diperlukan), composer
2. `cd laravel`
3. `composer setup`
4. `php artisan serve`


# Feature List (todo)
## Sistem Auth
- [x] Login
- [x] Daftar
- [x] Dashboard
- [x] Remember Me
- [x] Role:
    - [x] Admin (dapat melihat data-data user dan membuat ujian)
    - [x] Student (siswa yang harus presensi, dan mengerjakan ujian)
    - [x] Referrer (user yang hanya bisa mengajak student lain)

## Sistem Presensi
- [x] Scan QR
- [x] Upload document untuk bukti (sakit/izin)
- [x] Presentase kehadiran

## Sistem Ujian
- [x] CRUD Ujian dan Soal (admin only)
- [ ] Mengerjakan Ujian
- [ ] Melihat nilai
- [ ] Mereset ujian (admin only)
