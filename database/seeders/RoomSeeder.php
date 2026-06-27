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
        $buildingA = \App\Models\Building::create([
            'name' => 'Gedung A',
            'description' => 'Gedung Utama Fakultas'
        ]);

        $buildingB = \App\Models\Building::create([
            'name' => 'Gedung B',
            'description' => 'Gedung Laboratorium'
        ]);

        \App\Models\Room::create([
            'building_id' => $buildingA->id,
            'name' => 'Ruang A101',
            'capacity' => 40,
            'type' => 'Classroom'
        ]);

        \App\Models\Room::create([
            'building_id' => $buildingA->id,
            'name' => 'Aula Utama',
            'capacity' => 150,
            'type' => 'Auditorium'
        ]);

        \App\Models\Room::create([
            'building_id' => $buildingB->id,
            'name' => 'Lab Komputer 1',
            'capacity' => 30,
            'type' => 'Lab'
        ]);
    }
}
