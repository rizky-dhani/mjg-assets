# Implementation Plan: Implement filtering and sorting options for the assets list PDF report

## Phase 1: Backend Filtering Logic

- [ ] Task: Implement filtering logic for asset category
    - [ ] Write Tests: Create unit tests for `GaAsset` and `ITAsset` models to test category filtering.
    - [ ] Implement Feature: Add scope methods to `GaAsset` and `ITAsset` models to filter by category.
- [ ] Task: Implement filtering logic for asset location
    - [ ] Write Tests: Create unit tests for `GaAsset` and `ITAsset` models to test location filtering.
    - [ ] Implement Feature: Add scope methods to `GaAsset` and `ITAsset` models to filter by location.
- [ ] Task: Implement filtering logic for asset status (if applicable)
    - [ ] Write Tests: Create unit tests for `GaAsset` and `ITAsset` models to test status filtering.
    - [ ] Implement Feature: Add scope methods to `GaAsset` and `ITAsset` models to filter by status.
- [ ] Task: Integrate filtering logic into PDF generation query
    - [ ] Write Tests: Create integration tests for the PDF generation process to ensure filters are applied correctly.
    - [ ] Implement Feature: Modify the query in `pdf/assets-list.blade.php` to accept and apply filtering parameters.
- [ ] Task: Conductor - User Manual Verification 'Phase 1: Backend Filtering Logic' (Protocol in workflow.md)

## Phase 2: Backend Sorting Logic

- [ ] Task: Implement sorting logic for various fields (e.g., name, acquisition date)
    - [ ] Write Tests: Create unit tests for `GaAsset` and `ITAsset` models to test sorting.
    - [ ] Implement Feature: Add scope methods to `GaAsset` and `ITAsset` models to handle sorting by different fields.
- [ ] Task: Integrate sorting logic into PDF generation query
    - [ ] Write Tests: Create integration tests for the PDF generation process to ensure sorting is applied correctly.
    - [ ] Implement Feature: Modify the query in `pdf/assets-list.blade.php` to accept and apply sorting parameters.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Backend Sorting Logic' (Protocol in workflow.md)

## Phase 3: Frontend Integration (Filament Panel)

- [ ] Task: Add filter controls to the Filament resource for asset lists
    - [ ] Write Tests: Create Filament component tests for the filter controls.
    - [ ] Implement Feature: Add new filters to the `GaAssetResource` and `ITAssetResource` list pages.
- [ ] Task: Add sort controls to the Filament resource for asset lists
    - [ ] Write Tests: Create Filament component tests for the sort controls.
    - [ ] Implement Feature: Add sorting options to the `GaAssetResource` and `ITAssetResource` list pages.
- [ ] Task: Pass filter and sort parameters to PDF generation endpoint
    - [ ] Write Tests: Create browser tests to ensure filter and sort parameters are correctly passed.
    - [ ] Implement Feature: Modify the PDF generation action to receive and forward filter/sort parameters.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Frontend Integration (Filament Panel)' (Protocol in workflow.md)

## Phase 4: PDF Report Refinement

- [ ] Task: Display active filters and sorting in the PDF header
    - [ ] Write Tests: Create feature tests to verify filter and sort display in PDF.
    - [ ] Implement Feature: Update `pdf/assets-list.blade.php` to show active filters and sort order.
- [ ] Task: Ensure pagination and layout are maintained with filtering/sorting
    - [ ] Write Tests: Create feature tests to verify PDF layout with different filter/sort combinations.
    - [ ] Implement Feature: Adjust `pdf/assets-list.blade.php` layout and pagination logic if necessary.
- [ ] Task: Conductor - User Manual Verification 'Phase 4: PDF Report Refinement' (Protocol in workflow.md)
