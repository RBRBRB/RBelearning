<?php

use Illuminate\Database\Seeder;

class ChaptersTableSeeder extends Seeder
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
      DB::table('chapters')->truncate();
      \App\Chapter::truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS = 1');
      foreach (range(1,5) as $number) {
        \App\Chapter::create([
          'chapter' => $faker->word,
          'subject_id'=> rand(1,3),
        ]);
      }
    }
}
