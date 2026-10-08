<?php

namespace App\Domains\Admin\Support;

final class PermissionRegistry
{
    public static function all(): array
    {
        $permissions = [];

        foreach (self::groups() as $group) {
            foreach ($group as $permission => $label) {
                $permissions[$permission] = $label;
            }
        }

        return $permissions;
    }

    /**
     * Permission groups: group key => [permission => label].
     */
    public static function groups(): array
    {
        return [
            'Pengguna & Peran' => [
                'user.view' => 'Melihat pengguna',
                'user.create' => 'Membuat pengguna',
                'user.edit' => 'Mengubah pengguna',
                'user.delete' => 'Menghapus pengguna',
                'user.reset_password' => 'Meriset kata sandi',
                'role.view' => 'Melihat peran',
                'role.create' => 'Membuat peran',
                'role.edit' => 'Mengubah peran',
                'role.delete' => 'Menghapus peran',
                'role.assign_permissions' => 'Mengatur hak akses peran',
            ],
            'Data Master' => [
                'company.view' => 'Melihat entitas perusahaan',
                'company.edit' => 'Mengelola perusahaan & cabang',
                'location.view' => 'Melihat lokasi / wilayah',
                'location.edit' => 'Mengelola lokasi / wilayah',
                'route.view' => 'Melihat rute',
                'route.edit' => 'Mengelola rute',
                'service.view' => 'Melihat tipe layanan',
                'service.edit' => 'Mengelola tipe layanan',
                'tax.view' => 'Melihat pajak',
                'tax.edit' => 'Mengelola pajak',
                'unit.view' => 'Melihat satuan / kemasan',
                'unit.edit' => 'Mengelola satuan / kemasan',
                'charge_type.view' => 'Melihat tipe biaya',
                'charge_type.edit' => 'Mengelola tipe biaya',
                'cost_type.view' => 'Melihat tipe biaya pokok',
                'cost_type.edit' => 'Mengelola tipe biaya pokok',
            ],
            'Armada' => [
                'vehicle.view' => 'Melihat kendaraan',
                'vehicle.edit' => 'Mengelola kendaraan',
                'driver.view' => 'Melihat pengemudi',
                'driver.edit' => 'Mengelola pengemudi',
            ],
            'CRM' => [
                'customer.view' => 'Melihat pelanggan',
                'customer.create' => 'Membuat pelanggan',
                'customer.edit' => 'Mengubah pelanggan',
                'customer.delete' => 'Menghapus pelanggan',
                'vendor.view' => 'Melihat vendor',
                'vendor.create' => 'Membuat vendor',
                'vendor.edit' => 'Mengubah vendor',
                'vendor.delete' => 'Menghapus vendor',
            ],
            'Tarif & Katalog' => [
                'tariff.view' => 'Melihat tarif',
                'tariff.create' => 'Membuat tarif',
                'tariff.edit' => 'Mengubah tarif',
                'tariff.delete' => 'Menghapus tarif',
                'tariff.export' => 'Mengekspor tarif',
                'tariff.publish' => 'Menerbitkan versi tarif',
            ],
            'Penjualan' => [
                'quotation.view' => 'Melihat penawaran',
                'quotation.create' => 'Membuat penawaran',
                'quotation.edit' => 'Mengubah penawaran',
                'quotation.submit' => 'Mengirim penawaran',
                'quotation.approve' => 'Menyetujui penawaran',
                'quotation.revise' => 'Meminta revisi penawaran',
                'quotation.convert' => 'Mengonversi penawaran ke job order',
                'quotation.delete' => 'Menghapus penawaran',
                'quotation.export' => 'Mengekspor penawaran',
            ],
            'Operasional' => [
                'job.view' => 'Melihat job order',
                'job.create' => 'Membuat job order',
                'job.edit' => 'Mengubah job order',
                'job.delete' => 'Menghapus job order',
                'job.approve' => 'Menyetujui job order',
                'job.export' => 'Mengekspor job order',
                'job.update_status' => 'Memperbarui status pengiriman',
                'job.dispatch' => 'Mendistribusikan armada',
                'job.close' => 'Menutup job',
                'job.reopen' => 'Membuka ulang job',
                'pod.view' => 'Melihat bukti serah terima',
                'pod.upload' => 'Mengunggah bukti serah terima',
            ],
            'Keuangan' => [
                'job_cost.view' => 'Melihat biaya job',
                'job_cost.create' => 'Membuat biaya job',
                'job_cost.edit' => 'Mengubah biaya job',
                'job_cost.delete' => 'Menghapus biaya job',
                'job_cost.export' => 'Mengekspor biaya job',
                'job_cost.submit' => 'Mengajukan biaya job',
                'job_cost.approve' => 'Menyetujui biaya job',
                'job_cost.finalize' => 'Memfinalisasi biaya job',
                'job_cost.void' => 'Membatalkan biaya job',
                'invoice.view' => 'Melihat tagihan',
                'invoice.create' => 'Membuat tagihan',
                'invoice.edit' => 'Mengubah tagihan',
                'invoice.delete' => 'Menghapus tagihan',
                'invoice.approve' => 'Menyetujui tagihan',
                'invoice.export' => 'Mengekspor tagihan',
                'invoice.post' => 'Memposting tagihan',
                'invoice.send' => 'Menandai tagihan terkirim',
                'invoice.void' => 'Membatalkan tagihan',
                'receipt.view' => 'Melihat penerimaan',
                'receipt.create' => 'Mencatat penerimaan',
                'receipt.edit' => 'Mengubah penerimaan',
                'receipt.delete' => 'Menghapus penerimaan',
                'receipt.export' => 'Mengekspor penerimaan',
                'receipt.approve' => 'Menyetujui penerimaan',
                'vendor_payment.view' => 'Melihat pembayaran vendor',
                'vendor_payment.create' => 'Mencatat pembayaran vendor',
                'vendor_payment.approve' => 'Menyetujui pembayaran vendor',
                'reimbursement.view' => 'Melihat reimbursemen',
                'reimbursement.create' => 'Mencatat reimbursemen',
                'reimbursement.approve' => 'Menyetujui reimbursemen',
            ],
            'Akuntansi' => [
                'account.view' => 'Melihat bagan akun',
                'account.create' => 'Membuat akun',
                'account.edit' => 'Mengubah akun',
                'account.delete' => 'Menghapus akun',
                'account_mapping.view' => 'Melihat pemetaan akun',
                'account_mapping.edit' => 'Mengelola pemetaan akun',
                'journal.view' => 'Melihat jurnal',
                'journal.create' => 'Membuat jurnal manual',
                'journal.edit' => 'Mengubah jurnal manual',
                'journal.delete' => 'Menghapus jurnal manual',
                'journal.export' => 'Mengekspor jurnal',
                'journal.post' => 'Memposting jurnal',
                'journal.reverse' => 'Membalik jurnal',
                'period.view' => 'Melihat periode akuntansi',
                'period.create' => 'Membuat periode akuntansi',
                'period.edit' => 'Mengubah periode akuntansi',
                'period.delete' => 'Menghapus periode akuntansi',
                'period.open' => 'Membuka periode',
                'period.close' => 'Menutup periode',
                'fiscal_year.view' => 'Melihat tahun fiskal',
                'fiscal_year.manage' => 'Mengelola tahun fiskal',
            ],
            'Pelaporan' => [
                'report.view' => 'Melihat laporan keuangan',
                'report.export' => 'Mengekspor laporan',
            ],
            'Administrasi' => [
                'audit.view' => 'Melihat jejak audit',
                'settings.view' => 'Melihat pengaturan',
                'settings.edit' => 'Mengubah pengaturan',
                'design_system.view' => 'Melihat pustaka komponen',
            ],
        ];
    }

