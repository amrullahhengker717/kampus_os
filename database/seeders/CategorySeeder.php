<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Akademik',
                'slug' => 'akademik',
                'description' => 'Diskusi seputar perkuliahan, tugas, dan dosen.',
                'color' => 'blue'
            ],
            [
                'name' => 'Karir & Magang',
                'slug' => 'karir-magang',
                'description' => 'Informasi lowongan kerja, tips interview, dan pengalaman magang.',
                'color' => 'green'
            ],
            [
                'name' => 'Umum (General)',
                'slug' => 'umum',
                'description' => 'Diskusi bebas seputar kehidupan kampus.',
                'color' => 'indigo'
            ],
            [
                'name' => 'Hiburan & Hobi',
                'slug' => 'hiburan-hobi',
                'description' => 'Meme, film, game, dan keseruan lainnya di luar jam kuliah.',
                'color' => 'pink'
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
