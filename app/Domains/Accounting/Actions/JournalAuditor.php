<?php

namespace App\Domains\Accounting\Actions;

use App\Models\JournalAudit;
use App\Models\JournalEntry;

final class JournalAuditor
{
    public static function record(JournalEntry $journal, string $action, ?string $sourceType = null, ?int $sourceId = null): void
    {
        JournalAudit::create(['journal_entry_id' => $journal->id, 'source_type' => $sourceType, 'source_id' => $sourceId, 'action' => $action, 'snapshot' => $journal->toArray()]);
    }

    public static function reverse(JournalEntry $journal, string $reason): JournalEntry
    {
        $reversal = JournalEntry::create(['entry_date' => now()->toDateString(), 'reference' => 'REV-'.$journal->reference, 'description' => 'Pembalik: '.$reason, 'debit_account_id' => $journal->credit_account_id, 'credit_account_id' => $journal->debit_account_id, 'amount' => $journal->amount]);
        self::record($journal, 'reversed', null, null);
        self::record($reversal, 'reversal', null, null);
        return $reversal;
    }
}
