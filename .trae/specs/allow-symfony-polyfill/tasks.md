# Tasks
- [x] Task 1: Polyfill needs assessment
  - [x] List all PHP 8+ core functions currently used (if any) and map them to Symfony polyfill packages
  - [x] For each usage, decide: replace with PHP 7.4-native equivalent OR keep and require minimal polyfill

- [x] Task 2: Composer dependency update (minimal)
  - [x] If runtime code uses polyfilled functions, add only the required `symfony/polyfill-phpXX` packages to `composer.json: require`
  - [x] Otherwise, document them in `composer.json: suggest` (optional) with clear reasoning

- [x] Task 3: Documentation update
  - [x] Add/extend README notes: which modern functions are used, which polyfills are expected, and how to install them

- [x] Task 4: Testing-as-usage coverage
  - [x] Ensure relevant tests include usage snippets that call the (native/polyfilled) function paths
  - [x] Ensure tests clearly state whether behavior requires a polyfill (and how it behaves without it)

# Task Dependencies
- Task 2 depends on Task 1
- Task 3 depends on Task 2
- Task 4 can be done in parallel once Task 1 decisions are made
