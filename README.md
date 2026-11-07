# 🚚 GIS Logistik & Sistem Distribusi Produk (Wilayah Indramayu)

Sistem Informasi Geografis (Web GIS) berbasis web terpadu untuk manajemen produk, penugasan armada kurir, pemantauan rute jalan raya secara *real-time*, dan visualisasi analitik pengiriman logistik di wilayah Kabupaten Indramayu dan sekitarnya.

Aplikasi ini dikembangkan untuk memenuhi capaian pelatihan/tugas kelompok yang mencakup:
1. **Dashboard Analytic**
2. **Manajemen Produk (CRUD & Penugasan Kurir)**
3. **Leaflet Map GIS (Informasi Produk, Penentuan Lokasi Interaktif, dan Penyambungan Rute Kurir)**

---

## ✨ Fitur-Fitur Utama

### 1. 📊 Dashboard Analytic (Analitik Logistik & Distribusi)
- **Kartu Metrik Eksekutif (Real-Time):**
  - Total Produk dan Kuantitas Stok keseluruhan.
  - Estimasi Total Nilai Finansial Barang/Logistik (Rp).
  - Status Pengiriman: *Sedang Dikirim*, *Sukses Terkirim*, *Menunggu Kurir*, dan *Diproses*.
  - Status Kesiapan Armada: Rasio kurir yang sedang bertugas (*on-delivery*) vs kurir yang siap berangkat (*available*).
- **Visualisasi Interaktif (Chart.js):**
  - **Doughnut Chart:** Persentase proporsi status pengiriman paket.
  - **Bar Chart:** Distribusi jumlah produk berdasarkan kategori barang.
- **Aktivitas Pengiriman Terbaru:** Riwayat aktivitas 6 pengiriman teranyar lengkap dengan badge status dan tombol langsung `📍 Peta` untuk melacak posisi paket di peta.
- **Monitoring Beban Kurir:** Ringkasan jumlah antaran aktif tiap personil kurir.

---

### 2. 📦 Manajemen Produk (CRUD + Penugasan Kurir)
- **Data Produk Komprehensif:**
  - SKU produk (otomatis/kustom), Nama Produk, Kategori, Harga (Rp), Stok/Qty, Berat estimasi (kg), dan Deskripsi/Catatan.
  - Data Penerima: Nama Lengkap dan Nomor Telepon/WhatsApp.
  - **Penugasan Kurir:** Menugaskan kurir armada langsung ke pesanan barang.
  - **Dua Opsi Penentuan Lokasi Pengantaran:**
    1. **Input Alamat + Pencarian Otomatis:** Ketik alamat dan klik *"Cari Alamat"* (Geocoding otomatis OpenStreetMap Nominatim).
    2. **Interactive Mini-Map GIS Picker:** Klik pada peta mini di form modal untuk menggeser pin lokasi secara langsung; koordinat Latitude, Longitude, dan nama alamat/wilayah otomatis terdeteksi (*Reverse Geocoding*).
- **Operasi CRUD Lengkap:**
  - Tambah Produk Baru (*Create*).
  - Tabel Data Responsif dengan Pencarian Realtime, Filter Kategori, Filter Status, dan Filter Kurir (*Read*).
  - Edit Data Produk, Lokasi, Kurir, dan Status (*Update*).
  - Hapus Produk (*Delete*).
  - **Ekspor Data:** Fitur unduh seluruh data rekap pengiriman ke format file CSV/Excel.

---

### 3. 🗺️ Leaflet Map GIS & Penyambungan Rute Kurir (Routing Machine)
- **Marker Dinamis Berbasis Status Pengiriman:**
  - 🔴 **Depot/Gudang Pusat:** Titik awal keberangkatan distribusi (Depot Indramayu Pusat).
  - 🔵 **Sedang Dikirim:** Paket sedang dalam perjalanan oleh kurir.
  - 🟢 **Sukses Terkirim:** Paket telah diterima oleh penerima.
  - 🟡 **Menunggu Kurir / Diproses:** Paket masih dalam gudang/menunggu penugasan.
