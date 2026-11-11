<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    /** Riwayat peminjaman milik user yang login. */
    public function index(Request $request)
    {
        $loans = $request->user()->loans()
            ->with('item.lab')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create(Item $item)
    {
        $item->load('lab');

        return view('loans.create', compact('item'));
    }

    public function store(Request $request, Item $item)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'loan_date' => ['required', 'date', 'after_or_equal:today'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'purpose' => ['required', 'string', 'max:255'],
        ]);

        if ($data['quantity'] > $item->availableStock()) {
            return back()->withInput()->withErrors(['quantity' => 'Jumlah melebihi stok tersedia ('.$item->availableStock().').']);
        }

        $request->user()->loans()->create($data + ['item_id' => $item->id, 'status' => 'pending']);

        return redirect()->route('loans.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Tunggu persetujuan admin.');
    }

    /** User membatalkan pengajuan yang masih menunggu. */
    public function cancel(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === $request->user()->id, 403);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang bisa dibatalkan.');
        }

        $loan->delete();

        return back()->with('success', 'Pengajuan peminjaman dibatalkan.');
    }

    /* ---------------------------- Admin ---------------------------- */

    public function manage(Request $request)
    {
        $loans = Loan::with(['user', 'item.lab'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%")->orWhere('nim', 'like', "%{$s}%"))
                ->orWhereHas('item', fn ($i) => $i->where('name', 'like', "%{$s}%"))))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('loans.manage', compact('loans'));
    }

    public function updateStatus(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['approve', 'reject', 'return'])],
            'admin_note' => ['nullable', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($loan, $data) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();
            $note = $data['admin_note'] ?? $loan->admin_note;

            switch ($data['action']) {
                case 'approve':
                    if ($loan->status !== 'pending') {
                        return back()->with('error', 'Pengajuan ini sudah diproses.');
                    }
                    $item = Item::whereKey($loan->item_id)->lockForUpdate()->firstOrFail();
                    if ($loan->quantity > $item->availableStock()) {
                        return back()->with('error', 'Stok tidak mencukupi untuk menyetujui pengajuan ini.');
                    }
                    $loan->update(['status' => 'approved', 'admin_note' => $note]);

                    return back()->with('success', 'Peminjaman disetujui.');

                case 'reject':
                    if ($loan->status !== 'pending') {
                        return back()->with('error', 'Pengajuan ini sudah diproses.');
                    }
                    $loan->update(['status' => 'rejected', 'admin_note' => $note]);

                    return back()->with('success', 'Peminjaman ditolak.');

                case 'return':
                    if ($loan->status !== 'approved') {
                        return back()->with('error', 'Hanya peminjaman aktif yang bisa dikembalikan.');
                    }
                    $loan->update(['status' => 'returned', 'returned_at' => now(), 'admin_note' => $note]);

                    return back()->with('success', 'Barang telah ditandai dikembalikan.');
            }
        });
    }
}
