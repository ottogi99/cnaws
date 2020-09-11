<?php

use Illuminate\Database\Seeder;

class SmallFarmersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $farmers = [
            [
                'business_year'=>'2020',
                'sigun_code'=>'ca', 'nonghyup_id'=>'nh485014',
                'name'=>'오진덕', 'age'=>45, 'sex'=>'M', 'contact'=>'01095588923', 'address'=>'대전 유성구 테크노2로 187 418',
                'acreage1'=>100, 'acreage2'=>100, 'acreage3'=>100, 'sum_acreage'=>300, 'remark'=>'대표이사'
            ],
            [
                'business_year'=>'2020',
                'sigun_code'=>'ca', 'nonghyup_id'=>'nh485047',
                'name'=>'한은대', 'age'=>47, 'sex'=>'M', 'contact'=>'01024561527', 'address'=>'대전 유성구 테크노2로 187 418',
                'acreage1'=>80, 'acreage2'=>80, 'acreage3'=>80, 'sum_acreage'=>240, 'remark'=>'기술영업부 이사'
            ],
            [
                'business_year'=>'2020',
                'sigun_code'=>'ca', 'nonghyup_id'=>'nh485058',
                'name'=>'김주영', 'age'=>46, 'sex'=>'M', 'contact'=>'01054603876', 'address'=>'대전 유성구 테크노2로 187 418',
                'acreage1'=>80, 'acreage2'=>80, 'acreage3'=>80, 'sum_acreage'=>240, 'remark'=>'시스템개발부 이사'
            ],
        ];

        foreach ($farmers as $farmer) {
            App\SmallFarmer::create([
              'business_year' => $farmer['business_year'],
              'sigun_code'    => $farmer['sigun_code'],
              'nonghyup_id'   => $farmer['nonghyup_id'],
              'name'          => $farmer['name'],
              'age'           => $farmer['age'],
              'sex'           => $farmer['sex'],
              'contact'       => $farmer['contact'],
              'address'       => $farmer['address'],
              'acreage1'      => $farmer['acreage1'],
              'acreage2'      => $farmer['acreage2'],
              'acreage3'      => $farmer['acreage3'],
              'sum_acreage'   => $farmer['sum_acreage'],
              'remark'        => $farmer['remark'],
            ]);
        }

        factory(App\SmallFarmer::class, 50)->create();
    }
}
