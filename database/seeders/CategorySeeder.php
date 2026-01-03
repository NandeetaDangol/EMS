<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Music & Concerts',
                'description' => 'Live music performances, concerts, and music festivals',
                'icon' => 'music-icon.png',
                'is_active' => true,
            ],
            [
                'name' => 'Technology & Business',
                'description' => 'Tech conferences, business seminars, and professional development',
                'icon' => 'tech-icon.png',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Fitness',
                'description' => 'Sporting events, marathons, fitness activities, and competitions',
                'icon' => 'sports-icon.png',
                'is_active' => true,
            ],
            [
                'name' => 'Arts & Theater',
                'description' => 'Theater performances, cultural events, and artistic exhibitions',
                'icon' => 'theater-icon.png',
                'is_active' => true,
            ],
            [
                'name' => 'Food & Dining',
                'description' => 'Food festivals, cooking classes, and culinary experiences',
                'icon' => 'food-icon.png',
                'is_active' => true,
            ],
            [
                'name' => 'Education & Workshops',
                'description' => 'Educational workshops, training sessions, and learning events',
                'icon' => 'education-icon.png',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories seeded successfully');
    }
}
