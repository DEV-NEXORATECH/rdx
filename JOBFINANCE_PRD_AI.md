# JOBFINANCE — PRODUCT REQUIREMENTS DOCUMENT (PRD)

> Version: 1.0 | Date: 2026-10-08 | Project: Greenfield (build from zero) | Language UI: Bahasa Indonesia
> Product: JobFinance — Domestic Logistics ERP & Finance
> Stack: **Laravel 12, PHP 8.3+, Blade, Livewire 3, Alpine.js, Tailwind CSS, MySQL 8, Vite**. One deployable **modular monolith**, not an SPA, not microservices.

## 1. Product Vision & Boundaries
Build an integrated system for Indonesian **domestic/local shipping**: customer → inquiry/quotation → approval → job order → dispatch/trucking → tracking → proof of delivery → job costs → job closing → invoice → customer collection/vendor payment → double-entry journal → financial statements. Support intra-city, inter-city, inter-province, FTL, LTL, multi-stop, internal fleet and third-party vendors. Do not require international shipping fields for domestic jobs. **International forwarding modules** (LCL, POL/POD Port, AWB, BL, customs, FX) are **optional future modules**, not scope of initial production release; keep extensibility but do not build features that the business has not confirmed.

**Definition of success:** each transaction has one source of truth; finance outputs reconcile to posted accounting entries; every action is permission-protected, auditable and testable; every screen follows the same component library.

## 2. Design System — STRICT
**Palette:** White-first with blue accents, no navy/red-heavy layout.

| Token | HEX | Usage |
|---|---|---|
| `--color-background` | `#F8FAFC` | Page background |
| `--color-surface` | `#FFFFFF` | Card/sidebar/table |
| `--color-primary` | `#2563EB` | Primary CTA, links |
| `--color-primary-hover` | `#1D4ED8` | Hover |
| `--color-primary-soft` | `#EFF6FF` | Active menu, highlights |
| `--color-heading` | `#0F172A` | Heading |
| `--color-text` | `#334155` | Body |
| `--color-muted` | `#64748B` | Secondary text |
| `--color-border` | `#E2E8F0` | Borders |
| `--color-success` | `#16A34A` | Success semantic only |
| `--color-warning` | `#D97706` | Warning semantic only |
| `--color-danger` | `#DC2626` | Error/destructive only |

Use Inter or system sans, 8px spacing rhythm, rounded-lg/xl, subtle borders/shadows. **White sidebar**, blue active state; white topbar; light page background. Mobile responsive. Accessible contrast, focus styles, keyboard navigation, clear loading/empty/error states. Do not introduce another color system or independent page CSS.

### Universal page contracts
- Index: breadcrumbs, page title, short description, permission-aware Create, summary cards where useful, **shared** search/filter/sort/page-size/export toolbar, server paginated data table, actions, loading/empty/error.
- Form: shared input/select/searchable-select/date/money/textarea/file and validation components, sections, cancel/save actions, dirty-form warning.
- Detail: page header + status, structured cards/tabs, activity timeline, document list, contextual authorized actions.
- Dashboard: shared KPI cards, chart cards, filter bar, drilldown lists; figures derived from real queries, **no hard-coded fake numbers in production**.

### Reusable component inventory
`AppShell`, `Sidebar`, `Topbar`, `Breadcrumbs`, `PageHeader`, `KpiCard`, `ContentCard`, `DataTable`, `TableToolbar`, `SearchInput`, `FilterDrawer`, `SortSelect`, `PageSizeSelect`, `Pagination`, `EmptyState`, `Skeleton`, `PrimaryButton`, `SecondaryButton`, `DangerButton`, `IconButton`, `FormField`, `TextInput`, `MoneyInput`, `NumberInput`, `SelectInput`, `DateInput`, `FileUpload`, `FormSection`, `Modal`, `ConfirmDialog`, `StatusBadge`, `Toast`, `ApprovalTimeline`, `AuditTimeline`, `AttachmentList`, `DocumentPreview`, `ChartCard`, `ExportAction`.
Shared components accept configurable labels/columns/slots and do not embed business-specific rules. Module screens MUST import/use shared components, not clone markup. Component registry and examples required at `/design-system` (restricted to admin/developer).

