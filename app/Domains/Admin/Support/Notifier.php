<?php

namespace App\Domains\Admin\Support;

use Illuminate\Support\Facades\Session;

final class Notifier
{
    public static function success(string $message): void
    {
        self::flash('success', $message);
    }

    public static function info(string $message): void
    {
        self::flash('info', $message);
    }

    public static function warning(string $message): void
    {
        self::flash('warning', $message);
    }

    public static function error(string $message): void
    {
        self::flash('error', $message);
    }

    private static function flash(string $type, string $message): void
    {
        Session::flash('toast', ['type' => $type, 'message' => $message]);
    }
}
