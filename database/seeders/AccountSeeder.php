<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['1000', 'Kas', 'asset'], ['1100', 'Piutang Usaha', 'asset'], ['2100', 'Kas/Utang Operasional', 'liability'], ['4100', 'Pendapatan Jasa', 'revenue'], ['6100', 'Biaya Operasional', 'expense']] as [$code, $name, $type]) {
            Account::updateOrCreate(['code' => $code], ['name' => $name, 'type' => $type, 'is_active' => true]);
        }
    }
}
