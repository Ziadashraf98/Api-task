<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run()
    {
        DB::table('posts')->delete();
        
        Post::create([
            'title'=>'What are your best programming languages?',
            'body'=>'PHP',
            'image'=>'test.png',
            'status'=>0,
        ]);
        
        Post::create([
            'title'=>'What are the best types of laptops for you?',
            'body'=>'Lenovo',
            'image'=>'test.png',
            'status'=>0,
        ]);
    }
}