    /**
     * Role mapping: role => [permissions or '*' => 'all'].
     */
    public static function roleMatrix(): array
    {
        $all = array_keys(self::all());

        $finance = [
            'job_cost.view', 'job_cost.create', 'job_cost.edit', 'job_cost.submit', 'job_cost.void',
            'invoice.view', 'invoice.create',
            'receipt.view', 'receipt.create',
            'vendor_payment.view', 'vendor_payment.create',
            'reimbursement.view', 'reimbursement.create',
            'customer.view', 'vendor.view',
            'job.view',
            'account.view', 'account_mapping.view', 'journal.view', 'period.view', 'fiscal_year.view',
            'report.view',
        ];

        $financeManager = array_merge($finance, [
            'job_cost.edit', 'job_cost.delete', 'job_cost.export',
            'invoice.edit', 'invoice.delete', 'invoice.approve', 'invoice.export',
            'receipt.edit', 'receipt.delete', 'receipt.export',
            'job_cost.approve', 'job_cost.finalize',
            'invoice.post', 'invoice.send', 'invoice.void',
            'receipt.approve',
            'vendor_payment.approve',
            'reimbursement.approve',
            'report.export',
            'audit.view',
        ]);

        return [
            'super_admin' => $all,
            'management' => [
                'customer.view', 'vendor.view',
                'quotation.view', 'job.view', 'job_cost.view', 'invoice.view', 'receipt.view',
                'vendor_payment.view', 'reimbursement.view',
                'account.view', 'journal.view', 'period.view',
                'report.view', 'report.export', 'audit.view', 'settings.view',
                'vehicle.view', 'driver.view',
            ],
            'finance' => $finance,
            'finance_manager' => $financeManager,
            'sales' => [
                'customer.view', 'customer.create', 'customer.edit',
                'quotation.view', 'quotation.create', 'quotation.edit', 'quotation.submit',
                'tariff.view', 'service.view', 'route.view', 'location.view', 'tax.view', 'unit.view',
                'job.view', 'job_cost.view', 'invoice.view',
            ],
            'sales_manager' => [
                'customer.view', 'customer.create', 'customer.edit',
                'quotation.view', 'quotation.create', 'quotation.edit', 'quotation.submit',
                'quotation.approve', 'quotation.revise', 'quotation.delete', 'quotation.convert', 'quotation.export',
                'tariff.view', 'service.view', 'route.view', 'location.view', 'tax.view', 'unit.view',
                'job.view', 'job_cost.view', 'invoice.view', 'report.view',
            ],
            'operational' => [
                'customer.view', 'vendor.view',
                'quotation.view', 'quotation.convert',
                'job.view', 'job.create', 'job.update_status', 'job.dispatch',
                'pod.view', 'pod.upload',
                'job_cost.view',
                'vehicle.view', 'vehicle.edit', 'driver.view', 'driver.edit',
                'route.view', 'route.edit', 'service.view', 'location.view', 'unit.view',
                'tariff.view',
            ],
            'customer_service' => [
                'customer.view', 'customer.create', 'customer.edit',
                'quotation.view', 'quotation.create', 'quotation.edit',
                'job.view', 'job.update_status', 'pod.view',
                'route.view', 'service.view', 'location.view',
            ],
            'accounting' => [
                'customer.view', 'vendor.view',
                'job.view', 'job_cost.view', 'invoice.view', 'receipt.view', 'vendor_payment.view', 'reimbursement.view',
                'account.view', 'account.create', 'account.edit', 'account.delete',
                'account_mapping.view', 'account_mapping.edit',
                'journal.view', 'journal.create', 'journal.edit', 'journal.delete', 'journal.export', 'journal.post', 'journal.reverse',
                'period.view', 'period.create', 'period.edit', 'period.delete', 'period.open', 'period.close',
                'fiscal_year.view', 'fiscal_year.manage',
                'report.view', 'report.export', 'audit.view',
            ],
            'driver' => [
                'job.view',
            ],
        ];
    }

    public static function roleLabels(): array
    {
        return [
            'super_admin' => 'Super Admin',
            'management' => 'Management',
            'finance' => 'Finance',
            'finance_manager' => 'Finance Manager',
            'sales' => 'Sales',
            'sales_manager' => 'Sales Manager',
            'operational' => 'Operational',
            'customer_service' => 'Customer Service',
            'accounting' => 'Accounting',
            'driver' => 'Driver',
        ];
    }
}
