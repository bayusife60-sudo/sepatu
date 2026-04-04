<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceChangeRequest;
use App\Models\Treatment;
use Illuminate\Http\Request;

class PriceRequestController extends Controller
{
    /**
     * Tampilkan daftar request harga.
     */
    public function index(Request $request)
    {
        $query = PriceChangeRequest::with(['treatment', 'admin'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $priceRequests = $query->paginate(15)->withQueryString();

        return view('admin.price-requests.index', compact('priceRequests'));
    }

    /**
     * Tampilkan form buat request harga baru.
     */
    public function create()
    {
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();
        return view('admin.price-requests.create', compact('treatments'));
    }

    /**
     * Simpan request harga baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'treatment_id' => 'required|exists:treatments,id',
            'new_price' => 'required|numeric|min:0',
            'reason' => 'required|string|max:1000',
        ]);

        $treatment = Treatment::findOrFail($validated['treatment_id']);

        $oldPrice = $treatment->price;
        $newPrice = $validated['new_price'];
        $difference = $newPrice - $oldPrice;

        PriceChangeRequest::create([
            'treatment_id' => $treatment->id,
            'admin_id' => auth()->id() ?? 1, // Fallback ke 1
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'difference' => $difference,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('admin.price-requests.index')
            ->with('success', 'Request perubahan harga berhasil diajukan!');
    }

    /**
     * Tampilkan detail request harga.
     */
    public function show(PriceChangeRequest $priceRequest)
    {
        $priceRequest->load(['treatment', 'admin']);
        return view('admin.price-requests.show', compact('priceRequest'));
    }

    /**
     * Hapus request harga (hanya jika pending).
     */
    public function destroy(PriceChangeRequest $priceRequest)
    {
        if ($priceRequest->status !== 'pending') {
            return redirect()->route('admin.price-requests.index')
                ->with('error', 'Hanya request dengan status pending yang dapat dihapus!');
        }

        $priceRequest->delete();

        return redirect()->route('admin.price-requests.index')
            ->with('success', 'Request perubahan harga dibatalkan/dihapus.');
    }
}
