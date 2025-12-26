<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Mengambil Snap Token untuk order ini (API Endpoint).
     * Dipanggil via AJAX dari frontend saat user klik "Bayar".
     */
    public function getSnapToken(Order $order, MidtransService $midtransService)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Pesanan sudah dibayar.'], 400);
        }

        try {
            $snapToken = $midtransService->createSnapToken($order);

            $order->update(['snap_token' => $snapToken]);

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
