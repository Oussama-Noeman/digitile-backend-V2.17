<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeData2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('first_orders')->insert([
            'active' => 0,
            'amount' => 0,
            'type' => '1'
        ]);
        DB::table('free_deliveries')->insert([
            'active' => 0,
            'amount' => 0,
        ]);

    }
}
