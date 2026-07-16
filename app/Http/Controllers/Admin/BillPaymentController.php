<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Services\Payments\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use RuntimeException;

class BillPaymentController extends Controller
{
    public function createMidtransTransaction(Bill $bill, MidtransService $midtrans): JsonResponse
    {
        try {
            return response()->json($midtrans->createTransaction(
                $bill,
                (string) request()->user()?->email,
            ));
        } catch (RuntimeException $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    public function markPaid(Request $request, Bill $bill): RedirectResponse
    {
        $amount = (float) $request->input('amount', $bill->amount);

        $bill->payments()->create([
            'amount' => $amount,
            'method' => 'manual',
            'status' => 'paid',
            'reference' => 'MANUAL-'.$bill->id.'-'.now()->timestamp,
            'paid_at' => now(),
        ]);

        $bill->update([
            'status' => 'paid',
            'paid_amount' => $amount,
            'payment_status' => 'manual_paid',
            'payment_gateway' => 'manual',
            'paid_at' => now(),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Tagihan berhasil ditandai lunas.',
        ]);

        return redirect('/admin/bills');
    }
}
