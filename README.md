
# `php-skit`

SKit (SimpleKit) for KISS (Keep It Simple, Stupid!)

### motivation

- DRY (Don't Repeat Yourself)
- my very own curated and commonly used `tools` when code in PHP
- i dont want to make framework
- i am too lazy

### purpose

- standalone
- baseline php >= 8.0 (polyfill-php83 dipakai agar `str_increment`/`str_decrement` tetap jalan di 8.0-8.2)

### file/module index

root
- README.md: project intro + index
- composer.json: package metadata + PSR-4 autoload (anovsiradj\skit\ => src/)
- .gitignore: git ignore rules

src/ (library code)
- App.php: base app abstraction + config/env loader
- CURL.php: small OO wrapper around cURL (headers, post types, stdout/stderr capture, fileMime)
- Funct.php: function-loader abstraction for src/functs
- files/
  - arguments.php: standalone snippet to parse CLI args into array (returns $arguments)
- functs/ (global functions; not PSR-4 classes)
  - error.php: default error handler function for Funct
  - ico.php: imageico() + CLI script logic (GD required)
- helpers/: Archive, Array, Backup, Csv, Date, Excel, ExecTime, File, FileSize, Helper(dump), Html(Spatie elements), Http, Image, Intl, Letter, MenuBuilder, Number, Roman, Signature, SpreadsheetStyle, String, Time, Tree, Url, Youtube
- spreadsheet/ (PhpSpreadsheet helpers; optional module)
  - FacadeHelper.php: reader/writer facade + output helpers
  - SpreadHelper.php: sheet helper utilities (e.g., list sheet names, bulk cell transforms)
  - StyleHelper.php: styling helper utilities
- tests/: Runner + Assert, lightweight harness reused by sibling packages (laravel-skit, yii2-skit, php-gis)

bin/ (CLI entrypoints)
- cli-connect.php, cli-inspect.php: DB connect/inspect via PDO (sqlite butuh ext-pdo_sqlite)

tests/
- run.php + init.php + src/**/*Test.php: test-as-howto (lihat bagian tests & howto)
- curl/, spreadsheet/: manual examples requiring local server / phpoffice


### standalone usage

via composer (recommended)
- install: composer require anovsiradj/php-skit
- usage: require vendor/autoload.php then use classes from src/ (PSR-4)

per-file (copy/require)
- files like src/files/arguments.php are usable as standalone include (it returns an array)
- function files in src/functs/*.php can be required directly to expose global functions
- src/functs/ico.php can also be executed as CLI script, but it requires the GD extension

### optional dependencies & polyfills

- spreadsheet module: composer require phpoffice/phpspreadsheet
  - note: src/spreadsheet/* will throw a clear RuntimeException if PhpSpreadsheet is not installed
- examples: fakerphp/faker and symfony/var-dumper are only needed for tests/examples
- symfony/polyfill-php83: dependency produksi (memudahkan `str_increment`/`str_decrement` dipakai; bawaan PHP >= 8.3)

### tests & howto

Tes dibuat seringan mungkin menggunakan script murni (tanpa PHPUnit/Pest). Tes juga dirancang sebagai **HowTo / Example** sehingga developer bisa langsung membaca source code tes untuk melihat contoh pemakaian.

```bash
# menjalankan seluruh test
composer test

# melihat list file test
php tests/run.php --list

# filter test
php tests/run.php --filter "LetterHelper"
```

# Lainnya

Created By `anovsiradj`, 
Logo Powered By `copilot.microsoft.com`.
