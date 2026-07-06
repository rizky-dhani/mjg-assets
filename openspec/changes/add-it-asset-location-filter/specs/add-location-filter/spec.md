# Spec Delta: Add Location Filter to ITAssetResource

## ADDED Requirements

### Requirement: IT Asset Location Filter
The `ITAssetResource` table MUST provide a filter to narrow down results by `asset_location_id`.

#### Scenario: Filtering by Location
- **Given** the user is viewing the IT Assets list.
- **When** the user selects a specific location from the "Location" filter.
- **Then** only assets associated with that location should be displayed in the table.
- **And** the filter options should be preloaded from the `it_asset_locations` table.
- **And** the filter should be searchable.
