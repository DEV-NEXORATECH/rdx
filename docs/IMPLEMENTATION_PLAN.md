# JobFinance — Implementation Plan

Last updated: 2026-10-08
Status: Foundation complete (auth, RBAC kernel, shared UI, app shell, seeders, design-system). Next: Master Data + CRM.

## 1. Foundation (COMPLETE)
- [x] Laravel 12, Livewire 4, Tailwind 4, Spatie Permission
- [x] Domain structure (Identity, Admin, Shared)
- [x] RBAC kernel + PermissionRegistry + MenuDefinition
- [x] Shared UI components (icon, button, badge, status-badge, page-header, breadcrumbs, content-card, kpi-card, data-table, row-actions, toolbar, search-input, pagination, modal, confirm-dialog, toast, form controls, empty-state, skeleton, timeline, chart-card, tabs, avatar)
- [x] Auth (login, logout, password reset, rate limiting) + guest/app layouts
- [x] Livewire pages: Dashboard, Admin Users/Roles/Permissions/Audits (Index/Form/Show), Design System
- [x] WithListing trait, favicon, app.js toast/notify
- [x] Seeders (permissions, admin user)
- [x] Tests (auth), pint, build

## 2. Docs (IN PROGRESS)
- [ ] docs/IMPLEMENTATION_PLAN.md (this file)
- [ ] docs/UI_COMPONENT_REGISTRY.md
- [ ] docs/ADR-001-architecture.md
- [ ] docs/ADR-002-livewire-v4-layouts.md
- [ ] docs/ADR-003-rbac-domain.md

## 3. Master Data
Domains: companies, locations, routes, services, taxes, units, charge_types, cost_types (as per PRD).
- [ ] Migrations + models + factories
- [ ] Policies/permissions (already in PermissionRegistry)
- [ ] Livewire CRUD (index/form/show)
- [ ] Validation, audit hooks
- [ ] Tests

## 4. CRM
- [ ] Customers, Vendors CRUD
- [ ] Contacts, addresses (optional minimal)
- [ ] Livewire pages

## 5. Sales & Pricing
- [ ] Tariffs (domestic), quotation flow (draft→sent→approved→converted)
- [ ] Approval rules
- [ ] Tests

## 6. Logistics & Fleet
- [ ] Vehicles, Drivers
- [ ] Job Orders (booking→assigned→in_transit→delivered→closed)
- [ ] POD upload + verification
- [ ] Assignments

## 7. Finance & Accounting
- [ ] Job Costs, Invoices (AR), Bills (AP), Chart of Accounts, Journals, Periods
- [ ] Approval, numbering (DocumentNumber)
- [ ] Reports

## 8. Dashboards & Reports
- [ ] Role-based dashboards
- [ ] Operational/Finance reports (minimal export CSV)

## 9. Hardening
- [ ] Audit completeness
- [ ] Force-approve constraints
- [ ] CSV consistency
- [ ] Production build check
- [ ] Security pass
- [ ] Permission tests
- [ ] E2E smoke

## Conventions
- Bahasa Indonesia UI
- Domain-first layout
- wire:model.live.debounce.300ms for search
- Reuse shared components
- Minimal changes
- Tests green uncached
