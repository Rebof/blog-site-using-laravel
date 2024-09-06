<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade


class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Replace these with actual user IDs and category IDs if they exist
                $userId = 1; // Example user ID
                $categoryId = 1; // Example category ID
        
                $blogs = [
                    [
                        'user_id' => $userId,
                        'category_id' => $categoryId,
                        'title' => 'First Blog Post',
                        'author' => 'Author One',
                        'body' => 'This is the content of the first blog post.',
                        'status' => 'published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'user_id' => $userId,
                        'category_id' => $categoryId,
                        'title' => 'Second Blog Post',
                        'author' => 'Author Two',
                        'body' => 'This is the content of the second blog post.',
                        'status' => 'drafted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'user_id' => $userId,
                        'category_id' => $categoryId,
                        'title' => 'Third Blog Post',
                        'author' => 'Author Three',
                        'body' => 'This is the content of the third blog post.',
                        'status' => 'published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'user_id' => $userId,
                        'category_id' => $categoryId,
                        'title' => 'Fourth Blog Post',
                        'author' => 'Author Four',
                        'body' => 'This is the content of the fourth blog post.',
                        'status' => 'drafted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];
        
                // Insert data into the blogs table
                DB::table('blogs')->insert($blogs);
    }
}
