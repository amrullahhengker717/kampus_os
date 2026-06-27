<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teknik = \App\Models\Building::create([
            'name' => 'Gedung Teknik',
            'description' => 'Fakultas Teknik'
        ]);

        $feb = \App\Models\Building::create([
            'name' => 'Gedung FEB',
            'description' => 'Fakultas Ekonomi dan Bisnis'
        ]);

        // Teknik Lantai 1
        for ($i = 1; $i <= 4; $i++) {
            \App\Models\Room::create([
                'building_id' => $teknik->id,
                'name' => 'T.10' . $i,
                'floor' => 1,
                'capacity' => 40,
                'type' => 'Classroom'
            ]);
        }

        // Teknik Lantai 2
        for ($i = 1; $i <= 4; $i++) {
            \App\Models\Room::create([
                'building_id' => $teknik->id,
                'name' => 'T.20' . $i,
                'floor' => 2,
                'capacity' => 40,
                'type' => 'Classroom'
            ]);
        }

        // FEB Lantai 1
        for ($i = 1; $i <= 4; $i++) {
            \App\Models\Room::create([
                'building_id' => $feb->id,
                'name' => 'E.10' . $i,
                'floor' => 1,
                'capacity' => 50,
                'type' => 'Classroom'
            ]);
        }
        
        // FEB Lantai 2
        for ($i = 1; $i <= 2; $i++) {
            \App\Models\Room::create([
                'building_id' => $feb->id,
                'name' => 'Lab FEB ' . $i,
                'floor' => 2,
                'capacity' => 30,
                'type' => 'Lab'
            ]);
        }
    }
}
