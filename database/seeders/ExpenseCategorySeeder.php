<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Bahan Baku'],
            ['name' => 'Gaji Karyawan'],
            ['name' => 'Setoran Droppoint'],
            ['name' => 'Setoran Partner'],
            ['name' => 'Lain-lain'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::updateOrCreate(
                ['name' => $category['name']],
                ['name' => $category['name']]
            );
        }
    }
}
