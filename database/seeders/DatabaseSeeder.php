<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 初始化用户角色，将 1 号用户指派为『站长』
        $user = User::find(1);
        $user->assignRole('Founder');
        $user->update([
            'email' => 'necessitatibus14@example.org',
            'password' => Hash::make('123456'),
        ]);

        // 将 2 号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
        $user->update([
            'email' => 'reprehenderit.at@example.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
