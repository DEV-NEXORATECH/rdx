<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $currentPassword = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function save(): void
    {
        $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [], [
            'currentPassword' => 'kata sandi saat ini',
            'password' => 'kata sandi baru',
        ]);

        Auth::user()->update(['password' => Hash::make($this->password)]);
        $this->reset();
        session()->flash('status', 'Kata sandi berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.account.change-password')->title('Ubah Kata Sandi');
    }
}
