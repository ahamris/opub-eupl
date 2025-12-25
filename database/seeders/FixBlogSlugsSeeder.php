<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FixBlogSlugsSeeder extends Seeder
{
    public function run(): void
    {
        // Delete all existing blogs and re-seed
        Blog::query()->delete();
        
        $this->command->info('Existing blogs deleted. Running BlogSeeder...');
        
        $this->call(BlogSeeder::class);
    }
}
