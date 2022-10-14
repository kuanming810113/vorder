<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::truncate();
        
        \App\Models\User::insert([
        	'store_id'=>1,
            'name'=>'小黃貓',
            'email'=>'123@yahoo.com',
            'password'=>Hash::make('123'),
            'created_at'=> NOW()
        ]);

        \App\Models\Store::truncate();
        
        \App\Models\Store::insert([
            'name'=>'喵喵花店',
            'url'=> md5(uniqid()),
            'created_at'=> NOW()
        ]);

    }
}
