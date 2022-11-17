<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function users_number()
    {
        $users = User::count();
        return response($users);
    }

    public function posts_number()
    {
        $posts = Post::where('status' , 1)->count();
        return response($posts);
    }

    public function users_number_of_0_posts()
    {
        $user = User::whereDoesntHave('posts')->count();
        return response($user);
    }
    
    public function users_number_of_posts()
    {
        $users_number = User::count();
        $users_number_of_0_posts = User::whereDoesntHave('posts')->count();
        $users_number_of_posts = $users_number - $users_number_of_0_posts;
        return response($users_number_of_posts);
    }
}
