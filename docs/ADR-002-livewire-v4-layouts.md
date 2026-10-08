# ADR-002: Livewire v4 Layouts & Routing

Date: 2026-10-08
Status: Accepted

## Context
Livewire v4 uses page components. Layout config: `component_layout` = `layouts::app` (vendor config) maps to `resources/views/layouts/app.blade.php`. Component locations include `resources/views/components` and `resources/views/livewire`.

## Decision
- Use `Route::get($uri, Component::class)` for full-page Livewire
- Set page title via `->title()` or `$title` in layout
- Use `wire:model.live[.debounce.300ms]` for live search (network no longer auto-sent on `wire:model` in v4)
- Reuse shared Blade components
- Keep layouts simple (guest/app)

## Consequences
- Consistent routing
- Predictable DOM updates

## References
Livewire v4.4.7 behavior observed
