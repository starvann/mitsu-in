# Dokumentasi Aplikasi Mitsu-in

**Mitsu-in** adalah aplikasi web pengelolaan pembinaan calon peserta program ke Jepang — mulai dari pendaftaran dan biodata calon peserta, verifikasi pembayaran, presensi harian, sampai ujian dan penilaian. Semua berjalan dari satu tempat, bisa diakses lewat browser HP maupun laptop.

Dokumen ini menjelaskan apa saja isi aplikasi, bagaimana setiap fitur bekerja, dan alur lengkapnya dari sudut pandang pengguna. Ditulis dengan bahasa yang mudah dibaca, sehingga bisa dipakai sebagai pegangan bersama untuk tim, pengguna baru, maupun developer yang baru bergabung.

---

## Daftar Isi

1. [Overview Aplikasi](#1-overview-aplikasi)
2. [Konsep Inti yang Perlu Diketahui Dulu](#2-konsep-inti-yang-perlu-diketahui-dulu)
3. [Detail Fitur](#3-detail-fitur)
   - [3.1 Splash, Onboarding, dan Halaman Masuk](#31-splash-onboarding-dan-halaman-masuk)
   - [3.2 Pendaftaran (Dua Tahap)](#32-pendaftaran-dua-tahap)
   - [3.3 Verifikasi Pembayaran](#33-verifikasi-pembayaran)
   - [3.4 Sistem Presensi](#34-sistem-presensi)
   - [3.5 Sistem Ujian](#35-sistem-ujian)
   - [3.6 Kelola Grup](#36-kelola-grup)
   - [3.7 Sistem Referral](#37-sistem-referral)
   - [3.8 Profil dan Keamanan Akun](#38-profil-dan-keamanan-akun)
4. [Alur Lengkap](#4-alur-lengkap)
5. [Data yang Disimpan Aplikasi](#5-data-yang-disimpan-aplikasi)
6. [Menjalankan Aplikasi](#6-menjalankan-aplikasi)
7. [Batasan dan Catatan Penting](#7-batasan-dan-catan-penting)

---

## 1. Overview Aplikasi

### Untuk apa aplikasi ini

Lembaga yang mengurus pendaftaran dan pembekalan calon peserta ke Jepang membutuhkan tempat terpusat untuk tiga hal yang biasanya berantakan: **administrasi peserta**, **presensi harian**, dan **penilaian lewat ujian**. Mitsu-in menjawab ketiganya dalam satu aplikasi:

- **Administrasi peserta** — pendaftaran berikut biodata lengkap, verifikasi pembayaran, pengelompokan dalam grup, dan sistem referral.
- **Presensi harian** — absen lewat pemindaian QR, pengajuan izin/sakit dengan bukti, sampai penandaan alpha otomatis.
- **Ujian** — pembuatan soal oleh admin, pengerjaan oleh peserta, dan penilaian otomatis yang hasilnya bisa dilihat seketika.

### Teknologi yang dipakai

Aplikasi ini dibangun dengan **Laravel 12 (PHP)** dan tampilan dirender langsung dari server (*server-side rendering*) memakai template Blade. Datanya disimpan di **SQLite** — cukup satu file, tanpa perlu memasang database server terpisah. Tampilan memakai CSS kustom, sementara interaksi seperti pencarian dan pengerjaan ujian dibantu JavaScript biasa tanpa library berat.

Konsekuensinya: setiap kali pengguna berpindah halaman, server merender ulang tampilan. Hal ini membuat aplikasi sederhana dan mudah dijalankan, namun sebagian besar interaksi (misalnya menyimpan jawaban) berjalan sebagai permintaan kecil ke server.

### Tiga peran pengguna

Setiap akun punya satu peran, dan peran inilah yang menentukan menu serta halaman mana yang boleh dibuka.

| Peran | Kode | Apa yang bisa dilakukan |
|---|---|---|
| **Admin** | `admn` | Mengelola seluruh data pengguna, memverifikasi pembayaran, membuat/mengedit/menghapus ujian, melihat dan mereset hasil ujian, mengelola grup, melihat rekap presensi peserta |
| **Siswa** | `stdn` | Mengisi biodata, presensi harian, mengajukan izin/sakit, mengerjakan ujian, melihat nilai sendiri, mengedit profilnya |
| **Referrer** | `refl` | Mendapatkan kode referral, membagikannya, dan memantau siapa saja yang mendaftar memakai kode tersebut |

### Dua status akun

Di samping peran, setiap akun punya status: **`pending`** atau **`accepted`**. Status ini berfungsi sebagai tiket masuk.

- **`pending`** — artinya "Proses Daftar Ulang". Akun sudah terdaftar, tetapi belum bisa memakai aplikasi. Pengguna diarahkan ke halaman pembayaran untuk mengunggah bukti transfer.
- **`accepted`** — artinya "Terdaftar". Akun sudah diverifikasi admin dan seluruh fitur bisa dipakai.

Pengecualian: akun **Referrer** langsung berstatus `accepted` begitu dibuat (tidak perlu menunggu verifikasi), sedangkan akun **Admin** yang dibuat lewat formulir pendaftaran umum tetap harus diverifikasi admin lain terlebih dahulu — admin yang masih `pending` akan langsung dikeluarkan dari aplikasi saat mencoba membuka dashboard.

### Peta halaman utama

| Alamat | Halaman | Siapa yang boleh |
|---|---|---|
| `/` | Splash (logo, otomatis pindah setelah 2 detik) | Semua |
| `/onboarding` | Halaman pembuka dengan ajakan masuk | Semua |
| `/login` | Formulir masuk | Belum login |
| `/register`, `/register2` | Pendaftaran tahap 1 dan tahap 2 | Belum login |
| `/dashboard` | Dashboard (isinya berbeda tiap peran) | Sudah login, sudah `accepted` |
| `/pending` | Halaman unggah bukti pembayaran | Sudah login, status `pending` |
| `/dashboard/students`, `/referrals`, `/admins` | Daftar siswa / referral / admin | Admin |
| `/dashboard/view-user/{id}` | Detail satu pengguna | Admin |
| `/dashboard/edit-user/{id}` | Edit data pengguna | Admin atau pemilik akun |
| `/dashboard/change-pass/{id}` | Ganti password | Hanya pemilik akun |
| `/dashboard/create-exam`, `/manage-exam` | Buat dan kelola ujian | Admin |
| `/dashboard/exam-result/{id}` | Hasil ujian sebuah ujian | Admin |
| `/exam/{id}` | Mengerjakan ujian / melihat hasil | Sudah login |
| `/presence` | Menerima presensi (via QR atau form izin) | Sudah login / pemegang token |
| `/presence-qr/{id}` | Gambar QR presensi | Terbuka (lihat [catatan](#7-batasan-dan-catan-penting)) |
| `/dashboard/presences/{id}` | Rekap presensi per pengguna | Admin |
| `/dashboard/groups` | Kelola grup | Admin |
| `/logout` | Keluar dari aplikasi | Sudah login |

---

## 2. Konsep Inti yang Perlu Diketahui Dulu

Lima istilah berikut muncul berulang di seluruh fitur. Memahaminya lebih dulu akan membuat penjelasan berikutnya jauh lebih ringkas.

1. **Kode peran saat mendaftar.** Formulir pendaftaran tahap 1 meminta sebuah kolom "Kode" berisi `admn`, `stdn`, atau `refl`. Kode inilah yang menentukan jenis akun yang terbentuk. Kolomnya sengaja tidak dibuka lebar — hanya tiga nilai tersebut yang diterima, dan umumnya kode diberikan oleh petugas.

2. **Status verifikasi (`pending`/`accepted`).** Gerbang antara "sudah terdaftar" dan "boleh memakai aplikasi". Selama masih `pending`, pengguna hanya bisa membuka halaman pembayaran.

3. **Token presensi.** Kode acak sepanjang 32 karakter yang menjadi kunci QR presensi. Token ini **hanya berlaku 15 menit** dan kemudian hangus, lalu diganti token baru. Tujuannya mencegah seseorang menyalin link presensi milik orang lain dan memakainya berulang kali.

4. **Sesi ujian.** Saat seseorang membuka ujian, seluruh daftar soal dan jawaban sementara disimpan di *sesi* — semacam ruang penyimpanan sementara milik browser tersebut di server. Jawaban baru benar-benar disimpan ke database ketika ujian dikumpulkan. Karena itu, menutup browser di tengah ujian membuat sesi hilang dan ujian harus dimulai ulang.

5. **Kode referral.** Kode 8 karakter unik yang dimiliki setiap Referrer. Calon peserta memasukkan kode ini saat mendaftar, sehingga aplikasi tahu siapa yang mengundang siapa.

---

## 3. Detail Fitur

### 3.1 Splash, Onboarding, dan Halaman Masuk

**Apa isinya:** Halaman pembuka aplikasi.

**Cara kerjanya:**

1. Pengunjung membuka `/` — tampil logo **MITSU.IN** selama 2 detik, lalu otomatis diarahkan ke halaman onboarding.
2. Halaman onboarding menampilkan ilustrasi kucing keberuntungan beserta semboyan *"Step into your future — the Japanese way!"* dan tombol panah untuk melanjutkan.
3. Tombol tersebut mengarahkan ke halaman **Login**.
4. Di halaman login, pengguna memasukkan email dan password, lalu menekan **Sign In**.
5. Ada pilihan **Remember Me**. Jika dicentang, pengguna tetap dianggap login meski browser ditutup, sehingga tidak perlu masuk lagi lain waktu.
6. Jika berhasil, pengguna diarahkan ke `/dashboard` — tampilannya berbeda tergantung peran (lihat [Detail Fitur](#3-detail-fitur)).

Selain itu, tersedia tautan **"Daftar di sini"** bagi yang belum punya akun.

---

### 3.2 Pendaftaran (Dua Tahap)

**Apa isinya:** Formulir pendaftaran yang dibagi menjadi dua tahap karena jumlah datanya cukup banyak.

**Siapa yang bisa:** Calon pengguna yang belum punya akun.

#### Tahap 1 — Data dasar (`/register`)

1. Pengguna mengisi **nama lengkap**, **email**, **password**, **ulangi password**, **foto profil** (opsional), dan **kode peran**.
2. Password harus memenuhi syarat: minimal 8 karakter, memuat huruf besar, huruf kecil, angka, dan simbol.
3. Ada aturan tambahan pada foto profil: kolomnya hanya muncul bila kode yang diisi `admn` atau `refl`. Untuk `stdn`, kolom disembunyikan karena foto akan diambil dari halaman berikutnya.
4. Setelah tombol **Register** ditekan, sistem memeriksa kode yang dimasukkan:
   - **`stdn`** — data disimpan sementara, pengguna diarahkan ke **Tahap 2**.
   - **`refl`** — akun langsung dibuat. Sistem membuatkan **kode referral unik 8 karakter** secara otomatis, status akun langsung `accepted`, lalu pengguna diarahkan ke halaman login.
   - **`admn`** — akun dibuat dengan status `pending`, menunggu verifikasi.
   - Kode lain — ditolak dengan pesan "Kode tidak valid."

#### Tahap 2 — Biodata lengkap (`/register2`)

Tahap ini berisi data yang sangat rinci karena menjadi bahan pertimbangan program:

- **Foto profil** — di tahap ini kolomnya tersedia untuk semua peran dan wajib diisi (pada tahap 1, kolom ini hanya muncul untuk kode `admn`/`refl`).
- **Data diri**: nomor HP, jenis kelamin, usia, tinggi/berat badan, status pernikahan, golongan darah, agama, alamat, tangan dominan.
- **Riwayat ke Jepang & dokumen**: pernah ke Jepang atau tidak, punya paspor, punya SIM A, sertifikat JLPT, sertifikat lain.
- **Pendidikan** — bisa diisi lebih dari satu baris (tahun, nama sekolah, jurusan).
- **Pengalaman** (opsional) — boleh dikosongkan.
- **Struktur keluarga** — beberapa baris: hubungan, nama, umur, pekerjaan, penghasilan.
- **Relasi di Jepang** (opsional) — jika satu saja kolomnya diisi, seluruh kolom wajib lengkap; jika dibiarkan kosong semua, datanya tidak disimpan.
- **Pertanyaan terbuka**: tujuan ke Jepang, tujuan setelah kembali, kelebihan, kekurangan, hobi, dan catatan tambahan.
- **Kode referral** (opsional) — diisi jika ada kode dari seorang Referrer. Kode divalidasi harus benar-benar milik salah satu Referrer yang terdaftar.

**Hal kecil yang perlu diperhatikan:** password disimpan sementara di sesi selama berpindah dari tahap 1 ke tahap 2, dan baru di-*hash* (diolah menjadi kode aman) ketika akun benar-benar dibuat. Sesi tersebut langsung dibersihkan setelah pendaftaran selesai.

Begitu selesai, pengguna diarahkan ke halaman login dengan pesan **"Pendaftaran Berhasil!"**, dan akunnya berstatus `pending`.

---

### 3.3 Verifikasi Pembayaran

**Apa isinya:** Gerbang pembuka fitur aplikasi. Tanpa verifikasi ini, peserta hanya bisa mengakses halaman pembayaran.

**Siapa yang terlibat:** Siswa (mengunggah bukti) dan Admin (memverifikasi).

#### Alur sisi siswa

1. Setelah login, siswa berstatus `pending` otomatis diarahkan ke halaman `/pending`.
2. Halaman menampilkan rincian pembayaran: **Rp 250.000**, rekening **BCA 123456789 a/n PT MITSU INDOJAYA**, dan **QRIS LPK MITS GAKUEN** lengkap dengan gambarnya.
3. Siswa mengunggah gambar bukti pembayaran (PNG/JPG/WEBP, maksimal 2 MB). Bisa juga mengganti berkasnya kembali jika salah unggah — berkas lama otomatis dihapus.
4. Setelah dikirim, status berubah menjadi **"Menunggu Konfirmasi Admin"**. Siswa hanya bisa menekan tombol **Refresh** dan menunggu.

#### Alur sisi admin

1. Admin membuka daftar siswa, lalu memilih salah satu untuk melihat detailnya.
2. Di halaman tersebut tampil gambar bukti pembayaran milik siswa tersebut.
3. Admin mengubah status dari **"Proses Daftar Ulang"** menjadi **"Terdaftar"** (atau sebaliknya).
4. Begitu status berubah, siswa tersebut otomatis mendapat akses penuh ke dashboard saat login berikutnya.

---

### 3.4 Sistem Presensi

**Apa isinya:** Pencatatan kehadiran harian peserta beserta rekap dan persentasenya.

**Siapa yang terlibat:** Siswa (melakukan presensi) dan Admin (memantau rekap).

Presensi punya **tiga jalur**, dan setiap orang hanya bisa tercatat **sekali per hari**.

#### Jalur 1 — Lewat QR / link presensi

1. Di dashboard siswa yang sudah `accepted` dan belum presensi hari ini, tampil sebuah **kotak QR**. QR ini bisa diklik, isinya sama dengan link presensi.
2. QR tersebut dibuat dari token acak 32 karakter yang **hanya berlaku 15 menit**. Setelah itu token hangus dan QR yang sama tidak akan berfungsi lagi — QR baru dibuatkan otomatis saat dashboard dibuka kembali.
3. Seseorang memindai QR (atau membuka link) dengan kamera/HP.
4. Sistem memeriksa: apakah token masih berlaku? Jika tidak, permintaan ditolak.
5. Sistem memeriksa: apakah orang ini sudah presensi hari ini? Jika sudah, permintaan ditolak.
6. Jika lolos, presensi tercatat dengan status **`hadir`**, lengkap dengan tanggal dan jam.
7. Pengguna yang sedang login akan diarahkan ke dashboard dengan pesan "Berhasil presensi"; yang tidak login cukup melihat pesan teks yang sama.

> Catatan: QR presensi dibuat per orang, jadi setiap siswa memiliki QR-nya sendiri.

#### Jalur 2 — Pengajuan izin, sakit, atau darurat

Digunakan ketika siswa berhalangan dan perlu melampirkan bukti.

1. Di dashboard, siswa yang belum presensi hari ini menemukan form **"Izin"**.
2. Dipilih statusnya: **Sakit**, **Izin**, atau **Darurat**.
3. Diisi **Alasan Mendukung** — minimal 24 karakter, supaya alasan benar-benar menjelaskan.
4. Diunggah **dokumen pendukung** berupa gambar (PNG/JPG/WEBP, maksimal 4 MB), misalnya surat keterangan dokumen.
5. Setelah dikirim, presensi tercatat dengan status sesuai pilihan, beserta alasan dan berkas buktinya.
6. Setelah salah satu jalur dipakai, form izin dan kotak QR hilang dari dashboard — karena orang tersebut sudah tercatat presensi hari ini.

#### Jalur 3 — Alpha otomatis

Ini yang bekerja tanpa campur tangan manusia.

1. Aplikasi menjalankan tugas terjadwal secara otomatis **4 kali sehari: pukul 09.00, 13.00, 17.00, dan 21.00**.
2. Sistem memeriksa seluruh siswa yang berstatus `accepted`.
3. Untuk setiap siswa, sistem menelusuri setiap hari **kerja** (Senin–Jumat) di bulan berjalan, mulai dari tanggal siswa tersebut bergabung.
4. Jika pada tanggal itu siswa **tidak punya catatan presensi sama sekali**, sistem membuat catatan berstatus **`alpha`** dengan tanggal yang bersangkutan.
5. Jika siswa sudah presensi (lewat QR maupun izin), tanggal tersebut dilewati.

Karena dijalankan berkali-kali sehari, siswa yang lupa presensi akan tetap tercatat alpha meski aplikasinya tidak pernah dibuka.

#### Persentase kehadiran

Persentase ditampilkan sebagai lingkaran (donat) berwarna di dashboard siswa dan bisa juga dilihat admin.

1. Sistem menghitung **jumlah hari kerja** pada bulan berjalan (Sabtu dan Minggu tidak dihitung).
2. Sistem menghitung berapa kali siswa berstatus `hadir` pada bulan tersebut.
3. Persentase = `jumlah hari hadir ÷ jumlah hari kerja × 100`.
4. Warna lingkaran menyesuaikan hasilnya:
   - **≤ 50%** — merah
   - **≤ 75%** — kuning/oranye
   - **> 75%** — hijau
5. Di samping lingkaran tertera rincian: berapa hari **hadir**, **alpha**, **darurat**, **izin**, dan **sakit**, beserta nama bulan.

#### Rekap untuk admin

1. Admin membuka daftar pengguna, memilih satu siswa, lalu masuk ke halaman rekap presensi.
2. Catatan presensi dikelompokkan per bulan, ditampilkan sebagai kotak-kotak tanggal berwarna: **hijau** untuk hadir, **merah** untuk alpha, **kuning** untuk sakit/izin/darurat.
3. Mengklik satu tanggal membuka detail: waktu presensi lengkap, status, alasan, dan gambar dokumen pendukung (jika ada).

---

### 3.5 Sistem Ujian

**Apa isinya:** Pembuatan soal oleh admin dan pengerjaan oleh siswa, dengan penilaian otomatis.

**Siapa yang terlibat:** Admin (membuat, mengelola, melihat hasil) dan Siswa (mengerjakan).

#### Sisi admin — membuat dan mengelola ujian

1. Admin masuk ke **Kelola Ujian** → menekan **Buat**.
2. Diisi **judul** (harus unik) dan **deskripsi**.
3. Ditambahkan soal satu per satu. Setiap soal terdiri dari:
   - teks pertanyaan,
   - beberapa pilihan jawaban (bisa ditambah sesuai kebutuhan),
   - penandaan **kunci jawaban** lewat radio button.
4. Dua opsi penting:
   - **Siap Rilis** — hanya ujian yang dicentang ini yang akan muncul di dashboard siswa. Centang ini sebagai langkah terakhir setelah ujian selesai disusun.
   - **Acak Soal** — urutan soal akan diacak berbeda-beda untuk setiap orang yang mengerjakan.
5. Tekan simpan. Soal-soal tersimpan terhubung ke ujian tersebut.
6. Dari halaman **Kelola Ujian**, admin bisa:
   - **Edit** — mengubah judul, deskripsi, mengubah/menambah/menghapus soal (soal yang tidak dikirim lagi otomatis terhapus),
   - **Lihat hasil** — daftar nilai seluruh peserta,
   - **Hapus ujian** — beserta seluruh soal dan hasilnya.

#### Sisi siswa — mengerjakan ujian

1. Di dashboard, siswa melihat daftar ujian yang sudah **dirilis**. Ujian yang sudah pernah dikerjakan menampilkan tombol **"Lihat Hasil"**, yang belum menampilkan **"Kerjakan"**.
2. Setelah konfirmasi, halaman ujian terbuka dan soal pertama dimuat.
3. Navigasi tersedia dalam tiga cara: tombol **« (sebelumnya)**, tombol **» (berikutnya)**, dan **nomor soal** di baris bawah.
4. Setiap kali berpindah soal, jawaban pada soal sebelumnya **otomatis dikirim** ke server dan disimpan di sesi.
5. Pada soal terakhir, tombol berikutnya berubah menjadi **"Kirim"**.
6. Setelah ditekan dan konfirmasi diberikan, jawaban terakhir ikut tersimpan, lalu ujian dikumpulkan.
7. Sistem membandingkan jawaban dengan kunci: jawaban yang kosong dihitung **salah**. Nilai = `benar ÷ jumlah soal × 100`.
8. Hasil langsung tampil: lingkaran skor berwarna (merah ≤50, kuning ≤75, hijau >75), jumlah benar, dan jumlah salah.
9. Membuka ujian yang sama lagi hanya menampilkan hasil — **tidak bisa mengerjakan ulang**.

#### Sisi admin — hasil dan reset

1. Di halaman **Hasil Ujian**, tersedia daftar peserta beserta nilai, jumlah benar, dan jumlah salah, lengkap dengan **kotak pencarian** (mengetik minimal 3 huruf untuk mencari nama/email).
2. **Hapus (untuk mengerjakan ulang)** — menghapus satu hasil. Peserta tersebut kini bisa mengerjakan ujian dari awal. Ini cara "mereset" ujian per orang.
3. **Hapus Semua Hasil Ujian** — menghapus seluruh hasil pada ujian tersebut sekaligus, sehingga semua peserta bisa mengerjakan ulang.

---

### 3.6 Kelola Grup

**Apa isinya:** Pengelompokan peserta — misalnya per angkatan, per daerah asal, atau per kelas pembekalan.

**Siapa yang bisa:** Admin saja.

**Cara kerjanya:**

1. Admin membuka halaman **Grup**, lalu menekan **Buat Grup**.
2. Diisi **nama** (harus unik) dan **deskripsi**.
3. Sambil mengetik nama, admin bisa **mencari pengguna** (nama atau email) dan menandai siapa saja yang masuk anggota awal. Boleh dilewati — grup bisa dikosongkan dulu.
4. Grup tersimpan. Daftar grup tampil di halaman utama, lengkap dengan **kotak pencarian** untuk menyaring berdasarkan nama/deskripsi.
5. Mengklik sebuah grup membuka halaman detail berisi daftar anggotanya.
6. Dari halaman edit, admin bisa:
   - **menambah atau mengeluarkan anggota** — daftar anggota disinkronkan, jadi yang tidak dipilih lagi akan dikeluarkan,
   - **mengubah nama dan deskripsi** grup.
7. Grup bisa dihapus; seluruh keterkaitan anggotanya ikut terhapus.

> Setiap grup juga memiliki kolom peran internal (default `member`), namun saat ini belum dipakai di antarmuka.

---

### 3.7 Sistem Referral

**Apa isinya:** Mekanisme mengundang orang lain dan melacak siapa yang datang melalui undangan.

**Siapa yang terlibat:** Referrer (pemberi kode) dan Admin (melihat hubungannya).

**Cara kerjanya:**

1. Saat seseorang mendaftar sebagai **Referrer**, sistem otomatis membuatkan **kode referral unik 8 karakter**, misalnya `aB3xK9zQ`. Kode ini dijamin tidak sama dengan milik Referrer lain.
2. Referrer membagikan kode tersebut kepada calon peserta.
3. Calon peserta memasukkan kode itu pada kolom **Kode Referral** di tahap 2 pendaftaran. Kode divalidasi — jika tidak terdaftar, pendaftaran ditolak.
4. Setelah terhubung, **dashboard Referrer** menampilkan:
   - kartu berisi kodenya (untuk disalin/dibagikan),
   - daftar orang yang memakai kode tersebut, beserta statusnya: *"Proses daftar ulang"* (belum diverifikasi) atau *"Terdaftar"* (sudah diverifikasi).
   - Jika belum ada yang memakai, tampil keterangan *"Belum ada yang memakai kode referralmu"*.
5. Sisi admin punya pandangan dua arah:
   - membuka detail seorang **siswa** → tampil siapa Referrer-nya,
   - membuka detail seorang **referrer** → tampil berapa banyak orang yang diundangnya.

---

### 3.8 Profil dan Keamanan Akun

**Apa isinya:** Mengubah data diri dan password.

#### Edit profil

- **Siswa dan Referrer** hanya bisa mengedit data miliknya sendiri, dan setelah disimpan akan kembali ke dashboard.
- **Admin** bisa mengedit data siapa pun, termasuk dua hal yang tidak bisa diubah sendiri oleh pengguna biasa: **status akun** (`pending`/`accepted`) dan **peran** (`stdn`/`refl`/`admn`).
- Form yang digunakan sama dengan biodata pendaftaran tahap 2, sehingga seluruh data bisa diperbarui: data diri, pendidikan, pengalaman, struktur keluarga, relasi di Jepang, dan pertanyaan terbuka.
- Jika mengunggah foto profil baru, foto lama otomatis dihapus (kecuali foto bawaan).

#### Ganti password

1. Pengguna membuka halaman **Ganti Password** (hanya bisa diakses untuk akun sendiri).
2. Diisi **password lama**, **password baru**, dan **ulangi password baru**.
3. Password lama diverifikasi dulu — jika salah, muncul pesan *"Password lama tidak sama!"*.
4. Password baru harus memenuhi syarat yang sama seperti saat mendaftar: minimal 8 karakter, campuran huruf besar/kecil, angka, dan simbol.
5. Jika berhasil, pengguna kembali ke dashboard dengan pesan *"Password telah diupdate!"*.

#### Menghapus akun (admin)

Ketika admin menghapus seorang pengguna, aplikasi ikut membersihkan berkas-berkas miliknya: seluruh dokumen presensi, bukti pembayaran, dan foto profil (kecuali foto bawaan). Data presensi, hasil ujian, dan keanggotaan grup pengguna tersebut ikut terhapus otomatis.

> Catatan: fitur **lupa password / reset lewat email belum tersedia**. Jika seseorang lupa password, perlu bantuan admin.

---

## 4. Alur Lengkap

Tiga cerita berikut memperlihatkan bagaimana beberapa fitur saling terhubung.

### 4.1 Siswa baru sampai menerima nilai

1. Membuka `/` → splash 2 detik → onboarding → halaman login → klik **Daftar di sini**.
2. Mengisi tahap 1 dengan kode `stdn`, lalu melanjutkan ke tahap 2 dengan biodata lengkap.
3. Masuk ke aplikasi → diarahkan ke halaman pembayaran.
4. Transfer Rp250.000, mengunggah bukti, lalu menunggu.
5. Admin memverifikasi → status berubah jadi *Terdaftar*.
6. Siswa masuk ke dashboard → memindai QR (atau mengajukan izin bila berhalangan).
7. Melihat lingkaran persentase kehadirannya.
8. Membuka ujian yang tersedia → mengerjakan → menekan **Kirim** → nilai langsung tampil.
9. Untuk mengubah data, siswa memakai **Edit Profil**; untuk keamanan, memakai **Ganti Password**.

### 4.2 Admin dalam sehari kerja

1. Masuk ke dashboard admin (harus sudah berstatus *Terdaftar*).
2. Membuka **Data Siswa** → memeriksa siapa saja yang masih *Proses Daftar Ulang* → membuka detail → melihat bukti bayar → mengubah statusnya.
3. Membuka **Kelola Ujian** → membuat ujian baru atau mengedit yang sudah ada → menandai **Siap Rilis** saat sudah siap.
4. Memantau **rekap presensi** tiap siswa, termasuk dokumen pendukung izin.
5. Memeriksa **hasil ujian** → mencari peserta → menghapus hasil tertentu bila ada yang perlu mengerjakan ulang.
6. Sesekali membuka **Kelola Grup** untuk menambah atau mengeluarkan anggota.
7. Mengelola **Data Referral** dan **Data Admin**.

### 4.3 Referrer mengundang peserta

1. Mendaftar dengan kode `refl` → akun langsung aktif dan kode referral diberikan.
2. Masuk ke dashboard → kode tampil di kartu referral.
3. Membagikan kode kepada calon peserta.
4. Calon peserta memasukkan kode saat tahap 2 pendaftaran.
5. Referrer memantau daftarnya — status *"Proses daftar ulang"* berarti orang tersebut belum membayar/diverifikasi; *"Terdaftar"* berarti sudah aktif.

---

## 5. Data yang Disimpan Aplikasi

Aplikasi menyimpan data dalam delapan tabel utama. Berikut isinya dalam bahasa sederhana:

| Tabel | Isinya |
|---|---|
| `users` | Seluruh pengguna: identitas, biodata lengkap (pendidikan, keluarga, tujuan ke Jepang), peran, status akun, kode referral, dan password |
| `presences` | Catatan presensi harian: siapa, tanggal, status (`hadir`/`sakit`/`izin`/`darurat`/`alpha`), alasan, dan berkas bukti |
| `exams` | Ujian: judul, deskripsi, penanda *siap rilis* dan *acak soal*, serta pembuatnya |
| `questions` | Soal-soal milik sebuah ujian: teks soal, pilihan jawaban (tersimpan sebagai daftar), dan kunci jawaban |
| `exam_results` | Hasil ujian per orang: nilai, jumlah benar, jumlah salah, dan seluruh jawaban yang diberikan |
| `groups` | Grup: nama dan deskripsi |
| `member_groups` | Penghubung antara grup dan anggotanya (banyak ke banyak) |
| `payment_proofs` | Bukti pembayaran satu pengguna (satu orang punya satu berkas) |

**Hubungan antar data dalam kalimat biasa:**

- Satu pengguna bisa memiliki **banyak** catatan presensi dan **banyak** hasil ujian.
- Satu ujian memiliki **banyak** soal dan **banyak** hasil ujian.
- Satu pengguna bisa tergabung dalam **banyak** grup, dan satu grup berisi **banyak** pengguna.
- Satu pengguna memiliki **satu** bukti pembayaran.
- Menghapus pengguna akan ikut menghapus presensi, hasil ujian, dan keanggotaan grup miliknya.

---

## 6. Menjalankan Aplikasi

### Prasyarat

- PHP 8.2 atau lebih baru, dengan seluruh ekstensi yang diminta Laravel aktif.
- Composer.

### Langkah

```bash
composer setup-dev     # memasang dependensi, menyiapkan .env, membuat database, lalu migrasi + seeder
php artisan serve      # menjalankan aplikasi di http://127.0.0.1:8000
```

`setup-dev` sudah mengerjakan semuanya: menyalin `.env` bila belum ada, membuat file database SQLite, menghasilkan kunci aplikasi, lalu menjalankan migrasi beserta data awal.

Untuk pengembangan, tersedia `composer dev` yang menjalankan server, pelacakan log, dan pembuatan aset secara bersamaan.

### Dua pengaturan `.env` yang wajib diperhatikan

1. **`APP_LOCALE=id`** — agar tanggal, nama bulan, dan format lokal tampil dalam Bahasa Indonesia.
2. **`FILESYSTEM_DISK=public`** — agar berkas yang diunggah (foto profil, bukti pembayaran, dokumen presensi) tersimpan di lokasi yang bisa diakses browser. Tanpa ini, unggahan tidak akan tampil.

### Akun admin bawaan

Seeding membuatkan satu akun admin yang sudah berstatus *Terdaftar*:

| Akun | Nilai |
|---|---|
| Email | `mimin@mitsu-in.com` |
| Password | `${N1-Ustim?}` |

> ⚠️ Password tersebut tertulis apa adanya di berkas seeder. **Segera ganti setelah pertama kali masuk**, dan jangan gunakan akun bawaan ini di lingkungan produksi.

---

## 7. Batasan dan Catatan Penting

Hal-hal berikut bukan kesalahan, melainkan **kondisi aplikasi saat ini** yang perlu diketahui agar ekspektasinya sesuai.

### Batasan fungsional

- **Belum ada reset password otomatis.** Tidak ada alur "lupa password" lewat email. Pengguna yang lupa password perlu dibantu admin.
- **Tidak ada notifikasi.** Baik untuk konfirmasi pembayaran maupun hasil ujian, pengguna perlu membuka halaman dan menekan **Refresh** secara manual.
- **Deadline ujian belum berfungsi.** Kolomnya tersedia di lapisan penyimpanan, tetapi belum ada form isinya maupun penegakan waktu pengerjaan. Saat ini ujian terbuka sampai admin mencabut status rilis.
- **Presensi hanya sekali sehari dan tidak bisa diurungkan.** Tidak ada tombol batal presensi maupun ubah status; koreksi dilakukan lewat admin.
- **Kode peran bersifat rahasia.** Form pendaftaran menerima `admn`, `stdn`, `refl`. Siapa pun yang mengetahui kode `admn` bisa mengajukan diri sebagai admin — meski tetap butuh verifikasi admin lain sebelum bisa masuk.
- **Token QR presensi berlaku 15 menit.** QR yang sudah lama ditampilkan tidak bisa dipakai; harus dibuka ulang dashboard untuk mendapatkan QR baru.

### Catatan teknis untuk developer

- **Halaman presensi terbuka tanpa login.** Route `/presence` dan `/presence-qr/{id}` berada di luar middleware autentikasi agar bisa dipindai siapa saja. Keamanannya bergantung pada kerahasiaan token 15 menit — pertimbangkan untuk menambah pembatasan (misalnya pembatasan IP atau jumlah percobaan) bila aplikasi dipakai publik.
- **Pengecekan "sudah presensi hari ini" memakai tanggal server**, bukan zona waktu pengguna. Pastikan waktu server sudah benar.
- **Penomoran soal ujian mengikuti urutan sesi**, bukan ID soal — aman karena kunci jawaban ikut disimpan dalam sesi yang sama.
- **Validasi unik nama grup saat mengedit** memeriksa tabel ujian, bukan tabel grup (`Rule::unique('exams', 'judul')`). Perlu diperbaiki agar nama grup bisa divalidasi dengan benar.
- **Route `GET /logout`** dipakai untuk keluar. Alangkah lebih aman memakai `POST` agar tidak bisa dipicu tanpa disengaja lewat pemuatan halaman.
- **Middleware `VisitorScheduler`** diimpor di berkas konfigurasi aplikasi, tetapi filenya tidak ada dan tidak pernah dipakai — bisa dihapus.
- **Fitur yang sudah ada meski di `README.md` masih bertanda belum selesai**: ganti password dan edit profil — keduanya sudah berjalan penuh.

---

*Dokumen ini menggambarkan perilaku aplikasi berdasarkan pembacaan kode pada saat penulisan. Jika terdapat perbedaan setelah pengembangan selanjutnya, perbarui bagian yang bersangkutan.*
