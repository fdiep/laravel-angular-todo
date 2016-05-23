<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($u) {
          $u->todos()->save(factory(App\Todo::class)->make());
        });
    }
}
