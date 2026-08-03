# Panduan Menjalankan Multimedia Club Platform (CodeIgniter 4)

Dokumen ini berisi panduan langkah demi langkah untuk menginstal, mengonfigurasi, dan menjalankan proyek **Multimedia Club Platform** di lingkungan lokal.

---

## 📋 Prasyarat System

Pastikan perangkat Anda sudah terinstal perangkat lunak berikut:
- **PHP** `>= 8.2` (Ekstensi PHP wajib: `intl`, `mbstring`, `mysqli`, `curl`, `json`)
- **Composer** (Dependency Manager PHP)
- **MySQL** / **MariaDB** (via Laragon, XAMPP, atau MySQL standalone)
- **Web Server** (Apache / Nginx atau PHP CLI spark server)

---

## 🛠️ Langkah-Langkah Instalasi & Mengoperasikan

### 1. Clone / Buka Repository
Buka terminal (PowerShell/CMD/Bash) di direktori proyek:
```bash
cd c:\laragon\www\Multimedia-club-platform
```

### 2. Install Dependencies (Composer)
Jalankan perintah berikut untuk mengunduh library yang dibutuhkan oleh CodeIgniter 4:
```bash
composer install
```

---

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasinya (jika `.env` belum ada):
```bash
# Untuk Windows (Command Prompt / PowerShell):
copy env .env

# Untuk Linux / Mac / Git Bash:
cp env .env
```

Buka file `.env` dan pastikan konfigurasi sesuai dengan database lokal Anda:
```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = multimedia_club
database.default.username = root
database.default.password = ""
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci
```

---

### 4. Buat Database MySQL
Buat database bernama **`multimedia_club`** melalui:
- **phpMyAdmin**: `http://localhost/phpmyadmin` -> *New Database* -> nama `multimedia_club`.
- Atau via **MySQL CLI**:
  ```sql
  CREATE DATABASE multimedia_club CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

---

### 5. Jalankan Migration & Seeder Database

1. **Jalankan Database Migrations** (Membuat tabel-tabel):
   ```bash
   php spark migrate
   ```

2. **Jalankan Database Seeders** (Mengisi data awal & user demo):
   ```bash
   php spark db:seed DatabaseSeeder
   ```

---

### 6. Menjalankan Server Lokal

#### 🚀 Opsi A: Menggunakan PHP Spark (Direkomendasikan)
Jalankan perintah berikut di terminal:
```bash
php spark serve
```
Buka browser Anda dan akses:
👉 [http://localhost:8080](http://localhost:8080)

#### 🌐 Opsi B: Menggunakan Laragon VirtualHost
1. Pastikan folder proyek terletak di `C:\laragon\www\Multimedia-club-platform`.
2. Laragon akan otomatis membuat virtualhost seperti `http://multimedia-club-platform.test`.
3. Jika mengakses via Laragon VirtualHost, ubah `app.baseURL` di `.env` menjadi:
   ```env
   app.baseURL = 'http://multimedia-club-platform.test/'
   ```

---

## 🌐 Panduan Deployment ke Hosting (cPanel / VPS / Shared Hosting)

Berikut adalah panduan lengkap untuk melakukan pengunggahan dan penyebaran (*deployment*) **Multimedia Club Platform** ke cPanel Hosting atau VPS:

---

### 1. Persiapan Database Hosting

1. **Ekspor Database Lokal**:
   - Melalui **phpMyAdmin** lokal (`http://localhost/phpmyadmin`), pilih database `multimedia_club`.
   - Klik tab **Export** -> Format `SQL` -> Klik **Go** / **Export**.
2. **Buat Database di cPanel**:
   - Masuk ke cPanel -> Buka menu **MySQL® Databases**.
   - Buat Database Baru (contoh: `usercpanel_multimedia`).
   - Buat User Database Baru (contoh: `usercpanel_mmcuser`) beserta password yang kuat.
   - Hubungkan User ke Database dengan mencentang **ALL PRIVILEGES**.
