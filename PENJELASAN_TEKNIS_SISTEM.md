# PENJELASAN TEKNIS SISTEM

## 1. Gambaran Umum
TernakMurai adalah aplikasi manajemen breeding Murai Batu berbasis Laravel. Sistem ini dibangun dengan arsitektur MVC dan memisahkan logika bisnis ke dalam layanan (`app/Services/`). Aplikasi menargetkan peran `peternak` untuk mengelola data indukan, kandang, anakan, keuangan, dan analisa breeding.

## 2. Struktur Arsitektur
- Framework: Laravel
- Folder penting:
  - `app/Http/Controllers/Peternak/`: controller untuk alur peternak
  - `app/Services/`: logika bisnis reusable
  - `app/Models/`: model Eloquent dan relasi database
  - `resources/views/`: tampilan Blade
  - `routes/web.php`: routing aplikasi
- Pola utama: controller tipis + service tebal, validasi menggunakan `FormRequest`, tampilan menggunakan Blade.

## 3. Routing dan Middleware
Route peternak diisolasi di `routes/web.php` dengan middleware `auth` dan `peternak`:
- Prefix URL: `/peternak`
- Nama route: `peternak.*`
- Modul utama:
  - Dashboard: `peternak.dashboard`
  - Profile: `peternak.profile.show` / `peternak.profile.update`
  - Indukan: `peternak.indukan.*`
  - Kandang: `peternak.kandang.*`
  - Anakan: `peternak.anakan.*`
  - Analisa Breeding: `peternak.analisaBreeding.*`
  - Keuangan: `peternak.keuangan.*`

## 4. Domain Peternak
### 4.1 Indukan
- Controller: `App\Http\Controllers\Peternak\IndukanController`
- Service: `App\Services\IndukanService`
- Fungsionalitas:
  - CRUD data indukan
  - Upload foto indukan
  - Hitung performa global dan relasi breeding
- Validasi tanggal lahir: `tanggal_lahir` boleh nullable, tetapi jika ada harus `date|before_or_equal:today`.

### 4.2 Kandang
- Controller: `App\Http\Controllers\Peternak\KandangController`
- Service: `App\Services\KandangService`
- Fungsionalitas:
  - CRUD kandang
  - kelola status kandang: `kosong`, `bertelur`, `mengeram`, `menetas`
  - proses kegagalan breeding (`formGagal` / `storeGagal`)
  - tambah anakan saat menetas (`createAnak`, `storeAnakan`)
- Data kegagalan trip disimpan di tabel `perkawinan`.

### 4.3 Anakan
- Controller: `App\Http\Controllers\Peternak\AnakanController`
- Service: `App\Services\AnakanService`
- Fungsionalitas:
  - daftar, tambah, detail, update, status, jual, hapus anakan
  - dukungan sumber anakan internal (`peternakan`) dan eksternal (`luar`)
  - input anakan internal dapat membuat `Perkawinan` otomatis dari `Kandang`
- Validasi tanggal lahir anakan internal/eksternal:
  - `tanggal_lahir` wajib, `date`, `before_or_equal:today`

### 4.4 Analisa Breeding
- Controller: `App\Http\Controllers\Peternak\AnalisaBreedingController`
- Modul analisa terdiri dari:
  - form pemilihan jantan dan betina
  - proses analisa dengan AI OpenAI
  - tampilan hasil, simpan riwayat, detail, update catatan, dan hapus
- Sumber data analisa:
  - riwayat `Perkawinan` pasangan
  - anakan dari pasangan pasangan ini dan pasangan lain
  - relasi `anakansSebagaiJantan`, `anakansSebagaiBetina`
  - status perilaku jantan/betina
  - riwayat keputusan tindak lanjut peternak
- Rule-based rekomendasi sistem: `tentukanRekomendasiSistem()`
  - `stop` jika 3 gagal beruntun atau tren buruk
  - `uji_coba` jika data trip kurang dari 3 atau fluktuatif
  - `lanjut` jika success rate stabil tinggi

## 5. Model dan Relasi Data
### Indukan (`app/Models/Indukan.php`)
- `belongsTo(Peternak)`
- `hasMany(Kandang, 'indukan_jantan_id')`
- `hasMany(Kandang, 'indukan_betina_id')`
- `hasMany(Perkawinan, 'indukan_jantan_id')`
- `hasMany(Perkawinan, 'indukan_betina_id')`
- `hasManyThrough(Anakan, Perkawinan, 'indukan_jantan_id')` untuk anakan dari peran jantan
- `hasManyThrough(Anakan, Perkawinan, 'indukan_betina_id')` untuk anakan dari peran betina
- Attribute helper: `age`, `foto_url`, `isJantan()`, `isBetina()`

