<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Sale::create([
            'name'		=> 'Sale One',
        	'email'		=>	'sale1@gmail.com',
        	'phone'		=>	'09406405233',
        	'password'	=>	Hash::make('12345'),
            'admin_id'  => 1
        ]);
        Sale::create([
            'name'		=> 'Sale Two',
        	'email'		=>	'sale2@gmail.com',
        	'phone'		=>	'09406405234',
        	'password'	=>	Hash::make('12345'),
            'admin_id'  => 1
        ]);
    }
}
