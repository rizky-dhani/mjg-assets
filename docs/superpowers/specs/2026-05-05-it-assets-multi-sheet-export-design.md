# IT Assets Multi-Sheet Export

**Date:** 2026-05-05
**Status:** Approved

## Summary

Refactor the IT Assets Excel export to separate data into per-category sheets, add a category selection modal, reorder columns, and change sort order.

## Requirements

1. Move "Asset Code" column before "Asset Name" in headings and data mapping
2. Sort records descending by `asset_code` (instead of `created_at`)
3. Separate exported data into individual sheets, one per `it_asset_categories`
4. Remove the "Category" column from export data (redundant since sheets are category-specific)
5. Show a modal with category multi-select when user clicks "Export to Excel" — if no categories selected, export all
6. Only the header "Export to Excel" button gets the category modal (bulk action stays as-is)
7. Multi-sheet separation applies universally (header export, bulk action, all categories)

## Architecture

### Files

| File | Action |
|------|--------|
| `app/Exports/ITD/ITAssetsExport.php` | Rewrite to implement `WithMultipleSheets` |
| `app/Exports/ITD/ITAssetCategorySheet.php` | **New** — per-category sheet class |
| `app/Filament/Resources/ITD/ITAssetResource/Pages/ListITAssets.php` | Add modal with category select |

### ITAssetsExport (modified)

- Implements `WithMultipleSheets` instead of `FromCollection`
- Constructor accepts `array $ids = []` and `array $categoryIds = []`
- `sheets()` returns one `ITAssetCategorySheet` per selected/present category
- When `$categoryIds` is empty, loads all categories that have assets (or all categories)
- When `$ids` is provided (bulk action), passes IDs down to each sheet

### ITAssetCategorySheet (new)

- Implements `FromCollection`, `WithHeadings`, `WithMapping`, `WithStyles`, `ShouldAutoSize`
- Constructor receives: `ITAssetCategory $category`, `array $ids = []`
- `collection()` queries `ITAsset` filtered by `asset_category_id` and optionally by `$ids`
- Ordered by `asset_code` descending
- `headings()` — same as current but without "Category" and with "Asset Code" first
- `map()` — same as current but without the category field
- Sheet title = category name

### Filament Action (modified)

- "Export to Excel" action becomes a modal with `modalWidth: 'md'`
- Contains a multi-select field for categories (Filament's `Select` with `multiple()`)
- Labeled "Select Categories (optional)" with hint "Leave empty to export all categories"
- On submit, passes `$categoryIds` to `ITAssetsExport`
- Button text stays "Export to Excel"

### Column Order (headings)

```
Asset Code, Asset Name, Location, Serial Number, Port, Asset Year, Condition, User, Position, Created By
```

(Category column removed — redundant per-sheet)

## Data Flow

```
User clicks "Export to Excel"
  → Modal opens with category multi-select
  → User selects categories (or leaves empty)
  → ITAssetsExport(categoryIds: [1,3,5]) created
  → sheets() iterates categories, creates ITAssetCategorySheet per category
  → Each sheet queries: WHERE asset_category_id = ? ORDER BY asset_code DESC
  → Excel downloaded with sheets named per category
```

## Constraints

- Only the header "Export to Excel" button gets the modal. Bulk action passes IDs only.
- Multi-sheet separation applies to ALL exports (header and bulk).
- When bulk action provides IDs, each category sheet only includes assets matching those IDs within that category.
