<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define categories to be seeded
        $categories = [
            [
                'category_type' => 'Technology',
                'short_description' => 'All about the latest in tech.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_type' => 'Lifestyle',
                'short_description' => 'Tips and stories about living a better life.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into the categories table
        DB::table('categories')->insert($categories);
    }
}
