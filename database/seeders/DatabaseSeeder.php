<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categoryMap = [
            'Technology' => "https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            'Health' => "https://images.unsplash.com/photo-1494390248081-4e521a5940db?q=80&w=1406&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            'Science' => "https://images.unsplash.com/photo-1630959305790-4c956ce6c0b6?q=80&w=1493&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            'Sports' => "https://images.unsplash.com/photo-1607962837359-5e7e89f86776?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            'Politics' => "https://images.unsplash.com/photo-1520452112805-c6692c840af0?q=80&w=1380&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            'Entertainment' => "https://images.unsplash.com/photo-1586899028174-e7098604235b?q=80&w=1471&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        ];

        // Create categories
        foreach ($categoryMap as $name => $image) {
            Category::create(['name' => $name]);
        }

        // Create 3 users
        User::factory(3)->create()->each(function ($user) use ($categoryMap) {
            // Each user has 5 posts
            Post::factory(5)->make()->each(function ($post) use ($user, $categoryMap) {
                $category = Category::inRandomOrder()->first();

                $post->user_id = $user->id;
                $post->category_id = $category->id;
                $post->save();

                $imageUrl = $categoryMap[$category->name];

                $post->addMediaFromUrl($imageUrl)->toMediaCollection('posts');
            });
        });
    }
}
