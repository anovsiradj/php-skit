# Tasks
- [x] Task 1: Inventarisasi file & modul
  - [x] Kumpulkan daftar file/folder utama (src, bin, tests, dan file root) untuk kebutuhan indeks README
  - [x] Kelompokkan file berdasarkan modul (helpers, functs, files, spreadsheet, dll.)

- [x] Task 2: Standar dokumentasi tingkat-file untuk semua PHP
  - [x] Tetapkan template PHPDoc header yang ringkas dan konsisten (tujuan, cara pakai, dependency, catatan standalone)
  - [x] Terapkan template ke seluruh file `*.php` di `src/`, `bin/`, dan `tests/` tanpa mengubah gaya indentasi yang sudah ada

- [x] Task 3: Indeks dokumentasi di README
  - [x] Tambahkan section “File/Module Index” di `README.md` yang menjelaskan tujuan file/folder penting (termasuk file non-PHP seperti composer.json, composer.lock, workspace/project files)
  - [x] Tambahkan catatan “standalone usage” (cara memakai per-file vs via Composer)

- [x] Task 4: Kebijakan dependency & modul opsional
  - [x] Perjelas dependency opsional di `composer.json` (gunakan `suggest` untuk package yang bermanfaat namun tidak wajib)
  - [x] Pastikan modul yang membutuhkan dependency eksternal memiliki guard + pesan error yang jelas ketika dependency tidak tersedia
  - [x] Dokumentasikan dependency tersebut di PHPDoc file terkait dan di README

- [x] Task 5: Validasi dasar
  - [x] Jalankan linting syntax PHP untuk semua file yang berubah
  - [x] Jalankan minimal satu contoh script di `tests/` yang relevan untuk memastikan tidak ada regresi fatal

# Task Dependencies
- Task 2 bergantung pada Task 1 (butuh inventaris file agar cakupan penerapan jelas)
- Task 3 bergantung pada Task 1
- Task 4 dapat dikerjakan paralel dengan Task 2, setelah template dokumentasi disepakati
- Task 5 bergantung pada Task 2–4
