<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Urusan; // Pastikan model Urusan sudah ada

class UrusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $urusans = [
            // Teknologi Informasi (1)
            ['name' => 'Pengembangan TI', 'department_id' => 1],
            ['name' => 'Pemeliharaan TI', 'department_id' => 1],

            // Sekretaris (2)
            ['name' => 'Humas & Protokol', 'department_id' => 2],
            ['name' => 'Kesekretariatan & Kearsipan', 'department_id' => 2],
            ['name' => 'Hukum', 'department_id' => 2],

            // SDM & Umum (3)
            ['name' => 'Administrasi dan Personalia', 'department_id' => 3],
            ['name' => 'Asesmen & Pengembangan Organisasi', 'department_id' => 3],
            ['name' => 'Umum', 'department_id' => 3],

            // Keuangan (4)
            ['name' => 'Akuntansi & Anggaran', 'department_id' => 4],
            ['name' => 'Verifikasi & Pelaporan', 'department_id' => 4],
            ['name' => 'Keuangan & Perpajakan', 'department_id' => 4],

            // Kepesertaan & Pelayanan Peserta (5)
            ['name' => 'KPST. I, Pendanaan & Analisa Data', 'department_id' => 5],
            ['name' => 'KPST. II & Laporan Kepesertaan', 'department_id' => 5],
            ['name' => 'Pembayaran Manfaat Pensiun & Pelayanan Peserta', 'department_id' => 5],

            // Pasar Uang & Pasar Modal (6)
            ['name' => 'Pasar Modal I', 'department_id' => 6],
            ['name' => 'Pasar Modal II & Pasar Uang', 'department_id' => 6],

            // Investasi Langsung dan Pengelolaan Aset (7)
            ['name' => 'Investasi Langsung', 'department_id' => 7],
            ['name' => 'Pengelolaan Aset', 'department_id' => 7],

            // Pengawasan Internal (8)
            ['name' => 'Audit I', 'department_id' => 8],
            ['name' => 'Audit II', 'department_id' => 8],

            // Manajemen Resiko & Kepatuhan (9)
            ['name' => 'Manajemen Risiko', 'department_id' => 9],
            ['name' => 'Kepatuhan', 'department_id' => 9],
        ];

        foreach ($urusans as $urusan) {
            Urusan::firstOrCreate(
                ['name' => $urusan['name'], 'department_id' => $urusan['department_id']],
                $urusan
            );
        }
    }
}
