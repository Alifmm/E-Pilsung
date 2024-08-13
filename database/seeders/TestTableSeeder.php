<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 60; $i++) {
            $NIK = $faker->randomNumber(8);
            $name = $faker->name;

            DB::table('users')->insert([
                'NIK' => $NIK,
                'name' => $name,
                'email' => 'user' . ($i + 1) . '@gmail.com',
                'password' => bcrypt('00000000'),
                'role' => 'karyawan',
                'idcabang' => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
