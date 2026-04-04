<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Tampilkan daftar pengeluaran.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'user'])->latest('date');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                }
                );
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        // Filter berdasarkan bulan (format: YYYY-MM)
        if ($request->filled('month')) {
            $month = substr($request->month, 5, 2);
            $year = substr($request->month, 0, 4);
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }

        $expenses = $query->paginate(15)->withQueryString();
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Tampilkan form tambah pengeluaran.
     */
    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('admin.expenses.create', compact('categories'));
    }

    /**
     * Simpan pengeluaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
        ]);

        $validated['user_id'] = auth()->id() ?? 1; // Fallback ke 1 jika via seeder/testing

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Data pengeluaran berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit pengeluaran.
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update pengeluaran.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
        ]);

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    /**
     * Hapus pengeluaran.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}