- **Informasi Produk Lengkap pada Popup:**
  - SKU, Nama Barang, Kategori, Harga, Nama & Kontak Penerima, Alamat Tujuan, Kurir yang bertugas, serta koordinat Lat/Lng.
  - **Tombol Ubah Status Instan (`✓ Selesai`):** Konfirmasi barang telah terkirim langsung dari popup peta tanpa reload halaman.
- **Penyambungan Rute Kurir (OSRM Road Network Routing):**
  - **Rute Tunggal (Per Produk):** Tombol *"🗺️ Sambung Rute"* menarik rute jalan raya resmi dari Depot Pusat ke alamat tujuan dengan kalkulasi jarak kilometer dan estimasi waktu tempuh.
  - **Rute Multi-Titik (*Multi-Stop Route*):** Tombol *"🛣️ Sambungkan Rute Kurir"* menyambungkan seluruh paket yang ditugaskan ke kurir tertentu secara berantai dari Depot Pusat ke Stop 1, Stop 2, Stop 3, dst., lengkap dengan nomor urutan stop di peta.
- **Fitur Tambah Produk Cepat via Klik Peta Utama:**
  - Klik di sembarang titik pada peta Leaflet utama untuk memunculkan popup *"📍 Titik Lokasi Dipilih"* dan tombol *"+ Buat Produk di Titik Ini"*.
- **Layer Batas Wilayah GeoJSON GADM:**
  - Dukungan visualisasi layer batas administratif GADM Level 0 (Negara), Level 1 (Provinsi), Level 2 (Kabupaten/Kota), Level 3 (Kecamatan), hingga Level 4 (Desa).

---

### 4. 🛵 Manajemen Armada Kurir
- Pengelolaan personil kurir (Nama, Kontak WhatsApp/Telepon, Jenis Kendaraan seperti Motor/Mobil Box/Pickup, Nomor Polisi/Plat Kendaraan, dan Status Ketersediaan).

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 12 (PHP 8.2+) |
| **Database** | MySQL / MariaDB |
| **Database ORM** | Eloquent ORM |
| **Web GIS Engine** | Leaflet.js v1.9.4 |
| **Basemap Layer** | OpenStreetMap (OSM) Tiles |
| **Geocoding & Reverse Geocoding** | OpenStreetMap Nominatim API |
| **Routing / Navigasi Rute Jalan** | OSRM (Open Source Routing Machine) |
| **Data Visualisasi Grafik** | Chart.js |
| **Antarmuka / UI** | HTML5, CSS3, Plus Jakarta Sans, JetBrains Mono, Vanilla JS (SPA Style) |
| **Testing** | PHPUnit / Laravel Feature & Unit Tests |

---

## 📁 Struktur Direktori Penting

```text
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php   # Endpoint API metrik & grafik analitik
│   │   ├── ProductController.php     # Endpoint API CRUD produk & penugasan kurir
│   │   ├── CourierController.php     # Endpoint API armada kurir
│   │   ├── DepotController.php       # Controller data depot/gudang sampel
│   │   └── GisController.php         # Controller halaman peta & GeoJSON GADM
│   └── Models/
│       ├── Product.php               # Model relasi produk & pengiriman
│       ├── Courier.php               # Model kurir logistik
│       └── User.php
├── database/
│   ├── migrations/
│   │   └── *_create_couriers_and_products_tables.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ProductAndCourierSeeder.php # Data sampel 10 produk Indramayu & 5 kurir
├── resources/
│   └── views/
│       └── map.blade.php             # UI Front-End terpadu (SPA Dashboard, CRUD, GIS Map)
├── routes/
│   └── web.php                       # Route web & seluruh endpoint REST API
├── storage/app/public/json/gadm/     # File GeoJSON batas wilayah Indonesia & Indramayu
└── tests/Feature/
    └── DistributionSystemTest.php    # Automated feature test (CRUD, API, Page render)
```

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
- PHP >= 8.2
- Composer
- MySQL / MariaDB (misal via XAMPP atau Laragon)
- Git

