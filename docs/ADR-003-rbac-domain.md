# ADR-003: RBAC with Spatie Permission (Domain Models)

Date: 2026-10-08
Status: Accepted

## Context
Need fine-grained permissions + roles; UI must hide items by permission.

## Decision
- Use `spatie/laravel-permission` 6.x
- Custom models: `App\Domains\Identity\Models\Role`, `App\Domains\Identity\Models\Permission`
- `config/permission.php` wired to custom models + tables (with `label` on roles/permissions)
- Central permission registry `App\Domains\Admin\Support\PermissionRegistry` (groups + roleMatrix)
- Central nav `App\Domains\Admin\Support\MenuDefinition` filters by permission + route existence
- Policies optional later; Gate/HasRoles used

## Consequences
- Single source of truth for permissions
- Menu driven by registry
- Easy to extend per module

## References
PermissionSeeder, roleMatrix
