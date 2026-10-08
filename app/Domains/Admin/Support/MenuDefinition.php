<?php

namespace App\Domains\Admin\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

final class MenuDefinition
{
    /**
     * Central side-navigation definition. Items whose `permission` is not satisfied
     * by the current user are hidden automatically.
     *
     * @return array<int, array{label: string, icon: string, route?: string, permission?: string|array, active?: string, children?: array}>
     */
    public static function items(): array
    {
        $menu = [
            [
                'label' => 'Dashboard',
                'icon' => 'home',
                'route' => 'dashboard',
            ],
            [
                'label' => 'Penawaran',
                'icon' => 'document-text',
                'route' => 'sales.quotations.index',
                'permission' => 'quotation.view',
                'active' => 'sales.*',
            ],
            [
                'label' => 'Tarif Domestik',
                'icon' => 'tag',
                'route' => 'pricing.tariffs.index',
                'permission' => 'tariff.view',
            ],
            [
                'label' => 'Job Order',
                'icon' => 'truck',
                'route' => 'logistics.jobs.index',
                'permission' => 'job.view',
                'active' => 'logistics.*',
            ],
            [
                'label' => 'Biaya Job',
                'icon' => 'document-text',
                'route' => 'finance.job-costs.index',
                'permission' => 'job_cost.view',
            ],
            [
                'label' => 'Tagihan',
                'icon' => 'receipt',
                'route' => 'finance.invoices.index',
                'permission' => 'invoice.view',
            ],
            [
                'label' => 'Pelanggan',
                'icon' => 'user-group',
                'route' => 'crm.customers.index',
                'permission' => 'customer.view',
            ],
            [
                'label' => 'Vendor',
                'icon' => 'building',
                'route' => 'crm.vendors.index',
                'permission' => 'vendor.view',
            ],
            [
                'label' => 'Armada',
                'icon' => 'truck',
                'active' => 'fleet.*',
                'permission' => ['vehicle.view', 'driver.view'],
                'children' => [
                    ['label' => 'Kendaraan', 'route' => 'fleet.vehicles.index', 'permission' => 'vehicle.view'],
                    ['label' => 'Pengemudi', 'route' => 'fleet.drivers.index', 'permission' => 'driver.view'],
                ],
            ],
            [
                'label' => 'Data Master',
                'icon' => 'layout',
                'active' => 'master.*',
                'permission' => [
                    'company.view', 'location.view', 'route.view', 'service.view', 'tax.view',
                    'unit.view', 'charge_type.view', 'cost_type.view',
                ],
                'children' => [
                    ['label' => 'Perusahaan & Cabang', 'route' => 'master.companies.index', 'permission' => 'company.view'],
                    ['label' => 'Lokasi & Wilayah', 'route' => 'master.locations.index', 'permission' => 'location.view'],
                    ['label' => 'Rute', 'route' => 'master.routes.index', 'permission' => 'route.view'],
                    ['label' => 'Tipe Layanan', 'route' => 'master.services.index', 'permission' => 'service.view'],
                    ['label' => 'Pajak', 'route' => 'master.taxes.index', 'permission' => 'tax.view'],
                    ['label' => 'Satuan & Kemasan', 'route' => 'master.units.index', 'permission' => 'unit.view'],
                ],
            ],
            [
                'label' => 'Akuntansi',
                'icon' => 'calculator',
                'active' => 'accounting.*',
                'permission' => ['account.view', 'journal.view', 'period.view'],
                'children' => [
                    ['label' => 'Bagan Akun', 'route' => 'accounting.accounts.index', 'permission' => 'account.view'],
                    ['label' => 'Jurnal', 'route' => 'accounting.journals.index', 'permission' => 'journal.view'],
                    ['label' => 'Periode', 'route' => 'accounting.periods.index', 'permission' => 'period.view'],
                    ['label' => 'Laporan', 'route' => 'accounting.reports.index', 'permission' => 'report.view'],
                ],
            ],
            [
                'label' => 'Administrasi',
                'icon' => 'cog',
                'active' => 'admin.*',
                'permission' => [
                    'user.view', 'role.view', 'audit.view', 'settings.view', 'design_system.view',
                ],
                'children' => [
                    ['label' => 'Pengguna', 'route' => 'admin.users.index', 'permission' => 'user.view'],
                    ['label' => 'Peran & Izin', 'route' => 'admin.roles.index', 'permission' => 'role.view'],
                    ['label' => 'Jejak Audit', 'route' => 'admin.audits.index', 'permission' => 'audit.view'],
                    ['label' => 'Pustaka Komponen', 'route' => 'design-system.index', 'permission' => 'design_system.view'],
                ],
            ],
        ];

        return self::satisfied($menu);
    }

    public static function allowedFor(?string $routeName): bool
    {
        return self::isAllowed(self::find($routeName));
    }

    public static function find(?string $routeName): ?array
    {
        foreach (self::items() as $item) {
            if (($item['route'] ?? null) === $routeName) {
                return $item;
            }

            foreach ($item['children'] ?? [] as $child) {
                if (($child['route'] ?? null) === $routeName) {
                    return $child;
                }
            }
        }

        return null;
    }

    private static function satisfied(array $menu): array
    {
        $result = [];

        foreach ($menu as $item) {
            $permissions = (array) ($item['permission'] ?? []);

            if ($permissions !== [] && ! self::canAny($permissions)) {
                continue;
            }

            if (isset($item['children'])) {
                $item['children'] = self::satisfied($item['children']);

                if ($item['children'] === []) {
                    continue;
                }
            } elseif (isset($item['route']) && ! Route::has($item['route'])) {
                continue;
            }

            $result[] = $item;
        }

        return $result;
    }

    private static function canAny(array $permissions): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    private static function isAllowed(?array $item): bool
    {
        if ($item === null) {
            return true;
        }

        return (array) ($item['permission'] ?? []) === [] || self::canAny((array) $item['permission']);
    }
}