3. **Impor SQL ke Hosting**:
   - Masuk ke menu **phpMyAdmin** di cPanel.
   - Pilih database `usercpanel_multimedia` -> Klik tab **Import** -> Upload file `.sql` lokal Anda.

---

### 2. Upload File Proyek ke Hosting

#### 🔒 Metode A: Standar Keamanan cPanel (Direkomendasikan)
Menjaga source code CodeIgniter 4 berada di luar akses publik untuk keamanan maksimal:

1. Compress seluruh folder proyek Anda menjadi file `.zip` (kecuali folder `node_modules` atau `.git`).
2. Masuk ke **File Manager** cPanel.
3. Upload dan Ekstrak file `.zip` di direktori utama akun Anda (sejajar dengan `public_html`), misalnya ke folder `/home/username/mmc-app/`.
4. Pindahkan **seluruh isi dalam folder `public/`** (seperti `index.php`, `.htaccess`, `assets/`, `favicon.ico`, dll) ke dalam direktori **`public_html/`**.
5. Edit file **`public_html/index.php`**:
   - Cari baris berikut:
     ```php
     $pathsPath = FCPATH . '../app/Config/Paths.php';
     ```
   - Ubah jalurnya mengarah ke folder source code Anda:
     ```php
     $pathsPath = FCPATH . '../mmc-app/app/Config/Paths.php';
     ```

Contoh domainnya:
public_html/mm.rasxmedia.my.id/public/
jadi di depan domain harus ada /public nya.

#### 📂 Metode B: Direct Subdomain / Root Hosting dengan `.htaccess`
Jika menggunakan subdomain (misal: `club.sman1tamansari.sch.id`) atau root folder terpisah:

1. Upload seluruh folder proyek ke dalam direktori subdomain/domain Anda.
2. Arahkan **Document Root** pada settings cPanel Subdomain langsung ke folder `public/` (contoh: `public_html/club/public`).

---

### 3. Konfigurasi Environment Produksi (`.env`)

Buat atau edit file **`.env`** di direktori utama source code Anda di hosting (`mmc-app/.env`):

```env
CI_ENVIRONMENT = production

app.baseURL = 'https://domain-anda.sch.id/'
app.indexPage = ''
app.forceGlobalSecureRequests = true

database.default.hostname = localhost
database.default.database = usercpanel_multimedia
database.default.username = usercpanel_mmcuser
database.default.password = "PasswordDatabaseHostingAnda123!"
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci

logger.threshold = 4
```

---

### 4. Pengaturan Izin Folder (*File Permissions*)

Pastikan folder berikut memiliki izin akses tulisan (*write permissions* **`775`** atau **`755`**) agar CodeIgniter 4 dapat menyimpan log, session, dan cache:
- `writable/cache`
- `writable/logs`
- `writable/session`
- `writable/uploads`

---

### 5. Verifikasi & SSL HTTPS

1. Pastikan **SSL Certificate** (AutoSSL / Let's Encrypt) aktif di cPanel Anda agar fitur kamera QR Code Scanner berfungsi di HP siswa.
2. Akses domain Anda di browser: `https://domain-anda.sch.id`.
3. Coba login menggunakan akun Super Admin / Pembina.

---

## 🔑 Akun Demo / Default Credentials

Semua akun default menggunakan password: **`password123`**

| Role | Username | Email |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin` | `admin@multimedia-sman1tamansari.sch.id` |
| **Pembina** | `pembina` | `pembina@multimedia-sman1tamansari.sch.id` |
| **BPH (Ketua)** | `bph_ketua` | `ketua@multimedia-sman1tamansari.sch.id` |
| **Member** | `rizki_member` | `rizki@gmail.com` |
| **Member** | `adit_member` | `adit@gmail.com` |
| **Member** | `fajar_member` | `fajar@gmail.com` |

---

## 💡 Perintah Spark yang Berguna (Cheat Sheet)

```bash
# Memeriksa status migration
php spark migrate:status

# Refresh migration & re-seed (Reset Database)
php spark migrate:refresh && php spark db:seed DatabaseSeeder

# Menjalankan unit test
php spark test
```

