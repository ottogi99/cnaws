<?php

use Illuminate\Database\Seeder;

class LargeFarmersTableSeeder extends Seeder
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
                'name'=>'농부1', 'age'=>45, 'sex'=>'M', 'contact'=>'01095588923', 'address'=>'대전 유성구 테크노2로 187 418',
                'acreage'=>110, 'cultivar'=>'감귤', 'bank_name'=>'국민은행', 'bank_account'=>'11322311',
                'remark'=>'대표이사'
            ],
            [
                'business_year'=>'2020',
                'sigun_code'=>'ca', 'nonghyup_id'=>'nh485014',
                'name'=>'농부2', 'age'=>47, 'sex'=>'M', 'contact'=>'01024561527', 'address'=>'대전 유성구 테크노2로 187 418',
                'acreage'=>120, 'cultivar'=>'사과', 'bank_name'=>'하나은행', 'bank_account'=>'22355323',
                'remark'=>'기술영업부 이사'
            ],
            [
                'business_year'=>'2020',
                'sigun_code'=>'ca', 'nonghyup_id'=>'nh485014',
                'name'=>'농부3', 'age'=>46, 'sex'=>'M', 'contact'=>'01054603876', 'address'=>'대전 유성구 테크노2로 187 418',
                'acreage'=>150, 'cultivar'=>'배,포도', 'bank_name'=>'기업은행', 'bank_account'=>'91381919',
                'remark'=>'시스템개발부 이사'
            ],
        ];

        foreach ($farmers as $farmer) {
            App\LargeFarmer::create([
              'business_year' => $farmer['business_year'],
              'sigun_code'    => $farmer['sigun_code'],
              'nonghyup_id'   => $farmer['nonghyup_id'],
              'name'          => $farmer['name'],
              'age'           => $farmer['age'],
              'sex'           => $farmer['sex'],
              'contact'       => $farmer['contact'],
              'address'       => $farmer['address'],
              'acreage'       => $farmer['acreage'],
              'cultivar'      => $farmer['cultivar'],
              'bank_name'     => $farmer['bank_name'],
              'bank_account'  => $farmer['bank_account'],
              'remark'        => $farmer['remark'],
            ]);
        }

        factory(App\LargeFarmer::class, 40)->create();
    }
}
