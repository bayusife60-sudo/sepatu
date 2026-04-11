<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $categories = ExpenseCategory::all();
        if ($categories->isEmpty()) return;

        $descriptions = [
            'Bahan Baku' => ['Beli Sabun Sepatu', 'Beli Sikat Premium', 'Beli Cleaner Suede', 'Beli Kantong Plastik'],
            'Gaji Karyawan' => ['Gaji Mingguan Staff Utama', 'Bonus Lembur Staff', 'Gaji Part Time'],
            'Setoran Droppoint' => ['Setoran DP Mall A', 'Setoran DP Kedai Kopi X', 'Setoran DP Kantor Y'],
            'Setoran Partner' => ['Bagi Hasil Partner Z', 'Setoran Franchise'],
            'Lain-lain' => ['Bayar Listrik Bulanan', 'Beli Galon Air', 'Beli Kopi Staff'],
        ];

        for ($i = 0; $i < 20; $i++) {
            $category = $categories->random();
            $catName = $category->name;
            
            $descList = $descriptions[$catName] ?? ['Biaya Operasional'];
            $desc = $descList[array_rand($descList)];
            
            $date = Carbon::now()->subDays(rand(0, 30));
            
            Expense::create([
                'date' => $date->format('Y-m-d'),
                'expense_category_id' => $category->id,
                'description' => $desc,
                'amount' => rand(50000, 1500000),
                'payment_method' => rand(0, 1) ? 'Tunai' : 'Transfer Bank',
                'user_id' => $adminId,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
