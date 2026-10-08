<?php

namespace App\Livewire\MasterData;

use App\Models\MasterRecord;
use Illuminate\Support\Str;
use Livewire\Component;

class Form extends Component
{
    public string $type = '';
    public string $prefix = 'master';
    public ?int $editingId = null;
    public string $code = '';
    public string $name = '';
    public string $notes = '';
    public bool $is_active = true;
    public string $email = ''; public string $phone = ''; public string $address = ''; public string $tax_number = '';
    public string $plate_number = ''; public string $vehicle_type = ''; public string $capacity = ''; public string $license_number = '';
    public string $contact_person = ''; public string $payment_terms = ''; public string $credit_limit = '';
    public string $bank_name = ''; public string $bank_account = ''; public string $ownership = ''; public string $year = ''; public string $kir_expiry = '';
    public string $license_expiry = ''; public string $scac_code = ''; public string $website = '';
    public string $container_size = ''; public string $payload_capacity = ''; public string $tare_weight = '';

    public function mount(?int $record = null): void
    {
        $parts = explode('.', request()->route()->getName());
        $this->prefix = $parts[0] ?? 'master';
        $this->type = $parts[1] ?? '';
        abort_unless(isset(Index::types()[$this->type]), 404);

        if ($record !== null) {
            $item = MasterRecord::where('type', $this->type)->findOrFail($record);
            $this->editingId = $item->id;
            $this->code = (string) $item->code;
            $this->name = $item->name;
            $this->notes = (string) $item->notes;
            $this->is_active = $item->is_active;
            foreach ($this->metadataFields() as $field) $this->{$field} = (string) data_get($item->metadata, $field, '');
        }
    }

    public function save(): void
    {
        $rules = [
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->type === 'vehicles') {
            $rules['plate_number'] = ['required', 'string', 'max:30'];
        }
        if ($this->type === 'container-types') {
            $rules['container_size'] = ['required', 'string', 'max:30'];
        }
        if ($this->type === 'customers' || $this->type === 'vendors') {
            $rules['email'] = ['nullable', 'email', 'max:255'];
            $rules['credit_limit'] = ['nullable', 'numeric', 'min:0'];
        }
        if ($this->type === 'shipping-lines') {
            $rules['website'] = ['nullable', 'url', 'max:255'];
        }

        $this->validate($rules);

        $metadata = collect($this->metadataFields())->mapWithKeys(fn ($field) => [$field => $this->{$field} ?: null])->filter(fn ($value) => $value !== null)->all();
        MasterRecord::updateOrCreate(
            ['id' => $this->editingId],
            ['type' => $this->type, 'code' => $this->code ?: null, 'name' => $this->name, 'notes' => $this->notes ?: null, 'metadata' => $metadata, 'is_active' => $this->is_active]
        );

        session()->flash('status', 'Data berhasil disimpan.');
        $this->redirectRoute($this->prefix.'.'.$this->type.'.index');
    }

    private function metadataFields(): array
    {
        return match ($this->type) {
            'customers' => ['email', 'phone', 'address', 'tax_number', 'contact_person', 'payment_terms', 'credit_limit'],
            'vendors' => ['email', 'phone', 'address', 'tax_number', 'contact_person', 'bank_name', 'bank_account'],
            'companies', 'shippers', 'consignees', 'marketings' => ['email', 'phone', 'address', 'tax_number'],
            'vehicles' => ['plate_number', 'vehicle_type', 'capacity', 'ownership', 'year', 'kir_expiry'],
            'drivers' => ['phone', 'license_number', 'license_expiry', 'address'],
            'shipping-lines' => ['contact_person', 'phone', 'address', 'website', 'scac_code'],
            'container-types' => ['container_size', 'payload_capacity', 'tare_weight'],
            default => [],
        };
    }

    public function render()
    {
        $meta = Index::types()[$this->type];
        return view('livewire.master-data.form', compact('meta'))->title(($this->editingId ? 'Edit ' : 'Tambah ').$meta['singular']);
    }
}
