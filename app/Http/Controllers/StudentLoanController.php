<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\LoanRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class StudentLoanController extends Controller
{
    public function catalog(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();
        $category = $request->string('category')->toString();
        $available = $request->boolean('available');

        return view('mahasiswa.catalog', [
            'items' => Equipment::query()->orderBy('name')->orderBy('id')
                ->when($search !== '', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                ->when($category !== '', fn ($query) => $query->where('category', $category))
                ->when($available, fn ($query) => $query->where('condition', 'Baik')->where('available_quantity', '>', 0))
                ->paginate(12)->withQueryString(),
            'categories' => Equipment::query()->where('category', '!=', 'Tanpa kategori')
                ->distinct()->orderBy('category')->pluck('category'),
            'search' => $search,
            'category' => $category,
            'available' => $available,
            'loanOptions' => $this->loanOptions(),
        ]);
    }

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $loans = LoanRequest::query()->where('user_id', $request->user()->id);
        $counts = (clone $loans)->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('mahasiswa.requests', [
            'loans' => $loans->when(isset(LoanRequest::STATUSES[$status]), fn ($query) => $query->where('status', $status))
                ->latest('id')->paginate(12)->withQueryString(),
            'status' => $status,
            'statuses' => LoanRequest::STATUSES,
            'counts' => $counts,
            'loanOptions' => $this->loanOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'equipment_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:2147483647'],
            'borrow_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'return_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:borrow_date'],
            'purpose' => ['nullable', 'string', 'max:1000'],
        ], [
            'equipment_id.*' => 'Pilih barang yang ingin dipinjam.',
            'quantity.*' => 'Masukkan jumlah pinjam yang valid.',
            'borrow_date.*' => 'Pilih tanggal pinjam mulai hari ini.',
            'return_date.*' => 'Pilih tanggal kembali pada atau setelah tanggal pinjam.',
            'purpose.max' => 'Keperluan maksimal 1.000 karakter.',
        ]);

        if (Carbon::parse($data['borrow_date'])->addDays(30)->toDateString() < $data['return_date']) {
            throw ValidationException::withMessages(['return_date' => 'Masa pinjam maksimal 30 hari.']);
        }

        $equipment = Equipment::find($data['equipment_id']);
        if (! $equipment || $equipment->condition !== 'Baik' || $equipment->available_quantity < $data['quantity']) {
            throw ValidationException::withMessages(['quantity' => 'Barang atau jumlah yang diminta tidak tersedia.']);
        }

        LoanRequest::create($data + [
            'user_id' => $request->user()->id,
            'equipment_name' => $equipment->name,
        ]);

        return to_route('mahasiswa.requests')->with('status', 'Pengajuan berhasil dikirim.');
    }

    public function cancel(Request $request, LoanRequest $loan): RedirectResponse
    {
        abort_unless($loan->user_id === $request->user()->id, 404);

        $cancelled = LoanRequest::query()->whereKey($loan->id)->where('status', 'menunggu')
            ->update(['status' => 'dibatalkan']);

        return to_route('mahasiswa.requests')->with('status', $cancelled
            ? 'Pengajuan dibatalkan.'
            : 'Hanya pengajuan yang masih menunggu yang dapat dibatalkan.');
    }

    private function loanOptions()
    {
        return Equipment::query()->where('condition', 'Baik')->where('available_quantity', '>', 0)
            ->orderBy('name')->get(['id', 'name', 'available_quantity']);
    }
}
