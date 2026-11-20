# DEPLOY.md — E-Chetak's

Panduan deploy aplikasi ini ke server/VPS. Ditulis untuk **manusia dan AI agent**: setiap langkah berisi perintah yang bisa langsung dijalankan beserta cara memverifikasi hasilnya.

## 0. Ringkasan

| | |
|---|---|
| Jenis aplikasi | PHP native (tanpa framework) + MySQL, **tidak ada build step** (tidak perlu `composer install` / `npm install`) |
| Versi yang diuji | PHP 8.3 (container) & 8.4 (lokal), MySQL 8.0 |
| Ekstensi PHP wajib | `pdo_mysql`, `gd` (dengan FreeType + JPEG — dipakai captcha admin) |
| Database | `chetaks` — skema + sample data di `chetaks.sql` |
| Folder yang harus *writable* | `img/product/`, `desain/`, `buktibayar/` |
| Konfigurasi | Environment variable `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` (dibaca `koneksi.php`) |
| Login admin bawaan | `admin` / `admin123` → **wajib diganti** (langkah 3) |

**Dua jalur deploy:**

| Jalur | Kapan dipakai | Status pengujian |
|---|---|---|
| **A. Docker Compose** (direkomendasikan) | Default. Paling sedikit langkah, hasil identik di mana saja. | ✅ Diuji end-to-end di mesin dev: build, init DB, pesan → bayar → login admin + captcha → terima pembayaran, upload tersimpan di volume |
| **B. Nginx + PHP-FPM + MySQL (native)** | Kalau VPS tidak boleh/tidak mau pakai Docker. | ⚠️ Ditulis dari konfigurasi standar, **belum dijalankan** di VPS sungguhan. Ikuti verifikasi di langkah akhir. |

