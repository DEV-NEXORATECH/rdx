# UI Component Registry

Components under `resources/views/components/` with props/slots.

## Layout
- `layouts/app.blade.php` — app shell (sidebar, topbar, footer, toast)
- `layouts/guest.blade.php` — auth pages

## App Shell
- `app/brand.blade.php`
- `app/sidebar.blade.php`
- `app/nav-link.blade.php`
- `app/nav-group.blade.php`
- `app/topbar.blade.php`

## Primitives
- `icon.blade.php` — heroicons-style paths
- `button.blade.php` — variants primary/secondary/ghost/danger/icon
- `badge.blade.php`, `status-badge.blade.php`

## Page
- `page-header.blade.php` — title/description/actions
- `breadcrumbs.blade.php`
- `content-card.blade.php`
- `kpi-card.blade.php`
- `empty-state.blade.php`
- `skeleton.blade.php`

## Table
- `table/data-table.blade.php` — columns, sort, rowActions, loading, empty
- `table/row-actions.blade.php`
- `table/toolbar.blade.php`
- `search-input.blade.php`
- `sort-select.blade.php`
- `page-size-select.blade.php`
- `export-action.blade.php`
- `pagination.blade.php`

## Form
- `form/form-field.blade.php`
- `form/input.blade.php`
- `form/money-input.blade.php`
- `form/number-input.blade.php`
- `form/select.blade.php`
- `form/date-input.blade.php`
- `form/textarea.blade.php`
- `form/checkbox.blade.php`
- `form/file-upload.blade.php`
- `form/section.blade.php`

## Overlays
- `modal.blade.php`
- `confirm-dialog.blade.php`
- `toast.blade.php`

## Misc
- `timeline.blade.php`
- `chart-card.blade.php`
- `tabs.blade.php`
- `avatar.blade.php`

Usage: Livewire full-page components under `app/Livewire/*`, Blade views under `resources/views/livewire/*`. All admin routes use Livewire 4 (Route::get(..., Component::class)).
