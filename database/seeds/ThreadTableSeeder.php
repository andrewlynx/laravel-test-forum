<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Thread;

class ThreadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        //create threads
        foreach(range(1, 10) as $index) {
            Thread::create([
                'title'     => $faker->word(3),
                'content'   => $faker->sentence(4),
                'parent'    => 0,
                'author'    => rand(1, 5),
            ]);
        }
        
        //create comments
        foreach(range(1, 10) as $index) {
            Thread::create([
                'title'     => '',
                'content'   => $faker->sentence(4),
                'parent'    => rand(1, 9),
                'author'    => rand(1, 5),
            ]);
        }
    }
}
