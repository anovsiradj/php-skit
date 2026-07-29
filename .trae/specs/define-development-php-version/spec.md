# Development PHP Version Spec

## Why
The package still targets PHP 7.4 at runtime, but development should move with the latest PHP version so modern language features, tooling, and checks can be evaluated earlier.

## What Changes
- Define a dual-version policy: runtime compatibility stays at PHP 7.4, while day-to-day development uses PHP 8.5.
- Define how code should be written and validated when developed on PHP 8.5 but shipped for PHP 7.4 compatibility.
- Define documentation and tooling expectations so contributors understand which PHP version applies to runtime vs development.
- Define compatibility guardrails for tests, linting, and optional polyfills.

## Impact
- Affected specs: PHP compatibility policy, development workflow, validation strategy, documentation.
- Affected code: `README.md`, `composer.json` metadata/scripts if needed, test workflow, and any contributor-facing documentation.

## ADDED Requirements
### Requirement: Dual PHP Version Policy
The system SHALL support PHP 7.4 as the minimum runtime version for package users.

The development workflow SHALL use PHP 8.5 as the primary development version.

#### Scenario: Success case
- **WHEN** a contributor develops features locally
- **THEN** they use PHP 8.5 for editing, running tooling, and day-to-day tests
- **AND WHEN** the package is consumed in production
- **THEN** the runtime contract remains compatible with PHP 7.4

### Requirement: Compatibility-First Development
Code written on PHP 8.5 SHALL remain intentionally compatible with PHP 7.4 unless a newer runtime requirement is explicitly approved.

Rules:
- PHP 8.5-only syntax SHALL NOT be used in runtime code unless there is an approved compatibility strategy.
- Newer core functions MAY be used when covered by an approved polyfill policy.
- Validation SHOULD include a compatibility check that catches unsupported PHP 8.5 syntax/features before release.

#### Scenario: Success case
- **WHEN** a developer uses PHP 8.5 locally
- **THEN** they can still contribute safely without accidentally breaking PHP 7.4 consumers

### Requirement: Clear Runtime vs Development Documentation
The project SHALL clearly document the difference between:
- minimum supported runtime PHP version, and
- preferred development PHP version.

The documentation SHALL also explain the practical meaning:
- users can install/run on PHP 7.4+
- contributors are expected to develop and verify on PHP 8.5

#### Scenario: Success case
- **WHEN** a new contributor reads the README or project metadata
- **THEN** they understand that runtime support and development version are intentionally different

### Requirement: Test and Tooling Expectations
The project SHALL define testing/tooling expectations for this split-version workflow.

Rules:
- The main development test flow MAY run on PHP 8.5.
- Compatibility-sensitive checks SHOULD verify that runtime code remains PHP 7.4-safe.
- Tests/examples SHALL avoid implying that PHP 8.5 is required for package consumers unless that is explicitly true.

#### Scenario: Success case
- **WHEN** contributors run the normal development workflow
- **THEN** they use PHP 8.5
- **AND** the project still has a clear way to detect PHP 7.4 compatibility regressions

## MODIFIED Requirements
### Requirement: Minimum PHP Support
The package runtime support remains PHP 7.4, but the preferred development environment is PHP 8.5.

## REMOVED Requirements
Tidak ada.
