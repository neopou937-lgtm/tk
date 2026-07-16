<?php

namespace App\Services\Payments;

use App\Models\Bill;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    /**
     * @return array{snapToken: string|null, redirectUrl: string|null, orderId: string}
     */
    public function createTransaction(Bill $bill, string $customerEmail): array
    {
        if ($bill->status === 'paid') {
            throw new RuntimeException('Tagihan sudah lunas.');
        }

        $serverKey = $this->serverKey();
        $studentName = $bill->student_id ? $bill->student()->value('name') : null;
        $orderId = 'BILL-'.$bill->id.'-'.now()->timestamp;
        $amount = (int) $bill->amount;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Basic '.base64_encode($serverKey.':'),
        ])->post($this->snapUrl(), [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [[
                'id' => (string) $bill->id,
                'price' => $amount,
                'quantity' => 1,
                'name' => str($bill->title)->limit(50, '')->toString(),
            ]],
            'customer_details' => [
                'first_name' => $studentName ?: ($bill->nipd ?: 'Wali Murid'),
                'email' => $customerEmail,
            ],
            'callbacks' => [
                'finish' => config('app.url').'/admin/bills/'.$bill->id.'/edit',
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException($response->json('status_message') ?? 'Gagal membuat transaksi Midtrans.');
        }

        $bill->update([
            'midtrans_order_id' => $orderId,
            'payment_status' => 'pending',
            'payment_gateway' => 'midtrans',
            'snap_token' => $response->json('token'),
            'snap_redirect_url' => $response->json('redirect_url'),
        ]);

        return [
            'snapToken' => $response->json('token'),
            'redirectUrl' => $response->json('redirect_url'),
            'orderId' => $orderId,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleNotification(array $payload): ?Bill
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        if ($orderId === '') {
            return null;
        }

        $bill = Bill::where('midtrans_order_id', $orderId)->first();
        if (! $bill) {
            return null;
        }

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $grossAmount = (float) ($payload['gross_amount'] ?? $bill->amount);
        $paid = in_array($transactionStatus, ['capture', 'settlement'], true)
            && ($transactionStatus !== 'capture' || $fraudStatus === 'accept');

        if ($paid) {
            $bill->payments()->create([
                'amount' => $grossAmount,
                'method' => 'midtrans',
                'status' => 'paid',
                'reference' => $orderId,
                'payload' => $payload,
                'paid_at' => now(),
            ]);

            $bill->update([
                'status' => 'paid',
                'payment_status' => $transactionStatus,
                'paid_amount' => $bill->amount,
                'paid_at' => now(),
            ]);

            return $bill;
        }

        if (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
            $bill->update([
                'payment_status' => $transactionStatus,
            ]);
        }

        return $bill;
    }

    private function serverKey(): string
    {
        $serverKey = (string) config('services.midtrans.server_key', '');
        if ($serverKey === '') {
            throw new RuntimeException('MIDTRANS_SERVER_KEY belum dikonfigurasi.');
        }

        return $serverKey;
    }

    private function snapUrl(): string
    {
        $production = (bool) config('services.midtrans.is_production', false);

        return $production
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }
}
