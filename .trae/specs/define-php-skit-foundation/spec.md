# PHP Skit Foundation Spec

## Why
Paket ini adalah koleksi script dan helper yang ingin tetap ringan, mudah dicopy per-file, dan memaksimalkan fitur bawaan PHP tanpa ketergantungan eksternal yang tidak perlu.

## What Changes
- Menetapkan standar dokumentasi tingkat-file untuk seluruh file PHP agar fungsi dan tujuan setiap file jelas.
- Menambahkan indeks dokumentasi agar seluruh file (termasuk non-PHP) memiliki penjelasan tujuan di README.
- Menetapkan kebijakan dependency: default hanya PHP, dependency eksternal bersifat opsional dan dijelaskan manfaatnya.
- Menetapkan prinsip “standalone-friendly” agar file bisa dipakai per-file (via require) dengan syarat dependency terpenuhi.
- Tidak ada perubahan perilaku yang disengaja; perubahan fokus pada dokumentasi, konsistensi, dan guard opsional dependency.

## Impact
- Affected specs: dokumentasi file, kebijakan dependency, pola pemakaian standalone, kesiapan copas per-file.
- Affected code: seluruh `*.php` di `src/`, `bin/`, dan `tests/`; `README.md`; `composer.json` (bagian metadata dependency opsional).

## ADDED Requirements
### Requirement: Dokumentasi File PHP
Sistem SHALL menambahkan penjelasan fungsi dan tujuan pada setiap file PHP di repository ini (minimal di `src/`, `bin/`, dan `tests/`) dalam bentuk PHPDoc di bagian atas file.

Penjelasan tingkat-file SHALL mencakup minimal:
- Ringkasan 1–3 kalimat tentang fungsi file
- Target pengguna (mis. “helper date/time”, “fungsi error”, “contoh test”)
- Cara pakai minimal (contoh singkat)
- Dependency khusus (ekstensi PHP atau library Composer jika ada)
- Catatan standalone (bagaimana dipakai jika file dicopy dan di-`require`)

#### Scenario: Success case
- **WHEN** developer membuka file PHP apa pun di `src/`, `bin/`, atau `tests/`
- **THEN** developer dapat memahami tujuan file dan cara pakai dasarnya hanya dari PHPDoc header file tersebut

### Requirement: Indeks Penjelasan Semua File
Sistem SHALL menyediakan indeks di `README.md` yang menjelaskan fungsi/tujuan untuk file non-PHP dan folder utama, sehingga “setiap file” di package punya rujukan penjelasan.

#### Scenario: Success case
- **WHEN** developer membuka `README.md`
- **THEN** tersedia daftar file/folder penting beserta tujuan singkatnya dan catatan dependency yang relevan

### Requirement: Kebijakan Dependency Minimal & Opsional
Sistem SHALL meminimalkan dependency production pada package lain.

Aturan:
- `composer.json` bagian `require` SHALL tetap berisi minimal `php` saja, kecuali ada dependency yang sangat bermanfaat dan benar-benar dibutuhkan untuk fitur inti.
- Fitur yang bergantung pada library eksternal SHALL bersifat opsional: terisolasi, terdokumentasi, dan memiliki pesan error yang jelas ketika dependency tidak tersedia.
- Dependency opsional SHOULD dicantumkan di `composer.json` bagian `suggest` beserta alasan singkatnya.

#### Scenario: Success case
- **WHEN** developer memakai helper yang tidak membutuhkan dependency eksternal
- **THEN** helper bekerja tanpa perlu install package lain
- **WHEN** developer memakai helper yang membutuhkan dependency opsional
- **THEN** sistem memberi arahan dependency yang dibutuhkan (mis. via exception/message yang jelas)

### Requirement: Standalone-Friendly Copy Per-File
Sistem SHALL menjaga agar file dapat digunakan secara per-file (dicopy ke project lain lalu di-`require`) selama dependency yang dibutuhkan tersedia.

Batasan:
- File PHP tidak SHALL memiliki side-effect yang mengejutkan saat di-include (mis. eksekusi I/O) kecuali memang file tersebut adalah “script entry” yang jelas (contoh di `bin/` atau `tests/`).
- Setiap file SHALL mendokumentasikan cara pakai standalone (minimal contoh `require_once` + pemanggilan API).

#### Scenario: Success case
- **WHEN** developer menyalin satu file helper ke project lain dan melakukan `require_once`
- **THEN** developer dapat menggunakan API di file tersebut dengan petunjuk yang ada pada dokumentasi file

## MODIFIED Requirements
### Requirement: Purpose Paket
Paket ini tetap bertujuan sebagai kumpulan script/helper yang:
- Memaksimalkan fitur bawaan PHP (SPL, fungsi standar, typing modern PHP 7.4+).
- Meminimalkan dependency eksternal.
- Bisa dipakai sebagai library via Composer, namun juga “standalone-friendly” untuk penggunaan per-file.

## REMOVED Requirements
Tidak ada.
