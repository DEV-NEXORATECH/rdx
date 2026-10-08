# ADR-001: Architecture — Domain-First Modular Monolith

Date: 2026-10-08
Status: Accepted

## Context
JobFinance is domestic logistics ERP+Finance. Needs clear boundaries (Identity, Admin, Shared, Master, CRM, Sales, Logistics, Finance/Accounting, Reports).

## Decision
- `app/Domains/{Domain}/Models|Actions|Support|Concerns|Policies|Enums|Rules|Jobs` structure
- Shared kernel (`Shared/Support/Money`, `Shared/Support/DocumentNumber`, `Shared/Actions/RecordAudit`)
- Spatie Permission with custom Role/Permission under `Domains/Identity/Models`
- Livewire 4 full-page components + Blade views
- MySQL/MariaDB, Blade+Tailwind 4, Alpine

## Consequences
- Strong cohesion, easy to extract later
- Clear ownership
- Predictable imports

## References
PRD §7, AGENTS.md