## 3. Roles & Permissions
Roles: **Super Admin, Finance, Finance Manager, Sales, Sales Manager, Operational, Customer Service, Accounting, Management**, optional Driver. Backend route/policy checks mandatory; hiding menus alone is insufficient. Permissions granular (`quotation.view/create/edit/submit/approve`, `job.create/dispatch/update-status/close/reopen`, `cost.finalize`, `invoice.post`, `payment.approve`, `journal.post/reverse`, `report.view`, etc.). Scope Sales to own quotations by default; managers/team scope explicitly configurable. Financial reporting private to entitled roles. Audit sensitive actions.

## 4. Modules & Requirements

### A. Authentication & Administration
Login/logout/reset/change password, user CRUD, roles/permissions, menu policy, company & branch settings, numbering format, fiscal year, accounting periods, notifications, activity logs, approval configurations, document templates. Seed named permissions, seed roles, and a development admin account configurable through environment (no default production credentials).

### B. Master Data
Companies/branches, Customers + multiple PIC/addresses/NPWP/payment terms/credit limits, Vendors + contacts/bank details/vehicles, Drivers + SIM validity, Vehicles + type/plate/capacity/ownership, geographic provinces/cities/districts, Routes, Services, Shipment Types (FTL/LTL/parcel), Cargo Types, Unit/Packaging, Charge/Cost Types, Taxes, Bank/Cash Accounts, COA, Fiscal Years, Periods, Currency (IDR mandatory), document types. Every list uses shared CRUD components, pagination/filter/export where appropriate.

### C. Sales & Quotation
Customer inquiry optional; Quotation number, customer, origin/destination addresses, stops, cargo pieces/weight/dimensions/CBM, shipment service, carrier/vehicle, cost & sell lines, discount/tax if applicable, estimated gross margin, validity period, document preview/PDF, duplicate/versioned revision. Lifecycle: `DRAFT → SUBMITTED → APPROVED / REVISION_REQUESTED / REJECTED → CONVERTED`. Approval Sales Manager; quote-to-job conversion only once (idempotent) and only if approved; no editing approved values in place without revision.

### D. Domestic Tariff & Calculators
Rate matrix by origin/destination zone, service, route, vendor, vehicle, charge basis (per trip/kg/CBM/package), minimums, effective/expiry dates, surcharge; estimate FTL/LTL, volume CBM = L×W×H (unit-normalized), chargeable weight with configurable volumetric divisor, taxes configurable, provisional cost/selling/margin. Save rate snapshots on quotation; historical quotes must not change when master tariff changes.

### E. Operations — Job Orders & Dispatch
Create from approved quote or permissioned manual entry. Track customer, billing party, sender/recipient, pickup/dropoff including multiple stops, goods, quantities, cargo constraints, truck/vendor/driver, ETD/ETA (domestic scheduling labels), planned vs actual pickup/delivery. Separate statuses for **shipment**, **cost readiness**, **invoice**, **payment**. Shipment lifecycle: `DRAFT/OPEN → SCHEDULED → PICKED_UP → IN_TRANSIT → ARRIVED → DELIVERED → POD_VERIFIED`; exception statuses `ON_HOLD`, `FAILED_DELIVERY`, `RETURNED`, `CANCELLED`. State transition policy + audit every transition. Dispatch orders, domestic Surat Jalan, cargo receipt, delivery note, uploaded POD (receiver, timestamp, signature/photo, partial qty, exceptions). Timeline per job; delayed delivery list. Do not force ocean/air/customs documents.

### F. Job Costs, Approval & Vendor Payables
Per-job cost lines: trucking, fuel, toll, parking, driver, loading/unloading, storage, handling, courier, other; provisional, temporary, reimbursements, vendor AP, debit/credit adjustments. Fields: vendor, date, type, qty/unit, currency (IDR first), unit cost, unit sell, tax treatment, supporting file, status, linked job. Workflow `DRAFT → SUBMITTED → APPROVED → FINAL → PARTIALLY_PAID/PAID`; rejection and void with history. Draft/unapproved never post GL. On finalization: create balanced, mapped, idempotent AP/WIP or cost entries per agreed accounting policy. On payment: settle AP, bank and applicable withholding liability; partial payment supported. **Tax applicability/rates are configuration governed by Finance/Accounting**, not blind PPh23 for every transaction.

### G. Job Closing & Revenue Recognition
Closing checklist: operation complete per policy; required POD; costs final; no pending approval; valid account mapping; open accounting period; charge completeness; user authorized. Closing snapshots quantities, charge/revenue, cost, taxes, gross profit and margin. Posting revenue/HPP only under a **documented accounting policy**; posting must be transactional and idempotent; never double-count WIP/HPP. Reopen/reversal must be permissioned, reason-coded and preserve history; posted journals corrected by explicit reversal/adjustment, never deletion.

