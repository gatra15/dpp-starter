<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room; // Import model Room

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = [
            [
                'name' => 'Ruang A',
                'capacity' => 15,
                'description' => 'Ruangan rapat kecil dengan proyektor.',
                'available' => true,
            ],
            [
                'name' => 'Ruang B',
                'capacity' => 15,
                'description' => 'Ruangan rapat sedang dengan smart TV.',
                'available' => true,
            ],
            [
                'name' => 'Ruang C',
                'capacity' => 15,
                'description' => 'Ruangan rapat sedang dengan smart TV.',
                'available' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['name' => $room['name']], $room);
        }
    }
}
