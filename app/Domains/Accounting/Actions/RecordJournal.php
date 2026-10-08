<?php

namespace App\Domains\Accounting\Actions;

use App\Models\Account;
use App\Models\JournalEntry;

final class RecordJournal
{
    public static function create(string $date, ?string $reference, string $description, string $debitCode, string $creditCode, float $amount): JournalEntry
    {
        $debit = Account::firstOrCreate(['code' => $debitCode], ['name' => self::label($debitCode), 'type' => self::type($debitCode), 'is_active' => true]);
        $credit = Account::firstOrCreate(['code' => $creditCode], ['name' => self::label($creditCode), 'type' => self::type($creditCode), 'is_active' => true]);

        $journal = JournalEntry::firstOrCreate(['reference' => $reference], ['entry_date' => $date, 'description' => $description, 'debit_account_id' => $debit->id, 'credit_account_id' => $credit->id, 'amount' => $amount]);
        \App\Models\JournalAudit::firstOrCreate(['journal_entry_id' => $journal->id, 'action' => 'created'], ['snapshot' => $journal->toArray()]);
        return $journal;
    }

    private static function label(string $code): string
    {
        return ['1000' => 'Kas', '1100' => 'Piutang Usaha', '2100' => 'Kas/Utang Operasional', '4100' => 'Pendapatan Jasa', '6100' => 'Biaya Operasional'][$code] ?? 'Akun '.$code;
    }

    private static function type(string $code): string
    {
        return str_starts_with($code, '1') ? 'asset' : (str_starts_with($code, '4') ? 'revenue' : 'expense');
    }
}