### H. Invoicing, Collection, Payments
Generate invoice from eligible job snapshot, itemized charges/tax/currency, unique number, due date/payment terms, PDF/print, sending status, full/partial payment, receipt and allocations, AR aging, customer statement of account; vendor bill/payment and bank/cash reconciliation. Handle credit/debit notes and cancellations by controlled accounting documents. Invoice status distinct from collection status. PPN or e-invoicing integration only when tax rules/integration contract are confirmed; no fake Coretax API.

### I. Accounting & Finance
COA hierarchy; mapping per event and cost category; balanced immutable **posted** GL journals with source links; manual draft journals; reversal; ledger; trial balance; balance sheet; profit and loss; job HPP by cost type; profit per job/month; AR/AP aging; cash movements; SOA; delivery margin by route/customer. Drill-down: report → account → journal → source job/cost/invoice. Fiscal/period close and lock. Check accounting policy before finalizing recognition, withholding and tax rules. Financial reports reconcile to journal posted lines; GL postings require `debits == credits` and open period.

### J. Role Dashboards
- **Finance:** overdue AR count/amount, posted journals, open/closed jobs, cash collected, receivables aging, invoice follow-up, revenue/profit trend.
- **Finance Manager:** revenue, HPP, profit, margin, cost composition, approval queue, best/worst jobs, financial reports.
- **Sales:** owned draft/pending/approved/converted quotations, funnel, recent quotes, own shipment summary.
- **Sales Manager:** team approval queue, quotation funnel, 30-day activity, conversion metrics.
- **Operational:** open jobs, draft/final costs, upcoming pickup/delivery, delayed jobs, jobs ready to close, shipment status.
- **Customer Service:** deliveries due in 7 days, in-transit/late shipments, customer follow-up, POD pending, delivery confirmations.
- **Accounting:** unposted journal queue, reconciliation, period closure, trial balance alerts.
- **Management:** revenue/HPP/gross profit, cash/AR, job volumes, cost by route and profit trend.
Include consistent period/filter handling, policy scoping, real data, drilldown, and stable chart components.

### K. Optional AI Extensions (after core stable)
1. **Document OCR:** vendor invoice, surat jalan, POD; extract to review-only drafts with confidence flags and source evidence; human validation required.
2. **Pricing suggestion:** historical route/service/vendor rates, recommended price with margin floor; human approval.
3. **Risk/insight:** overdue AR, anomalous job cost, low-margin jobs, likely late shipments (when history sufficient).
AI must not autonomously approve, post journals, change payments, or override permissions. Store credentials server-side; log prompt/version/result; redact sensitive info and require consent/appropriate agreement before sending documents to external provider. AI failure must never block core finance/logistics flows.

## 5. End-to-End Flows
**Quote-to-Cash:** Customer → quotation draft → Sales Manager approval → convert job → plan/dispatch → pickup → track → deliver → POD verified → final cost → close job → invoice/post → collect/allocate → GL → reports.

**Procure-to-Pay (job costs):** Job → vendor/cost request → approval → final AP/cost journal → vendor payment incl. withholding when applicable → bank reconciled → reports.

**Returns/Exceptions:** Late/failure/partial delivery → event + evidence + resolution plan → extra charges/cost approval → close only when conditions satisfied.

## 6. Suggested DB Domains
- `users`, `roles`, `permissions`, `audit_logs`, `notifications`, `approval_flows`, `approval_steps`
- `customers`, `customer_contacts`, `customer_addresses`, `vendors`, `vendor_contacts`, `vendor_bank_accounts`
- `vehicles`, `vehicle_types`, `drivers`, `service_types`, `routes`, `locations`, `tariff_rates`, `tariff_rate_versions`
- `quotations`, `quotation_items`, `quotation_approvals`, `quotation_status_histories`
- `job_orders`, `job_stops`, `job_items`, `dispatches`, `shipment_events`, `delivery_proofs`, `job_documents`
- `job_costs`, `job_cost_items`, `cost_approvals`, `job_closing_snapshots`
- `invoices`, `invoice_lines`, `customer_receipts`, `payment_allocations`, `vendor_bills`, `vendor_payments`, `reimbursements`
- `accounts`, `account_mappings`, `journals`, `journal_lines`, `fiscal_years`, `accounting_periods`, `tax_rules`, `bank_accounts`, `bank_reconciliations`
- `attachments`, `document_sequences`, `settings`
Exact schema to be designed via migrations with FK/index/unique constraints, `DECIMAL` amounts (not float), enums or controlled reference tables, document numbering concurrency protection, transaction idempotency keys, and safe data retention. Prevent cross-customer data exposure.

