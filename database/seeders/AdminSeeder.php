<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Admin::create([
        	'name'		=> 'Super Admin',
        	'email'		=>	'admin@gmail.com',
        	'phone'		=>	'09406405232',
        	'password'	=>	Hash::make('12345')
        ]);
    }
}
