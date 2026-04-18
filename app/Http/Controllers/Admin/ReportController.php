<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Exports\ProfitLossExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    /**
     * Generate Profit & Loss PDF Report
     */
    public function profitLossPDF(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized action. Only owner can access financial reports.');
        }
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Fetch Orders (Revenue)
        $orders = Order::with(['items.treatment', 'customer'])
            ->where('payment_status', 'lunas')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        // Fetch Expenses (Expenditure)
        $expenses = Expense::with(['category', 'user'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        // Setup Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        
        $html = view('admin.reports.profit_loss_pdf', compact(
            'orders', 
            'expenses', 
            'totalRevenue', 
            'totalExpenses', 
            'netProfit', 
            'startDate', 
            'endDate'
        ))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Keuangan_' . $startDate . '_to_' . $endDate . '.pdf';

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generate Profit & Loss Excel Report
     */
    public function profitLossExcel(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $orders = Order::with(['items.treatment', 'customer'])
            ->where('payment_status', 'lunas')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $expenses = Expense::with(['category', 'user'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $data = [
            'orders' => $orders,
            'expenses' => $expenses,
            'totalRevenue' => $orders->sum('total_price'),
            'totalExpenses' => $expenses->sum('amount'),
            'netProfit' => $orders->sum('total_price') - $expenses->sum('amount'),
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return Excel::download(new ProfitLossExport($data), 'Laporan_Keuangan_' . $startDate . '_to_' . $endDate . '.xlsx');
    }
}
