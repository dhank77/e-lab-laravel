<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with('lab')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")))
            ->when($request->lab, fn ($q, $lab) => $q->where('lab_id', $lab))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('items.index', [
            'items' => $items,
            'labs' => Lab::orderBy('name')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return view('items.form', [
            'item' => new Item(['lab_id' => $request->lab, 'condition' => 'baik', 'stock' => 1]),
            'labs' => Lab::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $item = Item::create($this->validated($request));

        return redirect()->route('labs.show', $item->lab_id)->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('items.form', [
            'item' => $item,
            'labs' => Lab::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $data = $this->validated($request, $item);

        if ($data['stock'] < $item->borrowedCount()) {
            return back()->withInput()->withErrors(['stock' => 'Stok tidak boleh kurang dari jumlah yang sedang dipinjam ('.$item->borrowedCount().').']);
        }

        $item->update($data);

        return redirect()->route('labs.show', $item->lab_id)->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->loans()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Barang tidak bisa dihapus karena masih dipinjam / diajukan.');
        }

        $labId = $item->lab_id;
        $item->delete();

        return redirect()->route('labs.show', $labId)->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request, ?Item $item = null): array
    {
        return $request->validate([
            'lab_id' => ['required', 'exists:labs,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('items')->ignore($item)],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'condition' => ['required', Rule::in(array_keys(Item::CONDITIONS))],
            'description' => ['nullable', 'string'],
        ]);
    }
}
