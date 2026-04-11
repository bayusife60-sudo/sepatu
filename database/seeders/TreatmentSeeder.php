<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing treatments to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Treatment::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $treatments = [
            [
                'name' => 'FAST CLEAN',
                'description' => 'Optimal for quick maintenance. Includes cleaning of: Upper, Midsole, Outsole, and Parfume.',
                'price' => 35000,
                'estimated_time' => '1-2 Days',
                'is_active' => true,
            ],
            [
                'name' => 'DEEP CLEAN',
                'description' => 'Comprehensive cleaning for all shoe parts. Includes: Upper, Midsole, Outsole, Insole, Detailing, and Parfume.',
                'price' => 50000,
                'estimated_time' => '2-3 Days',
                'is_active' => true,
            ],
            [
                'name' => 'PREMIUM TREATMENT',
                'description' => 'Special care for delicate materials. Includes: Whitening, Suede, Leather, Nubbuck, Detailing, and Parfume.',
                'price' => 65000,
                'estimated_time' => '3-4 Days',
                'is_active' => true,
            ],
            [
                'name' => 'UNYELLOWING',
                'description' => 'Specialized treatment to remove oxidation from soles. Includes: Midsole whitening and Cleaning.',
                'price' => 100000,
                'estimated_time' => '4-5 Days',
                'is_active' => true,
            ],
            [
                'name' => 'REPAINT',
                'description' => 'Restore the original color or change it. Includes: ReColour Upper/midsole, and Cleaning.',
                'price' => 150000,
                'estimated_time' => '5-7 Days',
                'is_active' => true,
            ],
            [
                'name' => 'REGLUE',
                'description' => 'Professional adhesive restoration. Includes: Reglue pressing sole, Swapsole, and Cleaning.',
                'price' => 150000,
                'estimated_time' => '3-5 Days',
                'is_active' => true,
            ],
        ];

        foreach ($treatments as $treatment) {
            Treatment::create($treatment);
        }
    }
}
