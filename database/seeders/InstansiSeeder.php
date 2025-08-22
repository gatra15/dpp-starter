<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $instansi = [
            ['name' => 'Dapenbun'],
            ['name' => 'PTPN I (Supporting Co)'],
            ['name' => 'PTPN IV (Palm Co)'],
            ['name' => 'PTPN III (Holding)'],
        ];

        foreach ($instansi as $instansi) {
            Instansi::firstOrCreate(['name' => $instansi['name']], $instansi);
        }
    }
}
