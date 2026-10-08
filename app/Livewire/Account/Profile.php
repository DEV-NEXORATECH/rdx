<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RuntimeException;

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
            File::ensureDirectoryExists(public_path('uploads/profiles'));
            $filename = $this->profile_photo->hashName();
            $target = public_path('uploads/profiles/'.$filename);
            if (! copy($this->profile_photo->getRealPath(), $target)) {
                throw new RuntimeException('Foto profil tidak dapat disimpan.');
            }
            @unlink($this->profile_photo->getRealPath());
            $data['profile_photo_path'] = 'uploads/profiles/'.$filename;
        }

        if ($this->ktp_photo) {
            File::ensureDirectoryExists(public_path('uploads/ktp'));
            $filename = $this->ktp_photo->hashName();
            $target = public_path('uploads/ktp/'.$filename);
            if (! copy($this->ktp_photo->getRealPath(), $target)) {
                throw new RuntimeException('Dokumen KTP tidak dapat disimpan.');
            }
            @unlink($this->ktp_photo->getRealPath());
            $data['ktp_photo_path'] = 'uploads/ktp/'.$filename;
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