> ### ⚠️ Baca dulu sebelum membuka ke internet
> Aplikasi ini proyek kuliah. Sebagian query SQL masih menyisipkan input langsung (**rentan SQL injection**) — lihat [Bagian 7](#7-keamanan-yang-perlu-kamu-ketahui). Sebelum dipublikasikan luas, pilih salah satu: (a) perbaiki query-nya, atau (b) batasi akses (mis. basic-auth Nginx/Caddy untuk seluruh situs, atau VPN/Tailscale). Untuk demo/portofolio pribadi, (b) paling cepat.

---

## Untuk AI agent di VPS (baca ini dulu)

Urutan kerja yang disarankan — ikuti Jalur A kecuali manusia menyatakan lain:

1. **Jangan** menebak password. Generate sendiri password acak (`openssl rand -base64 24`) untuk `DB_PASS` dan `DB_ROOT_PASS`, tulis hanya ke `.env`, dan **jangan** menampilkan/commit/mengirimnya ke layanan luar.
2. **Jangan** menjalankan `docker compose down -v`, `docker volume rm`, atau `DROP DATABASE` kecuali manusia secara eksplisit memintanya — itu menghapus data pelanggan (pesanan, bukti bayar).
3. `chetaks.sql` hanya dieksekusi otomatis **saat volume database masih kosong**. Menjalankan ulang `up` **tidak** me-reset data.
4. Setelah deploy, jalankan seluruh checklist di [Bagian 6](#6-checklist-verifikasi) dan laporkan hasilnya (lulus/gagal per item) ke manusia.
5. Hal yang **hanya bisa dilakukan manusia**: mengarahkan DNS domain ke IP VPS, dan memutuskan membuka situs ke publik atau tidak (lihat peringatan di atas).
6. Ganti password admin (langkah 3) sebelum situs terjangkau dari internet.

---

## 1. Prasyarat

- VPS Linux (ditujukan untuk Ubuntu 22.04 / 24.04), RAM ≥ 1 GB, akses `sudo`.
- Port **80** dan **443** terbuka (untuk HTTPS). Port aplikasi (8080) **tidak** perlu dibuka ke publik.
- (Opsional tapi disarankan) domain yang sudah di-A-record ke IP VPS, mis. `chetaks.example.com`.
- Kode project tersedia di VPS (`git clone <url-repo> /opt/echetaks` atau upload).

```bash
sudo apt update && sudo apt install -y git curl ufw
```

---

## 2. Jalur A — Docker Compose (direkomendasikan)

### 2.1 Install Docker

```bash
curl -fsSL https://get.docker.com | sudo sh
sudo usermod -aG docker "$USER"   # logout/login ulang agar berlaku
docker compose version            # harus tampil v2.x
```

### 2.2 Ambil kode

```bash
sudo git clone <URL_REPO> /opt/echetaks   # ganti URL_REPO
sudo chown -R "$USER": /opt/echetaks
cd /opt/echetaks
```

### 2.3 Buat file `.env`

```bash
cp .env.example .env
# isi password acak (jangan pakai nilai contoh!)
sed -i "s|^DB_PASS=.*|DB_PASS=$(openssl rand -hex 16)|" .env
sed -i "s|^DB_ROOT_PASS=.*|DB_ROOT_PASS=$(openssl rand -hex 16)|" .env
chmod 600 .env
```

Isi `.env` (lihat `.env.example`):

| Variable | Arti | Default |
|---|---|---|
| `DB_NAME` | nama database | `chetaks` |
| `DB_USER` | user MySQL untuk aplikasi | `chetaks` |
| `DB_PASS` | password user aplikasi | **wajib diisi** |
| `DB_ROOT_PASS` | password root MySQL | **wajib diisi** |
| `APP_BIND` | alamat bind port aplikasi di host | `127.0.0.1` (hanya lewat reverse proxy) |
| `APP_PORT` | port aplikasi di host | `8080` |

> `.env` sudah ada di `.gitignore`. Jangan di-commit.

### 2.4 Jalankan

```bash
docker compose up -d --build
docker compose ps          # db = healthy, app = running
```

Yang terjadi: image PHP 8.3 + Apache dibuild, MySQL 8 dijalankan, `chetaks.sql` di-import otomatis (hanya first run), dan tiga folder upload dipasang sebagai **named volume** (`echetaks_up_product`, `echetaks_up_desain`, `echetaks_up_bukti`) sehingga aman saat rebuild.

Cek cepat:

```bash
curl -s -o /dev/null -w '%{http_code}\n' http://127.0.0.1:8080/index.php   # 200
```

### 2.5 HTTPS + domain (Caddy — otomatis dapat sertifikat Let's Encrypt)

```bash
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | sudo tee /etc/apt/sources.list.d/caddy-stable.list
sudo apt update && sudo apt install -y caddy
```

`/etc/caddy/Caddyfile`:

```caddyfile
chetaks.example.com {
    request_body {
        max_size 12MB
    }
    reverse_proxy 127.0.0.1:8080

    # OPSIONAL tapi disarankan sebelum SQL injection diperbaiki:
    # basic_auth {
    #     demo  <hash dari: caddy hash-password>
    # }
}
```

```bash
sudo systemctl reload caddy
sudo ufw allow OpenSSH && sudo ufw allow 80,443/tcp && sudo ufw --force enable
curl -sI https://chetaks.example.com/ | head -1      # HTTP/2 200
```

> Tanpa domain? Lewati Caddy, set `APP_BIND=0.0.0.0` di `.env`, `docker compose up -d`, buka port 8080 di firewall, dan akses `http://IP_VPS:8080`. Tidak ada HTTPS — hanya untuk uji coba.

---

## 3. Ganti password admin (WAJIB)

Password disimpan sebagai `SHA2(password, 256)` (tanpa salt — peninggalan kode asli). Ganti dari baris perintah:

**Docker:**
```bash
cd /opt/echetaks
read -rs NEWPASS; echo            # ketik password baru (jangan mengandung tanda petik ')
printf "UPDATE admins SET passwd=SHA2('%s',256) WHERE username='admin';\n" "$NEWPASS" \
  | docker compose exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" chetaks'
unset NEWPASS
```

**Native:**
```bash
sudo mysql chetaks -e "UPDATE admins SET passwd=SHA2('PASSWORD_BARU',256) WHERE username='admin';"
```

Verifikasi: login `/admin/login.php` dengan password lama harus **gagal**, password baru **berhasil**. (Catatan: login memakai `SHA2(:password,0)` — `0` setara 256-bit.)

---

## 4. Operasional (Jalur A)

Semua perintah dijalankan dari `/opt/echetaks`.

| Tugas | Perintah |
|---|---|
| Lihat status | `docker compose ps` |
| Log aplikasi (Apache/PHP) | `docker compose logs -f app` |
| Log database | `docker compose logs -f db` |
| Restart | `docker compose restart` |
| **Update ke versi terbaru** | `git pull && docker compose up -d --build` |
| Stop (data aman) | `docker compose down` |

> `docker compose down` **tanpa** `-v` aman. **Dengan `-v` = menghapus database & upload.**

### Backup

```bash
mkdir -p ~/backup && cd /opt/echetaks
# 1) Database
docker compose exec -T db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --single-transaction chetaks' \
  | gzip > ~/backup/chetaks-$(date +%F).sql.gz
# 2) Upload (desain, bukti bayar, gambar produk)
for v in up_product up_desain up_bukti; do
  docker run --rm -v echetaks_$v:/data -v ~/backup:/backup alpine \
    tar czf /backup/$v-$(date +%F).tgz -C /data .
done
```

Otomatiskan harian: `crontab -e` → `0 3 * * * cd /opt/echetaks && <perintah backup di atas>`.

### Restore

```bash
gunzip -c ~/backup/chetaks-YYYY-MM-DD.sql.gz | \
  docker compose exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" chetaks'
docker run --rm -v echetaks_up_desain:/data -v ~/backup:/backup alpine \
  sh -c 'cd /data && tar xzf /backup/up_desain-YYYY-MM-DD.tgz'
```

### Rollback versi kode

```bash
git log --oneline -5            # cari commit yang baik
git checkout <commit> && docker compose up -d --build
```

### Migrasi database lama (hanya jika DB dibuat dari `chetaks.sql` versi lama)

Bila database sudah ada dari dump lama (kolom `varchar(40/50/30)`), pelebaran kolom **tidak** otomatis. Jalankan sekali:

```sql
ALTER TABLE pemesanan  MODIFY desain_produk varchar(255) COLLATE utf8_bin NOT NULL;
ALTER TABLE pembayaran MODIFY bukti_bayar   varchar(255) COLLATE utf8_bin NOT NULL;
ALTER TABLE produk     MODIFY gambar_produk varchar(255) COLLATE utf8_bin NOT NULL;
```

---

## 5. Jalur B — Native (Nginx + PHP-FPM + MySQL)

> ⚠️ Jalur ini belum dijalankan di VPS sungguhan. Ikuti verifikasi [Bagian 6](#6-checklist-verifikasi) dan laporkan bila ada yang berbeda.

### 5.1 Install paket (Ubuntu 24.04: PHP 8.3; 22.04: PHP 8.1 — keduanya cukup)

```bash
sudo apt update
sudo apt install -y nginx mysql-server php-fpm php-mysql php-gd git
php -v && php -m | grep -E 'gd|pdo_mysql'     # keduanya harus muncul
php -r 'var_dump(function_exists("imagettftext"));'   # bool(true) -> FreeType ada
```

### 5.2 Database

```bash
sudo mysql <<'SQL'
CREATE DATABASE chetaks CHARACTER SET utf8mb4;
CREATE USER 'chetaks'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON chetaks.* TO 'chetaks'@'localhost';
SQL
```

(Import `chetaks.sql` dilakukan di 5.3 setelah kode ada.)

### 5.3 Kode & permission

```bash
sudo git clone <URL_REPO> /var/www/echetaks
cd /var/www/echetaks
sudo chown -R www-data:www-data img/product desain buktibayar     # folder upload
sudo chmod -R u+rwX,go-w img/product desain buktibayar
sudo mysql chetaks < chetaks.sql                         # import skema + sample data
# chetaks.sql tetap di web root tapi diblok Nginx (aturan *.sql di 5.5) -> verifikasi di Bagian 6, tes #4
```

### 5.4 Environment variable untuk PHP-FPM

Edit pool (`/etc/php/<versi>/fpm/pool.d/www.conf`), tambahkan di bagian bawah:

```ini
env[DB_HOST] = 127.0.0.1
env[DB_PORT] = 3306
env[DB_NAME] = chetaks
env[DB_USER] = chetaks
env[DB_PASS] = GANTI_PASSWORD_KUAT
```

Tambahkan juga di `/etc/php/<versi>/fpm/conf.d/99-chetaks.ini`:

```ini
display_errors = Off
upload_max_filesize = 10M
post_max_size = 12M
date.timezone = Asia/Jakarta
session.cookie_httponly = 1
```

### 5.5 Nginx

`/etc/nginx/sites-available/echetaks`:

```nginx
server {
    listen 80;
    server_name chetaks.example.com;
    root /var/www/echetaks;
    index index.php index.html;
    client_max_body_size 12m;

    # Urutan penting: aturan deny harus SEBELUM blok .php

    # Jangan pernah eksekusi PHP dari folder upload
    location ~* ^/(img/product|desain|buktibayar)/.*\.(php|phtml|phar|php[0-9])$ { return 403; }

    # Jangan sajikan file non-aplikasi
    location ~ /\.(?!well-known) { deny all; }
    location ~* \.(sql|md|txt|yml|yaml|env|ini|log|bak)$ { deny all; }
    location ^~ /admin/build/ { deny all; }
    location ^~ /admin/docs/  { deny all; }

    location / { try_files $uri $uri/ =404; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php-fpm.sock;   # sesuaikan: php8.3-fpm.sock / php8.1-fpm.sock
    }
}
```

```bash
ls /run/php/                              # lihat nama socket yang sebenarnya, sesuaikan fastcgi_pass
sudo ln -s /etc/nginx/sites-available/echetaks /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo systemctl restart php*-fpm
```

### 5.6 HTTPS

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d chetaks.example.com
sudo ufw allow 'Nginx Full' && sudo ufw allow OpenSSH && sudo ufw --force enable
```

Lalu lanjut ke [langkah 3](#3-ganti-password-admin-wajib) dan [Bagian 6](#6-checklist-verifikasi). Update kode: `cd /var/www/echetaks && sudo git pull` (tidak ada build step).

---

## 6. Checklist verifikasi

Ganti `BASE` sesuai jalur: Docker lokal di VPS `http://127.0.0.1:8080`, atau `https://chetaks.example.com`.

```bash
BASE=http://127.0.0.1:8080
# 1. Halaman publik -> semua 200
for p in index.php product.php pembayaran.php contact.php about.php "order.php?id=1" "invoice.php?id=21221864" admin/login.php admin/captcha.php; do
  printf '%-32s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' "$BASE/$p")"; done

# 2. Data dari DB tampil (harus mencetak Mug & Sablon Jaket Custom)
curl -s $BASE/product.php | grep -o 'Sablon Jaket Custom\|Mug' | sort -u

# 3. Tidak ada error PHP di output (harus 0)
curl -s $BASE/product.php | grep -ci 'warning\|fatal\|Koneksi atau query bermasalah'

# 4. Proteksi: semua harus 403 atau 404
for p in chetaks.sql DEPLOY.md .env img/ desain/ buktibayar/; do
  printf '%-16s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' "$BASE/$p")"; done

# 5. Aksi admin tanpa login harus 302 -> login (BUKAN 200)
for p in "admin/terimabayar.php?id_bayar=0&id_pesan=0" "admin/pesananbatal.php?id=0" "function/deleteProduct.php?id=0" admin/index.php; do
  printf '%-48s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' "$BASE/$p")"; done
```

**Checklist manual di browser (manusia):**

| # | Uji | Hasil yang benar |
|---|---|---|
| 1 | Buka `/` | Beranda kuning tampil, gambar muncul |
| 2 | `/product.php` → klik **Pesan** → isi form + upload gambar | Alert "berhasil memesan produk", masuk ke halaman **Invoice** |
| 3 | `/pembayaran.php` → isi nomor order + upload bukti | Alert "Pembayaran anda sedang diproses" |
| 4 | `/admin/login.php` dengan password **baru** + captcha | Masuk dashboard; password lama ditolak |
| 5 | Admin → Daftar Pembayaran → **Terima Pembayaran** | Status pembayaran "Diterima", pesanan "Sedang Diproses" |
| 6 | Admin → Daftar Produk → tambah produk + gambar | Produk muncul di `/product.php` |

Jika langkah 2/3/6 gagal dengan "gagal ..." → cek [Troubleshooting](#troubleshooting).

### Troubleshooting

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| Halaman menampilkan `Koneksi atau query bermasalah: ... Access denied` | `DB_USER/DB_PASS` tidak cocok | Docker: samakan `.env` dengan yang dipakai saat **pertama kali** DB dibuat (password hanya dipakai saat init volume). Native: cek `env[...]` di pool FPM lalu `systemctl restart php*-fpm` |
| `... Connection refused` / `getaddrinfo failed` | `DB_HOST` salah | Docker: `DB_HOST=db` (diset otomatis oleh compose). Native: `127.0.0.1` |
| Captcha berupa gambar rusak | Ekstensi `gd` / FreeType tidak ada | `php -m \| grep gd`; native: `apt install php-gd`. Cek juga `admin/images/captcha.jpg` & `admin/code.otf` ada |
| Login selalu "captcha salah" | Session tidak tersimpan | Pastikan folder session PHP writable (`session.save_path`) dan cookie tidak diblok proxy |
| "gagal memesan produk" / "gagal menambah produk" | Folder upload tidak writable, atau DB lama belum dimigrasi | `chown www-data` pada 3 folder upload; jalankan SQL migrasi di Bagian 4 |
| Upload gambar besar gagal | Batas ukuran | Naikkan `upload_max_filesize`/`post_max_size` dan `client_max_body_size` (Nginx) / `max_size` (Caddy) |
| Tampilan tanpa font/ikon tertentu | Aset dimuat dari CDN (Google Fonts, dll.) | Butuh akses internet dari browser pengunjung; bukan masalah server |
| `docker compose up` lalu DB tidak berisi data | Volume `db_data` sudah ada sebelum `chetaks.sql` di-mount | Import manual: `docker compose exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" chetaks' < chetaks.sql` |
| Port 8080 sudah dipakai | Bentrok | Ubah `APP_PORT` di `.env`, lalu `docker compose up -d` |

---

## 7. Keamanan yang perlu kamu ketahui

Sudah ditangani di repo ini:
- Aksi admin (`terimabayar`, `tolakbayar`, `pesananbatal`, `pesananselesai`, `deleteProduct`) sekarang menolak pengguna yang belum login.
- Docker/Nginx memblokir eksekusi PHP di folder upload dan menyembunyikan `chetaks.sql`, `*.md`, `.env`.
- Kredensial DB tidak lagi di-hardcode untuk deploy (env variable).

**Belum diperbaiki — risiko nyata di server publik:**

| Risiko | Lokasi | Dampak |
|---|---|---|
| SQL injection (input disisipkan ke string SQL) | `order.php`, `invoice.php`, `admin/editproduk.php`, `function/deleteProduct.php`, `pemesanan()` di `function/function.php` | Penyerang bisa membaca/mengubah database (termasuk hash admin) |
| Tidak ada CSRF token; aksi admin lewat link GET | seluruh `admin/*` | Admin yang login bisa "dipaksa" menjalankan aksi lewat link berbahaya |
| Upload divalidasi hanya dari ekstensi; nama file asli dipakai & bisa menimpa | `uploaddesain/uploadgambar/uploadpembayaran` di `function.php` | Penimpaan file; (eksekusi PHP sudah diblok di folder upload) |
| Hash password SHA-256 tanpa salt; captcha sederhana | `admin/login.php` | Mudah di-brute-force/crack bila hash bocor |
| Data contoh (rekening, WhatsApp) masih placeholder | `invoice.php`, `contact.php`, `index.php` | Ganti dengan data asli sebelum dipakai |

**Rekomendasi minimum bila situs dibuka publik:** ganti password admin → basic-auth di reverse proxy (atau batasi IP/VPN) → perbaiki SQL injection (ubah ke prepared statement) → baru buka tanpa basic-auth.

---

## 8. Perubahan kode yang dibuat untuk deploy ini

Supaya transparan bagi siapa pun yang membandingkan dengan versi 2020:

| File | Perubahan | Alasan |
|---|---|---|
| `koneksi.php`, `function/function.php` | Koneksi DB membaca env `DB_*` (default tetap `root`/kosong); `function.php` memakai `koneksi.php` alih-alih koneksi duplikat | Bisa deploy tanpa edit kode |
| `function/function.php` | `INSERT` produk & pembayaran tidak lagi mengirim `''` untuk kolom auto-increment | Gagal di MySQL 8 strict mode |
| `chetaks.sql` | `desain_produk`, `bukti_bayar`, `gambar_produk` → `varchar(255)` | Nama file panjang membuat INSERT gagal di strict mode |
| `admin/{terimabayar,tolakbayar,pesananbatal,pesananselesai}.php`, `function/deleteProduct.php` | Cek `$_SESSION['login']` di awal | Sebelumnya bisa dipanggil tanpa login |
| `Dockerfile`, `docker-compose.yml`, `docker/`, `.dockerignore`, `.env.example` | Baru | Deploy Jalur A |
