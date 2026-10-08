<?php

use App\Livewire\DesignSystem\Index;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('users', App\Livewire\Admin\Users\Index::class)->middleware(PermissionMiddleware::class.':user.view')->name('users.index');
        Route::get('roles', App\Livewire\Admin\Roles\Index::class)->middleware(PermissionMiddleware::class.':role.view')->name('roles.index');
        Route::get('permissions', App\Livewire\Admin\Permissions\Index::class)->middleware(PermissionMiddleware::class.':role.assign_permissions')->name('permissions.index');
        Route::get('audits', App\Livewire\Admin\Audits\Index::class)->middleware(PermissionMiddleware::class.':audit.view')->name('audits.index');
    });

Route::middleware(['auth', 'verified', PermissionMiddleware::class.':design_system.view'])
    ->get('design-system', Index::class)
    ->name('design-system.index');
