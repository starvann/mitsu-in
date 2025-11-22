# Mitsu-in

> woy jangan lupa edit .env LOCALE ke id, FILESYSTEM_DISK ke public!

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
    - [ ] Admin (dapat melihat data-data user dan membuat ujian),
    - [x] Student (siswa),
    - [x] Referrer (user yang hanya bisa mengajak student lain)

## Sistem Presensi
- [x] Scan QR
- [x] Upload document untuk bukti (sakit/izin)
- [ ] Presentase kehadiran

## Sistem Ujian
- CRUD ujian
- CRUD soal