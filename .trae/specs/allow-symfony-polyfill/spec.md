# Symfony Polyfill Policy Spec (PHP 7.4 Baseline)

## Why
We want to keep minimum support at PHP 7.4, but still allow using newer PHP core functions/features when they materially improve clarity and correctness, by relying on Symfony polyfills.

## What Changes
- Define an explicit policy for when it is acceptable to use Symfony polyfills to “backport” PHP 8+ core functions into PHP 7.4.
- Define which polyfill packages are allowed and how they should be declared in Composer (required vs suggested).
- Add a compatibility guideline: prefer PHP 7.4-native equivalents when they exist; use polyfills only when they provide unique value.
- Require tests to document usage and clarify whether a feature is native vs polyfilled.

## Impact
- Affected specs: minimal dependency policy, PHP 7.4 compatibility rules, testing-as-documentation rules.
- Affected code: `composer.json` dependency policy, any source file that uses PHP 8+ functions, and tests that demonstrate usage.

## ADDED Requirements
### Requirement: PHP 7.4 Baseline With Optional Polyfills
The system SHALL keep PHP 7.4 as the minimum supported runtime.

The system SHALL allow usage of selected PHP 8+ core functions on PHP 7.4 by using Symfony polyfill packages, when those functions are not available in PHP 7.4.

#### Scenario: Success case
- **WHEN** developer runs the library on PHP 7.4 with the required polyfill installed
- **THEN** calls to the polyfilled core function work as expected

### Requirement: Polyfill Dependency Policy
The system SHALL only add Symfony polyfills when the codebase actually uses the polyfilled APIs.

Rules:
- If production code calls a function that does not exist in PHP 7.4 and is provided by a Symfony polyfill, that polyfill SHALL be declared as a production dependency (`composer.json: require`).
- If the polyfill is not required by production code, it SHOULD NOT be added to `require`. It MAY be placed in `suggest` as a convenience.
- The chosen polyfill packages SHOULD be limited to the minimum set needed (avoid broad “just in case” adds).

#### Scenario: Success case
- **WHEN** the package is installed without optional/test dependencies
- **THEN** it only pulls in polyfills that are actually needed by runtime code

### Requirement: Allowed Polyfill Packages
The system SHALL only use the official Symfony polyfill packages for PHP core backports.

Allowed examples (pick only what is needed by usage):
- `symfony/polyfill-php80` (e.g., `str_contains`, `str_starts_with`, `str_ends_with`)
- `symfony/polyfill-php81`
- `symfony/polyfill-php82`
- `symfony/polyfill-php83` (e.g., `str_increment`, `str_decrement`)
- `symfony/polyfill-php84`

#### Scenario: Success case
- **WHEN** a developer wants to use a PHP 8+ core function in PHP 7.4
- **THEN** they either use an existing PHP 7.4-native equivalent, or use the minimal Symfony polyfill package that provides it

### Requirement: Prefer PHP 7.4-Native Equivalents
When PHP 7.4 has a clear, safe equivalent (language/operator/standard library behavior), the system SHOULD prefer the PHP 7.4-native approach over introducing a polyfill dependency.

#### Scenario: Success case
- **WHEN** the same outcome can be achieved without a polyfill
- **THEN** the implementation stays dependency-free and compatible with PHP 7.4

## MODIFIED Requirements
### Requirement: Minimal Dependency Policy (Clarified)
Minimal dependency remains the default. Symfony polyfills are an allowed exception when they provide strong value (clarity, correctness, maintainability) and there is no clean PHP 7.4-native substitute.

## REMOVED Requirements
Tidak ada.