### 2. Kloning Repositori
```bash
git clone https://github.com/rizkyaa-dev/Pelatihan_Klmpk2.git
cd Pelatihan_Klmpk2
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan pastikan konfigurasi database sudah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelatihan_web
DB_USERNAME=root
DB_PASSWORD=
```
*(Buat database kosong bernama `pelatihan_web` di MySQL/phpMyAdmin jika belum ada).*

### 4. Install Dependensi PHP
```bash
composer install
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder Database
Jalankan perintah ini untuk membuat tabel `couriers`, `products`, serta mengisi data sampel awal:
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🧪 Menjalankan Pengujian Otomatis (Automated Tests)
Untuk memverifikasi bahwa seluruh endpoint API dan halaman berjalan lancar 100%:
```bash
php artisan test
```

---

## 📡 Daftar Endpoint API

| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/` | Tampilan Web Utama (Dashboard, CRUD, Map, Kurir) |
| `GET` | `/api/analytics` | Mengambil data metrik dan statistik grafik |
| `GET` | `/api/products` | Mengambil daftar produk (mendukung filter & search) |
| `POST` | `/api/products` | Menambah produk baru + koordinat lokasi + kurir |
| `GET` | `/api/products/{id}` | Mengambil detail 1 produk |
| `PUT` | `/api/products/{id}` | Memperbarui data produk & lokasi |
| `PATCH` | `/api/products/{id}/status` | Mengubah status pengiriman secara cepat |
| `DELETE` | `/api/products/{id}` | Menghapus data produk |
| `GET` | `/api/couriers` | Mengambil daftar kurir armada |
| `POST` | `/api/couriers` | Menambahkan data kurir baru |
| `PUT` | `/api/couriers/{id}` | Mengedit data kurir |
| `DELETE` | `/api/couriers/{id}` | Menghapus data kurir |
| `GET` | `/api/depots` | Mengambil data titik depot distribusi |
| `GET` | `/api/geojson/gadm/{level}` | Mengambil data batas wilayah GeoJSON (Lvl 0-4) |

---

## 👥 Pengembang (Kelompok 2)
Proyek ini dikembangkan oleh **Kelompok 2** dalam rangka pelatihan/mata kuliah Sistem Informasi Geografis (GIS) dan Pengembangan Web.
# 🚚 GIS Logistik & Sistem Distribusi Produk (Wilayah Indramayu)

Sistem Informasi Geografis (Web GIS) berbasis web terpadu untuk manajemen produk, penugasan armada kurir, pemantauan rute jalan raya secara *real-time*, dan visualisasi analitik pengiriman logistik di wilayah Kabupaten Indramayu dan sekitarnya.

Aplikasi ini dikembangkan untuk memenuhi capaian pelatihan/tugas kelompok yang mencakup:
1. **Dashboard Analytic**
2. **Manajemen Produk (CRUD & Penugasan Kurir)**
3. **Leaflet Map GIS (Informasi Produk, Penentuan Lokasi Interaktif, dan Penyambungan Rute Kurir)**

---

## ✨ Fitur-Fitur Utama

### 1. 📊 Dashboard Analytic (Analitik Logistik & Distribusi)
- **Kartu Metrik Eksekutif (Real-Time):**
  - Total Produk dan Kuantitas Stok keseluruhan.
  - Estimasi Total Nilai Finansial Barang/Logistik (Rp).
  - Status Pengiriman: *Sedang Dikirim*, *Sukses Terkirim*, *Menunggu Kurir*, dan *Diproses*.
  - Status Kesiapan Armada: Rasio kurir yang sedang bertugas (*on-delivery*) vs kurir yang siap berangkat (*available*).
- **Visualisasi Interaktif (Chart.js):**
  - **Doughnut Chart:** Persentase proporsi status pengiriman paket.
  - **Bar Chart:** Distribusi jumlah produk berdasarkan kategori barang.
- **Aktivitas Pengiriman Terbaru:** Riwayat aktivitas 6 pengiriman teranyar lengkap dengan badge status dan tombol langsung `📍 Peta` untuk melacak posisi paket di peta.
- **Monitoring Beban Kurir:** Ringkasan jumlah antaran aktif tiap personil kurir.

---

