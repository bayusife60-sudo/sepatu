<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function ownerDashboard()
    {
        return view('owner.dashboard');
    }

    public function adminDashboard()
    {
        // Get counts for widgets
        $activeOrders = \App\Models\Order::whereNotIn('status', ['completed', 'cancelled'])->count();
        $todayPickups = \App\Models\Order::whereDate('pickup_date', today())->count();
        $todayDeliveries = \App\Models\Order::whereDate('estimated_completion', today())->where('status', 'ready_for_delivery')->count();

        // Get recent orders
        $recentOrders = \App\Models\Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('activeOrders', 'todayPickups', 'todayDeliveries', 'recentOrders'));
    }

    public function customerDashboard()
    {
        $customerId = auth()->id();

        $totalOrders = \App\Models\Order::where('customer_id', $customerId)->count();

        $activeOrders = \App\Models\Order::where('customer_id', $customerId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $completedOrders = \App\Models\Order::where('customer_id', $customerId)
            ->where('status', 'completed')
            ->count();

        $unpaidOrders = \App\Models\Order::where('customer_id', $customerId)
            ->where('payment_status', 'belum_lunas')
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $activeOrdersList = \App\Models\Order::with('treatment')
            ->where('customer_id', $customerId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->take(3)
            ->get();

        $orders = \App\Models\Order::with('treatment')
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
}
