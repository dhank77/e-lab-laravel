<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabController extends Controller
{
    public function index(Request $request)
    {
        $labs = Lab::withCount('items')
            ->withSum('items', 'stock')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('labs.index', compact('labs'));
    }

    public function show(Lab $lab)
    {
        $items = $lab->items()->orderBy('name')->get();

        return view('labs.show', compact('lab', 'items'));
    }

    public function create()
    {
        return view('labs.form', ['lab' => new Lab]);
    }

    public function store(Request $request)
    {
        $lab = Lab::create($this->validated($request));

        return redirect()->route('labs.show', $lab)->with('success', 'Lab berhasil ditambahkan.');
    }

    public function edit(Lab $lab)
    {
        return view('labs.form', compact('lab'));
    }

    public function update(Request $request, Lab $lab)
    {
        $lab->update($this->validated($request, $lab));

        return redirect()->route('labs.show', $lab)->with('success', 'Data lab berhasil diperbarui.');
    }

    public function destroy(Lab $lab)
    {
        if ($lab->items()->whereHas('loans', fn ($q) => $q->whereIn('status', ['pending', 'approved']))->exists()) {
            return back()->with('error', 'Lab tidak bisa dihapus karena masih ada barang yang dipinjam / diajukan.');
        }

        $lab->delete();

        return redirect()->route('labs.index')->with('success', 'Lab berhasil dihapus.');
    }

    private function validated(Request $request, ?Lab $lab = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:30', Rule::unique('labs')->ignore($lab)],
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'head' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
