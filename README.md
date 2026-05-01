
# `php-skit`

SKit (SimpleKit) for KISS (Keep It Simple, Stupid!)

### motivation

- DRY (Don't Repeat Yourself)
- my very own curated and commonly used `tools` when code in PHP
- i dont want to make framework
- i am too lazy

### purpose

- standalone
- LTS (Long-Term Support), php7 and beyond.
- ... maybe in the future, php8 and beyond.
- ... i was thinking about php5, but nah.

### file/module index

root
- README.md: project intro + index
- composer.json: package metadata + PSR-4 autoload (anovsiradj\skit\ => src/)
- composer.lock: locked dependency versions (for development)
- .gitignore: git ignore rules
- php-skit.code-workspace: VS Code workspace
- php-skit.sublime-project: Sublime Text project
- .trae/specs/define-php-skit-foundation/: internal specs/checklist/tasks

src/ (library code)
- App.php: base app abstraction (currently placeholder)
- CURL.php: small OO wrapper around cURL (headers, post types, stdout/stderr capture)
- Funct.php: function-loader abstraction for src/functs (currently placeholder/stub)
- files/
  - arguments.php: standalone snippet to parse CLI args into array (returns $arguments)
- functs/ (global functions; not PSR-4 classes)
  - error.php: default error handler function for Funct
  - ico.php: imageico() + CLI script logic (GD required)
- helpers/ (small reusable helpers)
  - DateHelper.php, TimeHelper.php, IntlHelper.php, NumberHelper.php, RomanHelper.php, LetterHelper.php
- spreadsheet/ (PhpSpreadsheet helpers; optional module)
  - FacadeHelper.php: reader/writer facade + output helpers
  - SpreadHelper.php: sheet helper utilities (e.g., list sheet names, bulk cell transforms)
  - StyleHelper.php: styling helper utilities

bin/ (CLI entrypoints)
- ico.php: placeholder (empty for now)

tests/ (example scripts)
- init.php: require Composer autoload
- curl/: local HTTP client/server examples for CURL.php (requires local server)
- spreadsheet/: examples for spreadsheet helpers (requires phpoffice/phpspreadsheet + fakerphp/faker)


### standalone usage

via composer (recommended)
- install: composer require anovsiradj/skit
- usage: require vendor/autoload.php then use classes from src/ (PSR-4)

per-file (copy/require)
- files like src/files/arguments.php are usable as standalone include (it returns an array)
- function files in src/functs/*.php can be required directly to expose global functions
- src/functs/ico.php can also be executed as CLI script, but it requires the GD extension

### optional dependencies & polyfills

- spreadsheet module: composer require phpoffice/phpspreadsheet
  - note: src/spreadsheet/* will throw a clear RuntimeException if PhpSpreadsheet is not installed
- examples: fakerphp/faker and symfony/var-dumper are only needed for tests/examples
- symfony/polyfill-php83: digunakan secara internal (mis. untuk `str_increment`), memungkinkan kita menggunakan fungsi PHP baru di environment PHP 7.4.

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
