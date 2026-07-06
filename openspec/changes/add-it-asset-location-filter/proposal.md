# Proposal: Add IT Asset Location Filter

## Objective
Add a `SelectFilter` for `asset_location_id` to the `ITAssetResource` table to allow users to filter assets by their location.

## Motivation
Users need a way to quickly find assets assigned to specific locations (e.g., specific rooms or departments) within the IT department's management view.

## Scope
- Modify `App\Filament\Resources\ITD\ITAssetResource::table()` to include a `SelectFilter` for the `location` relationship.
- Ensure the filter uses the `name` column from the `ITAssetLocation` model for labels.
- Preload the options for better user experience.
