<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Treatment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function ownerDashboard()
    {
        $data = $this->getDashboardData();
        return view('owner.dashboard', $data);
    }

    public function adminDashboard()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    private function getDashboardData()
    {
        // Get counts for widgets
        $activeOrders = \App\Models\Order::whereNotIn('status', ['Selesai', 'Dibatalkan'])->count();
        $todayPickups = \App\Models\Order::whereDate('pickup_date', today())->count();
        $todayDeliveries = \App\Models\Order::whereDate('estimated_completion', today())->whereIn('status', ['Siap Dikirim', 'Siap Diambil'])->count();

        // --- Data for ApexCharts ---
        
        // 1. Daily Data (Current Month)
        $currentDay = now()->day;
        $dailyLabels = [];
        $dailyRevenue = [];
        $dailyExpenses = [];
        $dailyShoes = [];

        for ($i = 1; $i <= $currentDay; $i++) {
            $date = now()->setDay($i)->format('Y-m-d');
            $dailyLabels[] = $i; // Day number
            
            $rev = \App\Models\Order::where('payment_status', 'lunas')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $dailyRevenue[] = (int)$rev;

            $exp = \App\Models\Expense::whereDate('date', $date)
                ->sum('amount');
            $dailyExpenses[] = (int)$exp;

            $shoes = \App\Models\OrderItem::whereHas('order', function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })->count();
            $dailyShoes[] = $shoes;
        }

        // 2. Monthly Data (Current Year)
        $currentMonth = now()->month;
        $allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyLabels = array_slice($allMonths, 0, $currentMonth);
        $monthlyRevenue = [];
        $monthlyExpenses = [];
        $monthlyShoes = [];

        for ($m = 1; $m <= $currentMonth; $m++) {
            $rev = \App\Models\Order::where('payment_status', 'lunas')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->sum('total_price');
            $monthlyRevenue[] = (int)$rev;

            $exp = \App\Models\Expense::whereYear('date', now()->year)
                ->whereMonth('date', $m)
                ->sum('amount');
            $monthlyExpenses[] = (int)$exp;

            $shoes = \App\Models\OrderItem::whereHas('order', function($q) use ($m) {
                $q->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', $m);
            })->count();
            $monthlyShoes[] = $shoes;
        }

        $chartData = [
            'daily' => [
                'labels' => $dailyLabels,
                'revenue' => $dailyRevenue,
                'expenses' => $dailyExpenses,
                'shoes' => $dailyShoes
            ],
            'monthly' => [
                'labels' => $monthlyLabels,
                'revenue' => $monthlyRevenue,
                'expenses' => $monthlyExpenses,
                'shoes' => $monthlyShoes
            ]
        ];

        // Get recent orders
        $recentOrders = \App\Models\Order::with(['customer', 'items.treatment'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $isOwner = auth()->user()->role === 'owner';

        return [
            'activeOrders' => $activeOrders,
            'todayPickups' => $todayPickups,
            'todayDeliveries' => $todayDeliveries,
            'recentOrders' => $recentOrders,
            'chartData' => [
                'daily' => [
                    'labels' => $dailyLabels,
                    'revenue' => $isOwner ? $dailyRevenue : [],
                    'expenses' => $isOwner ? $dailyExpenses : [],
                    'shoes' => $dailyShoes
                ],
                'monthly' => [
                    'labels' => $monthlyLabels,
                    'revenue' => $isOwner ? $monthlyRevenue : [],
                    'expenses' => $isOwner ? $monthlyExpenses : [],
                    'shoes' => $monthlyShoes
                ]
            ]
        ];
    }

    public function customerDashboard()
    {
        $customerId = auth()->id();

        $totalOrders = \App\Models\Order::where('customer_id', $customerId)->count();

        $activeOrders = \App\Models\Order::where('customer_id', $customerId)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        $completedOrders = \App\Models\Order::where('customer_id', $customerId)
            ->where('status', 'Selesai')
            ->count();

        $unpaidOrders = \App\Models\Order::where('customer_id', $customerId)
            ->where('payment_status', 'belum_lunas')
            ->whereNotIn('status', ['Dibatalkan'])
            ->count();

        $activeOrdersList = \App\Models\Order::with(['items.treatment'])
            ->where('customer_id', $customerId)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->latest()
            ->take(3)
            ->get();

        $orders = \App\Models\Order::with(['items.treatment'])
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(10);

        // Ambill daftar menu layanan / treatment yang aktif
        $services = \App\Models\Treatment::where('is_active', true)->get();

        return view('customer.dashboard', compact(
            'totalOrders', 'activeOrders', 'completedOrders',
            'unpaidOrders', 'activeOrdersList', 'orders', 'services'
        ));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            "items"             => "required|array|min:1",
            "items.*.brand"    => "required|string|max:100",
            "items.*.material" => "required|string|max:100",
            "items.*.color"    => "required|string|max:100",
            "items.*.treatment_id" => "required|exists:treatments,id",
            "items.*.photo"    => "nullable|image|max:2048",
            "service_method"    => "required|string|in:datang_langsung,pickup_delivery",
            "latitude"          => "nullable|required_if:service_method,pickup_delivery|numeric",
            "longitude"         => "nullable|required_if:service_method,pickup_delivery|numeric",
            "pickup_date"       => "nullable|required_if:service_method,pickup_delivery|date",
            "pickup_time"       => "nullable|required_if:service_method,pickup_delivery",
        ]);

        $customer = auth()->user();
        
        $totalTreatmentPrice = 0;
        $orderItemsData = [];

        foreach ($validated['items'] as $index => $item) {
            $treatment = Treatment::findOrFail($item['treatment_id']);
            $totalTreatmentPrice += $treatment->price;
            
            $photoPath = null;
            if ($request->hasFile("items.{$index}.photo")) {
                $file = $request->file("items.{$index}.photo");
                $filename = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/orders'), $filename);
                $photoPath = 'uploads/orders/' . $filename;
            }

            $orderItemsData[] = [
                'shoe_brand'    => $item['brand'],
                'shoe_material' => $item['material'],
                'shoe_color'    => $item['color'],
                'treatment_id'  => $treatment->id,
                'price'         => $treatment->price,
                'photo_before'  => $photoPath,
            ];
        }

        $serviceFee = 0;
        $distance = 0;

        if ($validated["service_method"] === "pickup_delivery") {
            $storeLat = env('STORE_LATITUDE', -6.20000000);
            $storeLng = env('STORE_LONGITUDE', 106.81660000);
            $custLat = $validated["latitude"];
            $custLng = $validated["longitude"];

            // Calculate distance using Haversine formula
            $earthRadius = 6371; // km
            $dLat = deg2rad($custLat - $storeLat);
            $dLon = deg2rad($custLng - $storeLng);
            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($storeLat)) * cos(deg2rad($custLat)) *
                sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            // Pricing logic: Free if < 5km, else 10k per km
            if ($distance >= 5) {
                $serviceFee = round($distance * 10000);
            }
        }

        $totalPrice = $totalTreatmentPrice + $serviceFee;

        // Generate unique order code
        do {
            $orderCode = "CLZ-" . strtoupper(Str::random(8));
        } while (Order::where("order_code", $orderCode)->exists());

        DB::beginTransaction();
        try {
            $order = Order::create([
                "order_code"           => $orderCode,
                "customer_id"          => $customer->id,
                "customer_name"        => $customer->name,
                "service_method"       => $validated["service_method"],
                "latitude"             => $validated["latitude"] ?? null,
                "longitude"            => $validated["longitude"] ?? null,
                "distance"             => $distance,
                "pickup_date"          => $validated["pickup_date"] ?? null,
                "pickup_time"          => $validated["pickup_time"] ?? null,
                "pickup_fee"           => $serviceFee,
                "total_price"          => $totalPrice,
                "status"               => "Antrian",
                "payment_status"       => "belum_lunas",
                "estimated_completion" => now()->addDays(3),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }

        return redirect()->route("customer.dashboard")
            ->with("success", "Pesanan Anda berhasil dibuat! Kode Order: " . $orderCode)
            ->with("new_order_id", $order->id)
            ->with("new_order_total", $totalPrice);
    }

    public function uploadPayment(Request $request, Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'pay_' . $order->order_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);

            $order->update([
                'payment_proof' => 'uploads/payments/' . $filename,
                'payment_method' => 'Transfer Bank',
                'status' => 'Menunggu Konfirmasi'
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
}
