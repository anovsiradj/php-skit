# Light Test Harness Spec (PHP 7.4)

## Why
This package targets PHP 7.4 with minimal dependencies, but still needs reliable, automatable tests for every feature/file without pulling a full testing framework.

## What Changes
- Add a lightweight, frameworkless test harness (pure PHP) that can run from CLI and return proper exit codes.
- Define a “one source file → one test file” convention for `src/**` (classes, helpers, function files).
- Make each test file double as a how-to/usage/example for the feature it tests (readable and copyable snippets).
- Add minimal assertion helpers (pure PHP) and a test runner with discovery + basic filtering.
- Add dependency-aware tests: when optional deps/extensions are missing, tests can be skipped with a clear message (not fail).
- Add a Composer script entry for running tests (no additional runtime dependency).

## Impact
- Affected specs: testing strategy, automation, dependency policy for tests.
- Affected code: new test harness files under `tests/`; new test files for each `src/**`; `composer.json` (scripts); `README.md` (how to run tests).

## ADDED Requirements
### Requirement: Minimum PHP Support
The system SHALL support running the library and its tests on PHP 7.4.

#### Scenario: Success case
- **WHEN** developer runs tests using PHP 7.4 CLI
- **THEN** the test harness runs and produces a deterministic pass/fail (and skip) result

### Requirement: Lightweight Test Harness (No Framework)
The system SHALL provide a minimal test harness implemented only with PHP standard features (no PHPUnit/Pest required) to execute tests from CLI.

The harness SHALL:
- Discover test files by convention (e.g., `tests/**/*Test.php`).
- Provide basic CLI options (at least `--filter <pattern>` and `--list`).
- Print a readable summary (passed/failed/skipped counts).
- Exit with code `0` if all tests passed or were skipped; exit with non-zero code if any test failed.

#### Scenario: Success case
- **WHEN** developer runs `php tests/run.php`
- **THEN** tests are discovered and executed, and the process exits with `0` on success

### Requirement: One Feature/File Has Its Own Test File
For each PHP source file under `src/**`, the system SHALL provide a corresponding test file that exercises its primary behaviors.

Rules:
- Class files SHALL have unit-style tests that validate public API behavior.
- Function/snippet files SHALL have tests that validate return values and side effects (if any).
- Optional-module files (e.g., spreadsheet) SHALL have tests that verify both:
  - Behavior when dependency is available, and
  - A clear error/skip behavior when dependency is not available.

#### Scenario: Success case
- **WHEN** developer changes a single file under `src/**`
- **THEN** there exists a direct test file covering that file’s key behaviors

### Requirement: Dependency-Aware Skips
Tests that depend on optional PHP extensions or optional Composer packages SHALL be skippable rather than hard-failing when dependencies are missing.

Skips SHALL:
- Print a clear reason (missing extension/package).
- Not count as failures.

#### Scenario: Success case
- **WHEN** developer runs tests without `phpoffice/phpspreadsheet`
- **THEN** spreadsheet tests are skipped (or the dependency-specific parts are skipped) with a clear message

### Requirement: Tests as How-To/Usage Examples
Each test file SHALL be written as a readable usage example for the code it tests.

Each test file SHALL include a top-level header (docblock or equivalent) that explains:
- What is being tested (feature/file)
- Minimal usage example(s) that can be copy-pasted
- Dependency notes (extensions/packages) and expected behavior when missing (skip vs fail)

#### Scenario: Success case
- **WHEN** developer opens a `*Test.php` file
- **THEN** the developer can learn how to use the tested feature by reading the test (without needing separate documentation)

## MODIFIED Requirements
### Requirement: Minimal Dependency Policy (Testing)
Testing SHALL not introduce a mandatory testing framework dependency. Running tests SHOULD be possible with only `php` plus the repo’s dev dependencies (if present).

## REMOVED Requirements
Tidak ada.
