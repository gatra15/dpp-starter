<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department; // Import model Department

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'name' => 'Teknologi Informasi',
            ],
            [
                'name' => 'Sekretaris',
            ],
            [
                'name' => 'SDM & Umum',
            ],
            [
                'name' => 'Keuangan',
            ],
            [
                'name' => 'Kepesertaan & Pelayanan Peserta',
            ],
            [
                'name' => 'Pasar Uang & Pasar Modal',
            ],
            [
                'name' => 'Investasi Langsung dan Pengelolaan Aset',
            ],
            [
                'name' => 'Pengawasan Internal',
            ],
            [
                'name' => 'Manajemen Resiko & Kepatuhan',
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department['name']], $department);
        }
    }
}
