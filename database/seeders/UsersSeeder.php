<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();
        
        User::create([
            'name'=>'Ziad',
            'email'=>'ziad@gmail.com',
            'password'=>bcrypt('1973'),
        ]);
        
        User::create([
            'name'=>'Ahmed',
            'email'=>'ahmed@gmail.com',
            'password'=>bcrypt('1999'),
        ]);
    }
}
