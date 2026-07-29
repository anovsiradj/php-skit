# Tasks
- [ ] Task 1: Define version policy in project docs
  - [ ] Document PHP 7.4 as the minimum runtime version
  - [ ] Document PHP 8.5 as the preferred development version
  - [ ] Explain the difference between runtime support and development workflow

- [ ] Task 2: Align project metadata and guidance
  - [ ] Review `composer.json` metadata/scripts for wording or workflow notes related to PHP versions
  - [ ] Add or update contributor-facing guidance in `README.md`

- [ ] Task 3: Define compatibility safeguards
  - [ ] Describe how PHP 8.5 development avoids breaking PHP 7.4 consumers
  - [ ] Clarify when Symfony polyfills are allowed for newer core functions
  - [ ] Define at least one compatibility-sensitive validation step

- [ ] Task 4: Validate the documented workflow
  - [ ] Ensure the documented policy is consistent with existing PHP 7.4 support claims
  - [ ] Ensure the documented policy is consistent with the current testing strategy

# Task Dependencies
- Task 2 depends on Task 1
- Task 3 depends on Task 1
- Task 4 depends on Task 2 and Task 3
