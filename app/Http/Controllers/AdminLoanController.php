<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\LoanRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminLoanController extends Controller
{
    public function index(): View
    {
        return view('petugas.loans', [
            'history' => false,
            'loans' => LoanRequest::with('borrower')->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
                ->latest('id')->paginate(15),
        ]);
    }

    public function history(): View
    {
        return view('petugas.loans', [
            'history' => true,
            'loans' => LoanRequest::with('borrower')->whereIn('status', ['dipinjam', 'selesai', 'ditolak', 'dibatalkan'])
                ->latest('updated_at')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function process(Request $request, LoanRequest $loan): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,reject,handover,return'],
            'reason' => ['nullable', 'required_if:action,reject', 'string', 'max:500'],
            'condition' => ['nullable', 'required_if:action,return', 'in:Baik,Buruk'],
        ], [
            'reason.required_if' => 'Masukkan alasan penolakan.',
            'reason.max' => 'Alasan penolakan maksimal 500 karakter.',
            'condition.required_if' => 'Pilih kondisi alat saat kembali.',
            'condition.in' => 'Pilih kondisi Baik atau Buruk.',
        ]);

        $message = DB::transaction(function () use ($loan, $data) {
            $loan = LoanRequest::query()->lockForUpdate()->findOrFail($loan->id);

            return match ($data['action']) {
                'approve' => $this->approve($loan),
                'reject' => $this->reject($loan, $data['reason']),
                'handover' => $this->handover($loan),
                'return' => $this->recordReturn($loan, $data['condition']),
            };
        }, 3);

        return to_route('petugas.requests')->with('status', $message);
    }

    private function approve(LoanRequest $loan): string
    {
        $this->requireStatus($loan, 'menunggu');
        $this->availableEquipment($loan);
        $loan->status = 'disetujui';
        $loan->save();

        return 'Pengajuan disetujui.';
    }

    private function reject(LoanRequest $loan, string $reason): string
    {
        $this->requireStatus($loan, 'menunggu', 'disetujui');
        $loan->status = 'ditolak';
        $loan->rejection_reason = $reason;
        $loan->save();

        return 'Pengajuan ditolak.';
    }

    private function handover(LoanRequest $loan): string
    {
        $this->requireStatus($loan, 'disetujui');
        $equipment = $this->availableEquipment($loan);
        $equipment->decrement('available_quantity', $loan->quantity);
        $loan->status = 'dipinjam';
        $loan->handed_over_at = now();
        $loan->save();

        return 'Penyerahan alat dicatat dan stok diperbarui.';
    }

    private function recordReturn(LoanRequest $loan, string $condition): string
    {
        $this->requireStatus($loan, 'dipinjam');
        $equipment = Equipment::query()->lockForUpdate()->find($loan->equipment_id);

        if (! $equipment || $equipment->available_quantity > 2147483647 - $loan->quantity) {
            throw ValidationException::withMessages(['action' => 'Data alat atau stok tidak dapat diperbarui. Periksa Data Lab.']);
        }

        $equipment->available_quantity += $loan->quantity;
        if ($condition === 'Buruk') {
            $equipment->condition = 'Buruk';
        }
        $equipment->save();

        $loan->status = 'selesai';
        $loan->return_condition = $condition;
        $loan->returned_at = now();
        $loan->save();

        return 'Pengembalian alat dicatat dan stok diperbarui.';
    }

    private function availableEquipment(LoanRequest $loan): Equipment
    {
        $equipment = Equipment::query()->lockForUpdate()->find($loan->equipment_id);

        if (! $equipment || $equipment->condition !== 'Baik' || $equipment->available_quantity < $loan->quantity) {
            throw ValidationException::withMessages(['action' => 'Alat atau jumlah yang diminta tidak tersedia.']);
        }

        return $equipment;
    }

    private function requireStatus(LoanRequest $loan, string ...$statuses): void
    {
        if (! in_array($loan->status, $statuses, true)) {
            throw ValidationException::withMessages(['action' => 'Status pengajuan sudah berubah. Muat ulang halaman sebelum mencoba lagi.']);
        }
    }
}