### 2. 📦 Manajemen Produk (CRUD + Penugasan Kurir)
- **Data Produk Komprehensif:**
  - SKU produk (otomatis/kustom), Nama Produk, Kategori, Harga (Rp), Stok/Qty, Berat estimasi (kg), dan Deskripsi/Catatan.
  - Data Penerima: Nama Lengkap dan Nomor Telepon/WhatsApp.
  - **Penugasan Kurir:** Menugaskan kurir armada langsung ke pesanan barang.
  - **Dua Opsi Penentuan Lokasi Pengantaran:**
    1. **Input Alamat + Pencarian Otomatis:** Ketik alamat dan klik *"Cari Alamat"* (Geocoding otomatis OpenStreetMap Nominatim).
    2. **Interactive Mini-Map GIS Picker:** Klik pada peta mini di form modal untuk menggeser pin lokasi secara langsung; koordinat Latitude, Longitude, dan nama alamat/wilayah otomatis terdeteksi (*Reverse Geocoding*).
- **Operasi CRUD Lengkap:**
  - Tambah Produk Baru (*Create*).
  - Tabel Data Responsif dengan Pencarian Realtime, Filter Kategori, Filter Status, dan Filter Kurir (*Read*).
  - Edit Data Produk, Lokasi, Kurir, dan Status (*Update*).
  - Hapus Produk (*Delete*).
  - **Ekspor Data:** Fitur unduh seluruh data rekap pengiriman ke format file CSV/Excel.

---

### 3. 🗺️ Leaflet Map GIS & Penyambungan Rute Kurir (Routing Machine)
- **Marker Dinamis Berbasis Status Pengiriman:**
  - 🔴 **Depot/Gudang Pusat:** Titik awal keberangkatan distribusi (Depot Indramayu Pusat).
  - 🔵 **Sedang Dikirim:** Paket sedang dalam perjalanan oleh kurir.
  - 🟢 **Sukses Terkirim:** Paket telah diterima oleh penerima.
  - 🟡 **Menunggu Kurir / Diproses:** Paket masih dalam gudang/menunggu penugasan.
- **Informasi Produk Lengkap pada Popup:**
  - SKU, Nama Barang, Kategori, Harga, Nama & Kontak Penerima, Alamat Tujuan, Kurir yang bertugas, serta koordinat Lat/Lng.
  - **Tombol Ubah Status Instan (`✓ Selesai`):** Konfirmasi barang telah terkirim langsung dari popup peta tanpa reload halaman.
- **Penyambungan Rute Kurir (OSRM Road Network Routing):**
  - **Rute Tunggal (Per Produk):** Tombol *"🗺️ Sambung Rute"* menarik rute jalan raya resmi dari Depot Pusat ke alamat tujuan dengan kalkulasi jarak kilometer dan estimasi waktu tempuh.
  - **Rute Multi-Titik (*Multi-Stop Route*):** Tombol *"🛣️ Sambungkan Rute Kurir"* menyambungkan seluruh paket yang ditugaskan ke kurir tertentu secara berantai dari Depot Pusat ke Stop 1, Stop 2, Stop 3, dst., lengkap dengan nomor urutan stop di peta.
- **Fitur Tambah Produk Cepat via Klik Peta Utama:**
  - Klik di sembarang titik pada peta Leaflet utama untuk memunculkan popup *"📍 Titik Lokasi Dipilih"* dan tombol *"+ Buat Produk di Titik Ini"*.
- **Layer Batas Wilayah GeoJSON GADM:**
  - Dukungan visualisasi layer batas administratif GADM Level 0 (Negara), Level 1 (Provinsi), Level 2 (Kabupaten/Kota), Level 3 (Kecamatan), hingga Level 4 (Desa).

---

### 4. 🛵 Manajemen Armada Kurir
- Pengelolaan personil kurir (Nama, Kontak WhatsApp/Telepon, Jenis Kendaraan seperti Motor/Mobil Box/Pickup, Nomor Polisi/Plat Kendaraan, dan Status Ketersediaan).

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 12 (PHP 8.2+) |
| **Database** | MySQL / MariaDB |
| **Database ORM** | Eloquent ORM |
| **Web GIS Engine** | Leaflet.js v1.9.4 |
| **Basemap Layer** | OpenStreetMap (OSM) Tiles |
| **Geocoding & Reverse Geocoding** | OpenStreetMap Nominatim API |
| **Routing / Navigasi Rute Jalan** | OSRM (Open Source Routing Machine) |
| **Data Visualisasi Grafik** | Chart.js |
| **Antarmuka / UI** | HTML5, CSS3, Plus Jakarta Sans, JetBrains Mono, Vanilla JS (SPA Style) |
| **Testing** | PHPUnit / Laravel Feature & Unit Tests |

