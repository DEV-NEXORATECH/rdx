<?php

namespace Tests\Unit;

use App\Domains\Admin\Support\PermissionRegistry;
use PHPUnit\Framework\TestCase;

class PermissionRegistryTest extends TestCase
{
    public function test_transaction_permissions_are_registered(): void
    {
        $permissions = PermissionRegistry::all();

        foreach (['quotation.view', 'quotation.export', 'job.edit', 'job_cost.delete', 'invoice.approve', 'journal.reverse', 'report.export'] as $permission) {
            self::assertArrayHasKey($permission, $permissions);
        }
    }
}