## 7. Monolith Organization
```text
app/
  Domains/
    Shared/ Identity/ MasterData/ CRM/ Sales/ Pricing/
    Logistics/ Fleet/ Finance/ Accounting/ Reporting/ Admin/
      {Models, Actions, Services, Queries, DTOs, Enums, Policies, Events}/
  Http/{Controllers,Requests,Middleware}/
  Livewire/{Dashboards,Sales,Logistics,Finance,Accounting,Admin}/
  View/Components/
resources/
  views/{layouts,components,livewire,pages}/
  css/{app.css,tokens.css}/
  js/app.js
routes/{web.php,admin.php,sales.php,logistics.php,finance.php,accounting.php}
database/{migrations,seeders,factories}
tests/{Unit,Feature,Browser}
docs/{architecture,adr,modules,ui-components,qa}
```
No business logic inside Blade; no giant Livewire components; prefer Actions/Services; Eloquent scoped queries; policies at route/action levels; financial posting within DB transactions + locks as needed; queues for PDFs/mail/large exports; cache invalidation defined for dashboards.

## 8. Quality Gates & Nonfunctional
- Security: auth, policies, CSRF, rate-limit, upload validation/storage, audited financial actions, encrypted secrets, role-scope tests.
- Performance: server-side pagination; eager loading; indexed filters; dashboard aggregate query/cache; queued heavy exports.
- Reliability: DB transaction/unique lock for posting; no duplicate journal/invoice; backup/restore procedures; exception monitoring.
- Testing: Pest/PHPUnit unit, feature, permission matrices, accounting balance and idempotency, role-scoped data, job state transition, browser smoke tests where feasible.
- Finance acceptance: trial balance debit == credit, balance sheet equation, P&L ties to job recognition per approved policy, invoice payments tie to AR and bank, void/reopen preserves audit history.
- Do not claim tests passed unless you ran them. Do not publish invented dashboard metrics. No production deploy without explicit approval.

## 9. Build Plan (sequential dependency gates)
1. Bootstrap Laravel monolith, DB connection, auth, permissions, master layout, **white–blue component design system**, CI/testing.
2. All master data and RBAC, component demos.
3. Domestic rates, calculators, CRM/customer/vendor, quotes and approvals.
4. Job Order, multi-stop, dispatch, driver/vehicle, shipment status, POD.
5. Job costs/AP, approvals, reimbursement, validation.
6. Accounting engine/mappings, journals, periods, reporting primitives.
7. Job closing, invoice, AR/collection, vendor payment, reconciliation.
8. Role dashboards, drill-down reports, export/PDF.
9. Regression/UAT/security/performance; optional AI pilot once core is stable.

For each phase: produce migration/seeders, domain actions, authorization, reusable UI, tests, and concise implementation report. Do not generate fake pages marked complete. Prefer complete, runnable vertical slices.

## 10. Acceptance Scenarios
- Sales can quote IDR domestic shipping without ports or FX, submit; Sales Manager can approve; Sales cannot approve own quote unless explicitly authorized.
- Approved quote converts into **exactly one** job; retries do not create duplicates.
- Operational can assign driver/truck, issue surat jalan, update pickup/in-transit/delivered and attach POD; unauthorized transitions rejected.
- Finance creates/finalizes cost; draft produces **no posted journal**; final cost produces balanced mapped entries only once.
- Authorized closing validates cost/POD/period; source journal balanced/idempotent, job snapshot stable.
- Invoice generation/partial receipts correctly update outstanding and AR; vendor payments settle AP with tax rules when applicable.
- Accounting reports drill down to originating transaction and reconcile.
- All module index pages use identical toolbar/table/pagination pattern; all forms use shared controls; mobile view works.
- Cross-role forbidden actions return 403 and never leak protected data.

## 11. Decisions That Must Be Validated Before Financial Go-Live
- Legal entity/branches, default currency, invoice sequence; contract/payment terms.
- Domestic rates: per kg/per CBM/per trip, volume divisors, tax-inclusive/exclusive rules.
- Approval threshold matrices and job closing definition (POD requirement, partial shipment handling).
- Chart of accounts, cost capitalization/WIP, revenue/HPP recognition timing, tax calculations and jurisdiction.
- Whether any international forwarding module should be included in later phases.

When unclear during development, write decisions and configurable defaults into `docs/adr/`, and **do not silently invent accounting policies or tax rates**.
