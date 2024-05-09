<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Carbon\Carbon;
class TableCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([ 
            'icon' => "demo-1",
            "vi" => [
                'title' => 'Tiêu đề việt nam 1',
            ],
            "en" => [
                'title' => 'title English 1',
            ],
        ]);
    }
}
