<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(["customer", "treatment"])->latest();

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("order_code", "like", "%{$search}%")
                  ->orWhere("customer_name", "like", "%{$search}%")
                  ->orWhere("shoe_brand", "like", "%{$search}%")
                  ->orWhereHas("customer", function ($q2) use ($search) {
                      $q2->where("name", "like", "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $allStatuses = [
            "pending", "pickup_scheduled", "picked_up", "in_queue",
            "cleaning_in_progress", "quality_check", "ready_for_delivery",
            "delivery_in_progress", "completed", "cancelled",
        ];

        return view("admin.orders.index", compact("orders", "allStatuses"));
    }

    public function show(Order $order)
    {
        $order->load(["customer", "treatment"]);
        $allStatuses = [
            "pending", "pickup_scheduled", "picked_up", "in_queue",
            "cleaning_in_progress", "quality_check", "ready_for_delivery",
            "delivery_in_progress", "completed", "cancelled",
        ];
        return view("admin.orders.show", compact("order", "allStatuses"));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            "status" => "required|in:pending,pickup_scheduled,picked_up,in_queue,cleaning_in_progress,quality_check,ready_for_delivery,delivery_in_progress,completed,cancelled",
        ]);

        $order->update(["status" => $request->status]);

        return redirect()->back()
            ->with("success", "Status order #" . $order->order_code . " berhasil diperbarui!");
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
            "customer_id"          => "required|exists:users,id",
            "customer_name"        => "nullable|string|max:150",
            "shoe_type"            => "required|string|max:100",
            "shoe_brand"           => "required|string|max:100",
            "treatment_id"         => "required|exists:treatments,id",
            "service_method"       => "required|string|in:datang_langsung,pickup,delivery",
            "pickup_address"       => "nullable|required_if:service_method,pickup|string|max:500",
            "pickup_date"          => "nullable|required_if:service_method,pickup|date",
            "delivery_address"     => "nullable|required_if:service_method,delivery|string|max:500",
            "delivery_date"        => "nullable|required_if:service_method,delivery|date",
            "price"                => "required|numeric|min:0",
            "estimated_completion" => "nullable|date",
            "payment_method"       => "nullable|string|max:100",
            "payment_status"       => "required|in:lunas,belum_lunas",
        ]);

        $treatment = Treatment::findOrFail($validated["treatment_id"]);

        $price       = (float) $validated["price"];
        $pickupFee   = ($validated["service_method"] === "pickup")   ? 40000 : 0;
        $deliveryFee = ($validated["service_method"] === "delivery") ? 40000 : 0;
        $totalPrice  = $price + $pickupFee + $deliveryFee;

        $customerName = !empty($validated["customer_name"])
            ? $validated["customer_name"]
            : (User::find($validated["customer_id"])->name ?? "");

        do {
            $orderCode = "CLZ-" . strtoupper(Str::random(8));
        } while (Order::where("order_code", $orderCode)->exists());

        Order::create([
            "order_code"           => $orderCode,
            "customer_id"          => $validated["customer_id"],
            "customer_name"        => $customerName,
            "shoe_type"            => $validated["shoe_type"],
            "shoe_brand"           => $validated["shoe_brand"],
            "treatment_id"         => $treatment->id,
            "service_method"       => $validated["service_method"],
            "pickup_address"       => $validated["pickup_address"] ?? null,
            "pickup_date"          => $validated["pickup_date"] ?? null,
            "delivery_address"     => $validated["delivery_address"] ?? null,
            "delivery_date"        => $validated["delivery_date"] ?? null,
            "pickup_fee"           => $pickupFee,
            "delivery_fee"         => $deliveryFee,
            "price"                => $price,
            "total_price"          => $totalPrice,
            "status"               => "pending",
            "payment_method"       => $validated["payment_method"] ?? null,
            "payment_status"       => $validated["payment_status"],
            "estimated_completion" => $validated["estimated_completion"] ?? null,
        ]);

        return redirect()->route("admin.orders.index")
            ->with("success", "Order baru berhasil dibuat dengan kode " . $orderCode . "!");
    }
}