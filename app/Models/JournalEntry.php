<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['entry_date', 'reference', 'description', 'debit_account_id', 'credit_account_id', 'amount'];
    protected function casts(): array { return ['entry_date' => 'date', 'amount' => 'decimal:2']; }
    public function debitAccount() { return $this->belongsTo(Account::class, 'debit_account_id'); }
    public function creditAccount() { return $this->belongsTo(Account::class, 'credit_account_id'); }
}
