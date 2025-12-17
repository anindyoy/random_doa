<?php

namespace Database\Seeders;

use App\Models\Doa;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\DoaSeeder;
use Illuminate\Database\Seeder;
use Database\Factories\DoaFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'admin@gmail.com'
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true
            ]
        );

        // clear data
        User::whereIsAdmin(false)->delete();
        DB::table('doa_tag')->truncate();
        Doa::truncate();
        Tag::truncate();

        // create data
        User::factory(2)->create();
        Tag::factory(5)->create();
        // Doa::factory(10)->create()
        //     ->each(function ($doa) {
        //         $tags = Tag::inRandomOrder()->limit(rand(1, 2))->get();
        //         $doa->tags()->attach($tags);
        //     });

        $this->call([DoaSeeder::class]);
    }
}
