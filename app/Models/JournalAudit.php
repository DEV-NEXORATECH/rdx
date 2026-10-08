<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalAudit extends Model
{
    protected $fillable = ['journal_entry_id', 'source_type', 'source_id', 'action', 'snapshot'];
    protected function casts(): array { return ['snapshot' => 'array']; }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
