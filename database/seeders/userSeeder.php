<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    public function run()
    {
        $name = Str::random(5);
        User::create([
            'name' => $name,
            'email' => $name.'@yopmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}   