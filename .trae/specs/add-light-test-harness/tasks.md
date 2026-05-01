# Tasks
- [x] Task 1: Define test conventions and mapping
  - [x] Finalize test file discovery pattern (e.g., `tests/**/*Test.php`)
  - [x] Define “one src file → one test file” mapping and naming rules
  - [x] Define test header convention so each test also acts as how-to/usage/example

- [x] Task 2: Implement the lightweight test harness (pure PHP)
  - [x] Add `tests/run.php` CLI runner (discover, execute, summarize, exit codes)
  - [x] Add `tests/bootstrap.php` (autoload + shared helpers)
  - [x] Add `tests/assert.php` (minimal assertions + skip helper)
  - [x] Add basic CLI flags: `--list`, `--filter <pattern>`, `--verbose`

- [x] Task 3: Wire test command for automation
  - [x] Add `composer.json` script entry (e.g., `composer test` → `php tests/run.php`)
  - [x] Document how to run tests in `README.md`

- [x] Task 4: Add complete tests for every `src/**` file
  - [x] Add unit tests for class files (`src/*.php`, `src/helpers/*.php`, `src/spreadsheet/*.php`)
  - [x] Add tests for function/snippet files (`src/files/*.php`, `src/functs/*.php`)
  - [x] Ensure dependency-aware behavior for optional modules (spreadsheet, ext-curl, ext-gd, ext-intl)
  - [x] Ensure every test includes minimal usage examples in its header and/or in the test body (clear, copyable snippets)

- [x] Task 5: Validate test suite
  - [x] Run `php -l` on the harness + all new tests
  - [x] Run `php tests/run.php` and ensure non-zero exit code on a forced failing assertion
  - [x] Run `composer test` and confirm output summary is readable

# Task Dependencies
- Task 2 depends on Task 1
- Task 3 depends on Task 2
- Task 4 depends on Task 2 (and uses conventions from Task 1)
- Task 5 depends on Task 2–4
