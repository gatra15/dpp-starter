<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status; // Import model Status

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'pending'],
            ['name' => 'pimpinan_approved'],
            ['name' => 'approved'],
            ['name' => 'rejected'],
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}
