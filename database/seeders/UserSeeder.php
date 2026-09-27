<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        // Akun statis untuk login operasional kedai
        \App\Models\User::create([
            'nama_pengguna' => 'admin_kopi',
            'password' => bcrypt('admin123'),
            'peran' => 'admin',
        ]);
        \App\Models\User::create([
            'nama_pengguna' => 'kasir_lutfi',
            'password' => bcrypt('kasir123'),
         'peran' => 'kasir',
        ]);
        \App\Models\User::create([
            'nama_pengguna' => 'owner_kedai',
            'password' => bcrypt('owner123'),
            'peran' => 'pemilik',
        ]);
    }
}
