<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\LoanRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class EquipmentController extends Controller
{
    public function catalog(): View
    {
        return view('equipment.catalog', [
            'equipmentList' => Equipment::query()->orderBy('name')->orderBy('id')->simplePaginate(20),
        ]);
    }

    public function dashboard(): View
    {
        return view('petugas.dashboard', [
            'availableQuantity' => Equipment::query()->sum('available_quantity'),
        ]);
    }

    public function categories(): View
    {
        return view('petugas.categories', [
            'categories' => Equipment::query()
                ->select('category')
                ->selectRaw('COUNT(*) as equipment_count')
                ->groupBy('category')
                ->orderBy('category')
                ->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();
        $condition = $request->string('condition')->toString();

        return view('equipment.index', [
            'equipmentList' => Equipment::query()
                ->when($search !== '', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                ->when($condition !== '', fn ($query) => $query->where('condition', $condition))
                ->latest('id')->simplePaginate(15)->withQueryString(),
            'conditions' => ['Baik', 'Buruk'],
            'pendingLoanCount' => LoanRequest::query()->where('status', 'menunggu')->count(),
            'equipmentCount' => Equipment::query()->count(),
            'availableQuantity' => Equipment::query()->sum('available_quantity'),
            'damagedQuantity' => Equipment::query()
                ->whereLike('condition', 'buruk')
                ->orWhereLike('condition', 'rusak%')
                ->sum('available_quantity'),
            'search' => $search,
            'condition' => $condition,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->saveEquipment($request, new Equipment);

        return to_route('petugas.equipment.index')->with('status', 'Barang berhasil ditambahkan.');
    }

    public function show(Equipment $equipment): View
    {
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment): View
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $this->saveEquipment($request, $equipment);

        return to_route('petugas.equipment.index')->with('status', 'Barang berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        DB::transaction(function () use ($equipment) {
            $equipment = Equipment::query()->lockForUpdate()->findOrFail($equipment->id);

            if (LoanRequest::query()->where('equipment_id', $equipment->id)->where('status', 'dipinjam')->exists()) {
                throw ValidationException::withMessages(['equipment' => 'Alat masih dipinjam dan belum dapat dihapus.']);
            }

            $equipment->delete();
        }, 3);

        if ($equipment->photo_path) {
            Storage::disk('local')->delete($equipment->photo_path);
        }

        return to_route('petugas.equipment.index')->with('status', 'Barang berhasil dihapus.');
    }

    private function saveEquipment(Request $request, Equipment $equipment): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['sometimes', 'required', 'string', 'max:80'],
            'available_quantity' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'condition' => ['required', 'in:Baik,Buruk'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'available_quantity.min' => 'Jumlah tersedia minimal 0.',
            'available_quantity.max' => 'Jumlah tersedia melebihi batas.',
            'max.string' => ':attribute maksimal :max karakter.',
            'condition.in' => 'Pilih kondisi Baik atau Buruk.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Gunakan foto JPG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'photo.uploaded' => 'Foto gagal diunggah. Pastikan ukuran foto maksimal 2 MB.',
        ], [
            'name' => 'Nama alat',
            'category' => 'Kategori',
            'available_quantity' => 'Jumlah tersedia',
            'condition' => 'Kondisi',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('equipment', 'local');

            if ($photoPath === false) {
                throw ValidationException::withMessages(['photo' => 'Foto gagal disimpan. Silakan coba lagi.']);
            }
        }

        unset($data['photo']);
        $previousPhoto = $equipment->photo_path;
        if ($photoPath !== null) {
            $data['photo_path'] = $photoPath;
        }

        try {
            if (! $equipment->exists) {
                $data['category'] ??= 'Tanpa kategori';
            }

            $equipment->fill($data)->save();
        } catch (Throwable $exception) {
            if ($photoPath !== null) {
                Storage::disk('local')->delete($photoPath);
            }

            throw $exception;
        }

        if ($photoPath !== null && $previousPhoto) {
            Storage::disk('local')->delete($previousPhoto);
        }
    }

    public function photo(Equipment $equipment): StreamedResponse
    {
        $disk = Storage::disk('local');

        abort_unless($equipment->photo_path && $disk->exists($equipment->photo_path), 404);

        return $disk->response($equipment->photo_path);
    }
}
