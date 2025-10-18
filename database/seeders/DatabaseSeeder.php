<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('password123'), 'role' => 'admin']);
    }
}