---

## 📁 Struktur Direktori Penting

```text
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php   # Endpoint API metrik & grafik analitik
│   │   ├── ProductController.php     # Endpoint API CRUD produk & penugasan kurir
│   │   ├── CourierController.php     # Endpoint API armada kurir
│   │   ├── DepotController.php       # Controller data depot/gudang sampel
│   │   └── GisController.php         # Controller halaman peta & GeoJSON GADM
│   └── Models/
│       ├── Product.php               # Model relasi produk & pengiriman
│       ├── Courier.php               # Model kurir logistik
│       └── User.php
├── database/
│   ├── migrations/
│   │   └── *_create_couriers_and_products_tables.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ProductAndCourierSeeder.php # Data sampel 10 produk Indramayu & 5 kurir
├── resources/
│   └── views/
│       └── map.blade.php             # UI Front-End terpadu (SPA Dashboard, CRUD, GIS Map)
├── routes/
│   └── web.php                       # Route web & seluruh endpoint REST API
├── storage/app/public/json/gadm/     # File GeoJSON batas wilayah Indonesia & Indramayu
└── tests/Feature/
    └── DistributionSystemTest.php    # Automated feature test (CRUD, API, Page render)
```

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
- PHP >= 8.2
- Composer
- MySQL / MariaDB (misal via XAMPP atau Laragon)
- Git

### 2. Kloning Repositori
```bash
git clone https://github.com/rizkyaa-dev/Pelatihan_Klmpk2.git
cd Pelatihan_Klmpk2
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan pastikan konfigurasi database sudah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelatihan_web
DB_USERNAME=root
DB_PASSWORD=
```
*(Buat database kosong bernama `pelatihan_web` di MySQL/phpMyAdmin jika belum ada).*

### 4. Install Dependensi PHP
```bash
composer install
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder Database
Jalankan perintah ini untuk membuat tabel `couriers`, `products`, serta mengisi data sampel awal:
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🧪 Menjalankan Pengujian Otomatis (Automated Tests)
Untuk memverifikasi bahwa seluruh endpoint API dan halaman berjalan lancar 100%:
```bash
php artisan test
```

---

## 📡 Daftar Endpoint API

| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/` | Tampilan Web Utama (Dashboard, CRUD, Map, Kurir) |
| `GET` | `/api/analytics` | Mengambil data metrik dan statistik grafik |
| `GET` | `/api/products` | Mengambil daftar produk (mendukung filter & search) |
| `POST` | `/api/products` | Menambah produk baru + koordinat lokasi + kurir |
| `GET` | `/api/products/{id}` | Mengambil detail 1 produk |
| `PUT` | `/api/products/{id}` | Memperbarui data produk & lokasi |
| `PATCH` | `/api/products/{id}/status` | Mengubah status pengiriman secara cepat |
| `DELETE` | `/api/products/{id}` | Menghapus data produk |
| `GET` | `/api/couriers` | Mengambil daftar kurir armada |
| `POST` | `/api/couriers` | Menambahkan data kurir baru |
| `PUT` | `/api/couriers/{id}` | Mengedit data kurir |
| `DELETE` | `/api/couriers/{id}` | Menghapus data kurir |
| `GET` | `/api/depots` | Mengambil data titik depot distribusi |
| `GET` | `/api/geojson/gadm/{level}` | Mengambil data batas wilayah GeoJSON (Lvl 0-4) |

---

## 👥 Pengembang (Kelompok 2)
Proyek ini dikembangkan oleh **Kelompok 2** dalam rangka pelatihan/mata kuliah Sistem Informasi Geografis (GIS) dan Pengembangan Web.
