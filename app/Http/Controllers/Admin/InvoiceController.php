<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoiceController extends Controller
{
    /**
     * Download Invoice as PDF
     */
    public function downloadPDF(Order $order)
    {
        // Eager load items and treatment to ensure data is available for PDF
        $order->load(['items.treatment', 'customer']);

        // Setup Dompdf options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Allow images from remote/absolute paths
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        
        // Render Blade view to HTML
        $html = view('admin.orders.invoice_pdf', compact('order'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream the PDF to the browser with correct headers
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Invoice_' . $order->order_code . '.pdf"');
    }

    /**
     * Public Web View of Invoice (for WA link) - Now returns PDF
     */
    public function publicView($order_code)
    {
        $order = Order::where('order_code', $order_code)->with(['items.treatment', 'customer'])->firstOrFail();
        
        // Setup Dompdf options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        
        // Render Blade view to HTML
        $html = view('admin.orders.invoice_pdf', compact('order'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Invoice_' . $order->order_code . '.pdf"');
    }
}
