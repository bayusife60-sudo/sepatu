<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    public function createInvoice(Order $order)
    {
        // Pastikan order milik user yang sedang login
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        // Jika sudah ada link pembayaran, arahkan ke sana
        if ($order->payment_link) {
            return redirect($order->payment_link);
        }

        $apiInstance = new InvoiceApi();
        
        $external_id = $order->order_code . '-' . time();
        
        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => $external_id,
            'amount' => (float) $order->total_price,
            'payer_email' => auth()->user()->email,
            'description' => 'Pembayaran Cuci Sepatu Cleansetz - ' . $order->order_code,
            'invoice_duration' => 86400, // 24 jam
            'success_redirect_url' => route('customer.dashboard'),
            'failure_redirect_url' => route('customer.dashboard'),
            'currency' => 'IDR',
        ]);

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            
            $order->update([
                'xendit_invoice_id' => $result['id'],
                'xendit_external_id' => $external_id,
                'payment_link' => $result['invoice_url']
            ]);

            return redirect($result['invoice_url']);
        } catch (\Xendit\XenditSdkException $e) {
            Log::error('Xendit Error: ' . $e->getFullError());
            return back()->with('error', 'Gagal membuat invoice pembayaran: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('General Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function handleWebhook(Request $request)
    {
        // Log payload untuk debugging (bisa dilihat di storage/logs/laravel.log)
        Log::info('Xendit Webhook Received: ', $request->all());

        $callbackToken = config('services.xendit.callback_token');
        $xIncomingToken = $request->header('x-callback-token');

        if ($callbackToken !== $xIncomingToken) {
            Log::warning('Xendit Webhook Invalid Token');
            return response()->json(['message' => 'Invalid token'], 403);
        }

        $external_id = $request->external_id;
       
        $status = $request->status;

        
            $order = Order::where('xendit_external_id', $external_id)->first();

            if ($order) {
        
                    $order->update([
                        'payment_status' => 'lunas',
                        'status' => 'Antrian',
                        'payment_method' => $request->payment_method ?? 'Xendit',
                        'payment_date' => now(),
                        'payment_proof' => 'PAID_VIA_XENDIT'
                    ]);
                    Log::info('Order Paid Successfully: ' . $external_id);
            
            } else {
                Log::warning('Order Not Found for Webhook: ' . $external_id);
            }
        
        return response()->json([
            'message' => 'Callback processed successfully',
            'external_id' => $external_id
        ]);
    }
}
