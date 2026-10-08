<?php

namespace App\Livewire\MasterData;

use App\Models\MasterRecord;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $type = '';
    public string $search = '';
    public string $sort = 'name';
    public string $direction = 'asc';
    public ?int $editingId = null;
    public string $code = '';
    public string $name = '';
    public string $notes = '';
    public bool $is_active = true;

    private const TYPES = [
        'companies' => ['title' => 'Perusahaan & Cabang', 'singular' => 'Perusahaan/Cabang'],
        'locations' => ['title' => 'Lokasi & Wilayah', 'singular' => 'Lokasi'],
        'routes' => ['title' => 'Rute', 'singular' => 'Rute'],
        'services' => ['title' => 'Tipe Layanan', 'singular' => 'Tipe Layanan'],
        'taxes' => ['title' => 'Pajak', 'singular' => 'Pajak'],
        'units' => ['title' => 'Satuan & Kemasan', 'singular' => 'Satuan/Kemasan'],
        'customers' => ['title' => 'Customer', 'singular' => 'Customer'],
        'vendors' => ['title' => 'Vendor', 'singular' => 'Vendor'],
        'shippers' => ['title' => 'Shipper', 'singular' => 'Shipper'],
        'consignees' => ['title' => 'Consignee', 'singular' => 'Consignee'],
        'shipping-lines' => ['title' => 'Shipping Line', 'singular' => 'Shipping Line'],
        'container-types' => ['title' => 'Jenis Container', 'singular' => 'Jenis Container'],
        'marketings' => ['title' => 'Marketing', 'singular' => 'Marketing'],
        'vehicles' => ['title' => 'Kendaraan', 'singular' => 'Kendaraan'],
        'drivers' => ['title' => 'Pengemudi', 'singular' => 'Pengemudi'],
    ];

    public static function types(): array
    {
        return self::TYPES;
    }

    public function mount(): void
    {
        $this->type = explode('.', request()->route()->getName())[1] ?? '';
        abort_unless(isset(self::TYPES[$this->type]), 404);
    }

    public function sortBy(string $column): void
    {
        if (! in_array($column, ['code', 'name', 'is_active'], true)) return;
        if ($this->sort === $column) $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        else [$this->sort, $this->direction] = [$column, 'asc'];
    }

    public function edit(int $id): void
    {
        $prefix = explode('.', request()->route()->getName())[0];
        $this->redirectRoute($prefix.'.'.$this->type.'.edit', ['record' => $id]);
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'code', 'name', 'notes']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        MasterRecord::updateOrCreate(
            ['id' => $this->editingId],
            ['type' => $this->type, 'code' => $this->code ?: null, 'name' => $this->name, 'notes' => $this->notes ?: null, 'is_active' => $this->is_active]
        );

        $this->resetForm();
        session()->flash('status', 'Data berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        MasterRecord::where('type', $this->type)->whereKey($id)->delete();
        session()->flash('status', 'Data berhasil dihapus.');
    }

    public function render()
    {
        $meta = self::TYPES[$this->type];
        $records = MasterRecord::where('type', $this->type)
            ->when($this->search !== '', fn ($query) => $query->where(fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('code', 'like', '%'.$this->search.'%')))
            ->orderBy($this->sort, $this->direction)->paginate(10);

        return view('livewire.master-data.index', compact('meta', 'records'))->title($meta['title']);
    }
}
