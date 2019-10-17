<?php

use Illuminate\Database\Seeder;

class CrousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //Create faker instance
      $faker = \Faker\Factory::create();
      //Flush the table before recreate
      DB::statement('SET FOREIGN_KEY_CHECKS = 0');
      DB::table('crouses')->truncate();
      \App\Crouse::truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS = 1');
      foreach (range(1,10) as $number) {
        \App\Crouse::create([
          'subject' => $faker->word,
        ]);
      }
    }
}
