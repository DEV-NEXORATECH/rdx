<?php

namespace App\Domains\Shared\Support;

use Illuminate\Support\Facades\DB;

/**
 * Concurrency-safe document numbering backed by a locked DB sequence row.
 *
 * @example DocumentNumber::next('QUOTATION', 'QTN/{fiscalYear}/{seq:5}')
 */
final class DocumentNumber
{
    /**
     * Generate the next booking number as MM + a minimum four-digit sequence.
     *
     * Examples: 010001, 010002, ..., 120999, 121000.
     */
    public static function nextBooking(): string
    {
        return self::next('BOOKING', '{month}{seq:4}');
    }

    public static function next(string $context, string $format): string
    {
        return DB::transaction(function () use ($context, $format) {
            /** @var \stdClass|null $row */
            $row = DB::table('document_sequences')->where('context', $context)->lockForUpdate()->first();

            if ($row === null) {
                DB::table('document_sequences')->insert([
                    'context' => $context,
                    'next_number' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $number = 1;
            } else {
                $number = $row->next_number;

                DB::table('document_sequences')
                    ->where('context', $context)
                    ->update(['next_number' => $row->next_number + 1, 'updated_at' => now()]);
            }

            return self::format($format, $number);
        });
    }

    public static function format(string $format, int $number): string
    {
        $replacements = [
            '{seq}' => (string) $number,
            '{seq:4}' => str_pad((string) $number, 4, '0', STR_PAD_LEFT),
            '{seq:5}' => str_pad((string) $number, 5, '0', STR_PAD_LEFT),
            '{seq:6}' => str_pad((string) $number, 6, '0', STR_PAD_LEFT),
            '{year}' => now()->format('Y'),
            '{month}' => now()->format('m'),
            '{fiscalYear}' => now()->format('Y'),
        ];

        return strtr($format, $replacements);
    }
}
