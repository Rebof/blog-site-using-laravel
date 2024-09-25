<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->insert([
            'email' => 'rebofrebok@gmail.com',
            'name' => 'RebofAdmin',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
        ]);
    }
}
