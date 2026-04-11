<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Treatment::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $treatments = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.treatments.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role !== 'owner') {
            return redirect()->route('admin.treatments.index')
                ->with('error', 'Hanya Owner yang dapat menambah layanan treatment.');
        }
        return view('admin.treatments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:treatments,name',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        \App\Models\Treatment::create($validated);

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Layanan Treatment berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Treatment $treatment)
    {
        if (auth()->user()->role !== 'owner') {
            return redirect()->route('admin.treatments.index')
                ->with('error', 'Hanya Owner yang dapat mengubah layanan treatment.');
        }
        return view('admin.treatments.edit', compact('treatment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Treatment $treatment)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:treatments,name,' . $treatment->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $treatment->update($validated);

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Layanan Treatment berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Treatment $treatment)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized action.');
        }

        // Check if treatment is used in any orders to prevent orphaned records
        if (\App\Models\Order::where('id', '>', 0)->whereHas('items', function($q) use ($treatment) {
            $q->where('treatment_id', $treatment->id);
        })->exists()) {
            return redirect()->route('admin.treatments.index')
                ->with('error', 'Gagal dihapus! Treatment ini sedang digunakan pada data order.');
        }

        $treatment->delete();

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Layanan Treatment berhasil dihapus!');
    }
}
