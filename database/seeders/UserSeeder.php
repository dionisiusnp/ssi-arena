<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = $this->user();
        foreach ($users as $user) {
            User::updateOrCreate(
                ['id' => $user['id']],
                $user
            );
        }
    }

    private function user() 
    {
        $user = [
            [
                'id' => 1,
                'name' => 'Game Master',
                'email' => 'gm@gmail.com',
                'password' => Hash::make('admingame'),
                'is_member' => null,
                'is_lecturer' => true,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Member',
                'email' => 'member@gmail.com',
                'password' => Hash::make('membergame'),
                'is_member' => true,
                'is_lecturer' => false,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Pengajar',
                'email' => 'mentor@gmail.com',
                'password' => Hash::make('mentorgame'),
                'is_member' => true,
                'is_lecturer' => true,
                'is_active' => true,
            ],
        ];
        return $user;
    }
}
