<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!DB::table('admins')->where('email','BM1')->first()) {
          DB::table('admins')->insert([
            'name' => 'BM1',
            'email' => 'BM1',
            'password' => Hash::make('1234'),
            'is_moder' => 1
          ]);
        }
        if(!DB::table('admins')->where('email','BM2')->first()) {
          DB::table('admins')->insert([
            'name' => 'BM2',
            'email' => 'BM2',
            'password' => Hash::make('1234'),
            'is_moder' => 1

          ]);
        }
        if(!DB::table('admins')->where('email','BM3')->first()) {
          DB::table('admins')->insert([
            'name' => 'BM3',
            'email' => 'BM3',
            'password' => Hash::make('1234'),
            'is_moder' => 1
        ]);
      }
    }
}
