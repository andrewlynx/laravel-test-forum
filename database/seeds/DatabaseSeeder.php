<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('threads')->truncate(); //If you want to reset to 50. See note below.
        //$this->call('UserTableSeeder');
        $this->call('ThreadTableSeeder');
    }
}
