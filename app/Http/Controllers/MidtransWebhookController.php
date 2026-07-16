<?php

namespace App\Http\Controllers;

use App\Services\Payments\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request, MidtransService $midtrans): JsonResponse
    {
        $bill = $midtrans->handleNotification($request->all());

        return response()->json([
            'ok' => true,
            'billId' => $bill?->id,
        ]);
    }
}
