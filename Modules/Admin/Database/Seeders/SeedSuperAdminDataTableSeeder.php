<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeedSuperAdminDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

       DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'sadmin@gmail.com',
            'password' => Hash::make('sadmin@gmail.com'),
            'com_id' => Str::uuid()->toString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
