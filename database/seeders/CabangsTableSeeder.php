<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cabangs')->insert([
            [
                'name' => 'Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lampung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Palembang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Padang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tanjung Enim',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
