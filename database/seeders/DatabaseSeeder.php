<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Haruncpi\LaravelIdGenerator\IdGenerator;

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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $Admin = new User;

        $generator_rules = [
            'table' => 'users',
            'length' => '10',
            'prefix' => date('ymd'),
        ];
        $id = IdGenerator::generate($generator_rules);

        $Admin->id = $id;
        $Admin->user_type = 1;
        $Admin->username = 'admin';
        $Admin->first_name = 'Admin';
        $Admin->last_name = 'One';
        $Admin->password = Hash::make('admin1234');
        $Admin->email = 'admin@mail.com';
        $Admin->phone_number = '0215001';
        $Admin->umkm_status = true;
        $Admin->save();
    }
}
