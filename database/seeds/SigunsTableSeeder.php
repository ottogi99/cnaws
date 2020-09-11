<?php

use Illuminate\Database\Seeder;

class SigunsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $siguns = array(
          array('sequence'  => 1, 'code'  => 'ca', 'name'  => '천안시'),
          array('sequence'  => 2, 'code'  => 'gj', 'name'  => '공주시'),
          array('sequence'  => 3, 'code'  => 'br', 'name'  => '보령시'),
          array('sequence'  => 4, 'code'  => 'as', 'name'  => '아산시'),
          array('sequence'  => 5, 'code'  => 'ss', 'name'  => '서산시'),
          array('sequence'  => 6, 'code'  => 'ns', 'name'  => '논산시'),
          array('sequence'  => 8, 'code'  => 'dj', 'name'  => '당진시'),
          array('sequence'  => 7, 'code'  => 'sj', 'name'  => '세종시'),
          array('sequence'  => 9, 'code'  => 'gs', 'name'  => '금산군'),
          array('sequence'  => 10, 'code'  => 'by', 'name'  => '부여군'),
          array('sequence'  => 11, 'code'  => 'sc', 'name'  => '서천군'),
          array('sequence'  => 12, 'code'  => 'cy', 'name'  => '청양군'),
          array('sequence'  => 13, 'code'  => 'hs', 'name'  => '홍성군'),
          array('sequence'  => 14, 'code'  => 'ys', 'name'  => '예산군'),
          array('sequence'  => 15, 'code'  => 'ta', 'name'  => '태안군'),
          array('sequence'  => 16, 'code'  => 'pn', 'name'  => '품농'),
      );

      foreach ($siguns as $sigun) {
        App\Sigun::create([
          'sequence' => $sigun['sequence'],
          'code' => $sigun['code'],
          'name' => $sigun['name']
        ]);
      }
    }
}
