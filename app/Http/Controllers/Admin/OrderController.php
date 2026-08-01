<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Treatment;
use App\Models\User;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(["customer", "items.treatment"])->latest();

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("order_code", "like", "%{$search}%")
                  ->orWhere("customer_name", "like", "%{$search}%")
                  ->orWhereHas("customer", function ($q2) use ($search) {
                      $q2->where("name", "like", "%{$search}%");
                  });
            });
        }

        // Filter by Customer
        if ($request->filled("customer_id")) {
            $query->where("customer_id", $request->customer_id);
        }

        // Filter by Date Range
        if ($request->filled("start_date")) {
            $query->whereDate("created_at", ">=", $request->start_date);
        }
        if ($request->filled("end_date")) {
            $query->whereDate("created_at", "<=", $request->end_date);
        }

        $orders = $query->paginate(15)->withQueryString();
        $customers = User::where("role", "customer")->orderBy("name")->get();

        $allStatuses = [
            "Antrian", "Menunggu Konfirmasi", "Diterima Toko", 
            "Dikerjakan", "Siap Diambil", "Siap Dikirim", 
            "Selesai", "Dibatalkan"
        ];

        return view("admin.orders.index", compact("orders", "allStatuses", "customers"));
    }

    /**
     * Export Filtered Orders to PDF
     */
    public function exportPDF(Request $request)
    {
        $query = Order::with(["customer", "items.treatment"])->latest();

        // Apply same filters as index
        if ($request->filled("status")) $query->where("status", $request->status);
        if ($request->filled("customer_id")) $query->where("customer_id", $request->customer_id);
        if ($request->filled("start_date")) $query->whereDate("created_at", ">=", $request->start_date);
        if ($request->filled("end_date")) $query->whereDate("created_at", "<=", $request->end_date);
        
        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("order_code", "like", "%{$search}%")
                  ->orWhere("customer_name", "like", "%{$search}%")
                  ->orWhereHas("customer", function ($q2) use ($search) {
                      $q2->where("name", "like", "%{$search}%");
                  });
            });
        }

        $orders = $query->get();
        $dateRange = "";
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $dateRange = ($request->start_date ?? '...') . " s/d " . ($request->end_date ?? '...');
        }

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('admin.orders.report_pdf', compact('orders', 'dateRange'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Laporan_Order_' . now()->format('Ymd') . '.pdf"');
    }

    /**
     * Export Filtered Orders to Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new OrdersExport($request), 'Laporan_Order_' . now()->format('Ymd') . '.xlsx');
    }

    public function show(Order $order)
    {
        $order->load(["customer", "items.treatment"]);
        $allStatuses = [
            "Antrian", "Menunggu Konfirmasi", "Diterima Toko", 
            "Dikerjakan", "Siap Diambil", "Siap Dikirim", 
            "Selesai", "Dibatalkan"
        ];
        return view("admin.orders.show", compact("order", "allStatuses"));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            "status" => "required|string",
        ]);

        $order->update(["status" => $request->status]);

        return redirect()->back()
            ->with("success", "Status order #" . $order->order_code . " berhasil diperbarui!");
    }

    public function confirmPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'lunas',
            'payment_date' => now(),
            'status' => 'Diterima Toko' // Automatically move to next status after payment confirmed
        ]);

        return redirect()->back()
            ->with("success", "Pembayaran untuk order #" . $order->order_code . " telah dikonfirmasi!");
    }

    public function create()
    {
        $customers  = User::where("role", "customer")->orderBy("name")->get();
        $treatments = Treatment::where('is_active', true)->get();

        return view("admin.orders.create", compact("customers", "treatments"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "is_new_customer"      => "nullable|boolean",
            "customer_id"          => "required_without:is_new_customer|nullable|exists:users,id",
            "new_customer_email"   => "required_if:is_new_customer,1|nullable|email|unique:users,email",
            "customer_name"        => "required_if:is_new_customer,1|nullable|string|max:150",
            "customer_phone"       => "required_if:is_new_customer,1|nullable|string|max:20",
            "service_method"       => "required|in:datang_langsung,pickup_delivery",
            "pickup_address"       => "nullable|string|max:500",
            "pickup_date"          => "nullable|date",
            "estimated_completion" => "nullable|date",
            "payment_method"       => "nullable|string|max:100",
            "payment_status"       => "required|in:lunas,belum_lunas",
            "items"                => "required|array|min:1",
            "items.*.shoe_brand"   => "required|string",
            "items.*.shoe_material"=> "required|string",
            "items.*.shoe_color"   => "required|string",
            "items.*.treatment_id" => "required|exists:treatments,id",
            "items.*.price"        => "required|numeric|min:0",
        ]);

        try {
            return \DB::transaction(function () use ($validated, $request) {
                // Generate Order Code
                do {
                    $orderCode = "CLZ-" . strtoupper(\Str::random(8));
                } while (Order::where("order_code", $orderCode)->exists());

                $customerId = $validated["customer_id"] ?? null;
                $user = null;

                if (!empty($validated["is_new_customer"])) {
                    $user = User::create([
                        'name' => $validated['customer_name'],
                        'email' => $validated['new_customer_email'],
                        'password' => bcrypt('password123'), // Default password
                        'phone' => $validated['customer_phone'],
                        'role' => 'customer',
                    ]);
                    $customerId = $user->id;
                } else if ($customerId) {
                    $user = User::find($customerId);
                }

                $customerName = !empty($validated["customer_name"])
                    ? $validated["customer_name"]
                    : ($user->name ?? "");

                $customerPhone = !empty($validated["customer_phone"])
                    ? $validated["customer_phone"]
                    : ($user->phone ?? null);

                $pickupFee = ($validated["service_method"] === "pickup_delivery") ? 40000 : 0;
                $itemsPrice = collect($request->items)->sum('price');
                $totalPrice = $itemsPrice + $pickupFee;

                $order = Order::create([
                    "order_code"           => $orderCode,
                    "customer_id"          => $customerId,
                    "customer_name"        => $customerName,
                    "customer_phone"       => $customerPhone,
                    "service_method"       => $validated["service_method"],
                    "pickup_address"       => $validated["pickup_address"] ?? null,
                    "pickup_date"          => $validated["pickup_date"] ?? null,
                    "pickup_fee"           => $pickupFee,
                    "total_price"          => $totalPrice,
                    "status"               => "Antrian",
                    "payment_method"       => $validated["payment_method"] ?? null,
                    "payment_status"       => $validated["payment_status"],
                    "estimated_completion" => $validated["estimated_completion"] ?? null,
                ]);

                foreach ($request->items as $item) {
                    $order->items()->create([
                        'shoe_brand'    => $item['shoe_brand'],
                        'shoe_material' => $item['shoe_material'],
                        'shoe_color'    => $item['shoe_color'],
                        'treatment_id'  => $item['treatment_id'],
                        'price'         => $item['price'],
                    ]);
                }

                return redirect()->route("admin.orders.index")
                    ->with("success", "Order #" . $orderCode . " berhasil dibuat!");
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan order: ' . $e->getMessage());
        }
    }
}