### Perkawinan (`app/Models/Perkawinan.php`)
- `belongsTo(Kandang)`
- `belongsTo(Indukan, 'indukan_jantan_id')`
- `belongsTo(Indukan, 'indukan_betina_id')`
- `hasMany(Anakan)`
- Attr `tanggal_kawin` dicast ke `date`
- Generator nomor trip (pasangan dan kandang)

### Anakan (`app/Models/Anakan.php`)
- `belongsTo(Peternak)`
- `belongsTo(Kandang)`
- `belongsTo(Perkawinan)`
- `belongsTo(Indukan, 'indukan_jantan_id')`
- `belongsTo(Indukan, 'indukan_betina_id')`

## 6. Validasi dan Proteksi Data
- Semua form `peternak` menggunakan middleware `auth` + `peternak`.
- Controller memeriksa kepemilikan resource untuk mencegah akses data lintas akun.
- Validation rules di `FormRequest` memastikan input sesuai domain.
- Contoh validasi tanggal:
  - `StoreIndukanRequest`: `tanggal_lahir` `nullable|date|before_or_equal:today`
  - `UpdateIndukanRequest`: `tanggal_lahir` `nullable|date|before_or_equal:today`
  - `StoreAnakanDariDalamKandangRequest` dan `StoreAnakanDariLuarRequest`: `tanggal_lahir` `required|date|before_or_equal:today`
  - `storeGagal` pada `KandangController`: `tanggal_gagal` wajib `date`

## 7. Penanganan Tanggal dan Datepicker
### Custom Datepicker
- Flatpickr digunakan untuk mengatasi keterbatasan native browser `type="date"`.
- Implementasi ada di `resources/views/components/layout.blade.php`.
- Semua input tanggal dengan kelas `custom-datepicker` diinisialisasi dengan:
  - `dateFormat: 'Y-m-d'` (format yang disimpan backend)
  - `altInput: true`
  - `altFormat: 'd/m/Y'` (format tampilan lokal)
  - `maxDate: 'today'` (mencegah tanggal masa depan)
  - `disableMobile: true` (memaksa Flatpickr di mobile)
- `normalizeValue()` mengkonversi nilai awal `dd/mm/yyyy` ke `yyyy-mm-dd` jika diperlukan.

### Lokasi implementasi datepicker
- `resources/views/peternak/indukan/create.blade.php`
- `resources/views/peternak/indukan/edit.blade.php`
- `resources/views/peternak/kandang/createAnak.blade.php`
- `resources/views/peternak/kandang/formGagal.blade.php`
- `resources/views/peternak/anakan/create.blade.php`

### Catatan penting
- Backend tetap menerima nilai tanggal standar `YYYY-MM-DD` agar kompatibel dengan cast Eloquent.
- Display internal ke pengguna menggunakan `dd/mm/yyyy`.
- Validasi Laravel `before_or_equal:today` memberikan lapisan keamanan kedua terhadap input tanggal masa depan.

## 8. Analisa AI & Integrasi OpenAI
- Aplikasi memanggil OpenAI melalui `OpenAI::client(env('OPENAI_API_KEY'))`.
- Model default adalah `env('OPENAI_MODEL', 'gpt-4o-mini')`.
- Prompt dibangun dalam `AnalisaBreedingController::generatePrompt()` dengan:
  - data jantan/betina
  - ringkasan trip breeding
  - karakteristik anakan
  - fase produksi indukan
  - status perilaku
  - riwayat tindak lanjut peternak
- Jika API gagal atau rate limit, ada fallback ke `fallbackAnalisa()`.

## 9. Kelebihan dan Observasi Implementasi
- Memisahkan business logic ke service mempermudah pengujian dan pemeliharaan.
- `AnalisaBreedingController` menggabungkan rule-based dan AI-assisted insight.
- Validasi tanggal sudah baik pada backend, sehingga mencegah input tanggal ke depan.
- Flatpickr dijadikan global di layout, sehingga form tanggal konsisten.
- `KandangController::storeGagal()` mencatat `tanggal_gagal` dan `status='gagal'` pada tabel `perkawinan`, lalu mengosongkan kandang.

## 10. Rekomendasi Perbaikan
- Pastikan semua input tanggal penting memakai kelas `custom-datepicker` bila ingin tampilkan `dd/mm/yyyy` secara seragam.
- Jika ingin memperluas validasi UI, tambahkan `altInputClass` dan `dateFormat` pada Flatpickr agar styling tetap seragam.
- Pertimbangkan membuat helper Blade atau komponen `@datePicker` agar penggunaan lebih konsisten.
- Review penggunaan `generateNomorTripPasangan()` karena hasilnya bertipe `int` sementara `nomor_trip` umumnya string.

---

Dokumen ini ditulis berdasarkan struktur kode saat ini dan file yang ada di `app/`, `routes/`, `resources/views/`, dan `app/Services/`.