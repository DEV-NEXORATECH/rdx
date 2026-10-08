<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $nik = '';

    public string $birth_date = '';

    public string $gender = '';

    public string $address = '';

    public string $city = '';

    public string $postal_code = '';

    public ?TemporaryUploadedFile $profile_photo = null;

    public ?TemporaryUploadedFile $ktp_photo = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->nik = $user->nik ?? '';
        $this->birth_date = $user->birth_date?->format('Y-m-d') ?? '';
        $this->gender = $user->gender ?? '';
        $this->address = $user->address ?? '';
        $this->city = $user->city ?? '';
        $this->postal_code = $user->postal_code ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
            'phone' => ['nullable', 'string', 'max:30'],
            'nik' => ['nullable', 'string', 'max:32', 'unique:users,nik,'.Auth::id()],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'ktp_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $user = Auth::user();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'nik' => $this->nik ?: null,
            'birth_date' => $this->birth_date ?: null,
            'gender' => $this->gender ?: null,
            'address' => $this->address ?: null,
            'city' => $this->city ?: null,
            'postal_code' => $this->postal_code ?: null,
        ];

        if ($this->profile_photo) {
            $data['profile_photo_path'] = $this->profile_photo->store('profiles', 'public');
        }

        if ($this->ktp_photo) {
            $data['ktp_photo_path'] = $this->ktp_photo->store('ktp', 'public');
        }

        $user->update($data);
        $this->profile_photo = null;
        $this->ktp_photo = null;

        session()->flash('status', 'Profil berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.account.profile')->title('Profil Saya');
    }
}
