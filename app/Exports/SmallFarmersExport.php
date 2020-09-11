<?php

namespace App\Exports;

use App\SmallFarmer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class SmallFarmersExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        // return SmallFarmer::query();
        //
        // $query = SmallFarmer::query();
        //
        // if ($this->sigun_code)
        //     $query = $query->where('sigun_code', $this->sigun_code);
        //
        // if ($this->user_id)
        //     $query = $query->where('user_id', $this->user_id);
        //
        // return $query->orderBy('sigun');

        // $query = SmallFarmer::join('siguns', 'small_farmers.sigun_code', '=', 'siguns.code')
        //                     ->select('small_farmers.*', 'siguns.sequence', 'siguns.name as sigun_name')
        //                     ->orderby('siguns.sequence');
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;
        $keyword = $this->keyword;

        $query = \App\SmallFarmer::with('sigun')->with('nonghyup')
                                  ->join('siguns', 'small_farmers.sigun_code', '=', 'siguns.code')
                                  ->join('users', 'small_farmers.nonghyup_id', 'users.nonghyup_id')
                                  ->select('small_farmers.*', 'siguns.sequence', 'siguns.name as sigun_name', 'users.sequence', 'users.name as nonghyup_name')
                                  ->where('small_farmers.business_year', $business_year)
                                  ->where('users.is_admin', '!=', 1)
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('small_farmers.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('small_farmers.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 농가명으로 검색
                                      return $query->whereRaw(
                                                    '(siguns.name like ? or users.name like ? or small_farmers.name like ?)',
                                                    [$keyword, $keyword, $keyword]
                                                  );
                                  })
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('small_farmers.created_at', 'desc');

        return $query;
    }

    public function map($farmer): array
    {
        return [
            [
                $farmer->business_year,
                $farmer->sigun_name,
                $farmer->nonghyup_name,
                $farmer->name,
                $farmer->age,
                ($farmer->sex == 'M' ? '남' : '여'),
                $farmer->address,
                $farmer->contact,
                $farmer->sum_acreage,
                $farmer->acreage1,
                $farmer->acreage2,
                $farmer->acreage3,
                $farmer->remark,
                Date::dateTimeToExcel($farmer->created_at),
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
          '대상년도',
          '시군명', '대상농협',
          '성명', '연령(세)', '성별', '주소','연락처',
          '계','답작','전작','기타',
          '비고', '등록일'
        ];
    }

    public function forYear($year)
    {
        $this->year = ($year) ? $year : now()->year;
        return $this;
    }

    // public function __construct(string $nonghyup_id)
    // {
    //     $this->nonghyup_id = $nonghyup_id;
    // }
    // 시군별 검색 결과만 조회시
    public function forSigun($sigun_code, $user)
    {
        $this->sigun_code = $sigun_code;

        if (!$user->isAdmin()) {
            if (!$sigun_code) {
                $this->sigun_code = $user->sigun->code;
            }
        }

        return $this;
    }

    // 사용자(농협)별 검색 결과만 조회시
    public function forNonghyup($nonghyup_id, $user)
    {
        $this->nonghyup_id = $nonghyup_id;

        if (!$user->isAdmin()) {
            if (!$nonghyup_id) {
                $this->nonghyup_id = $user->nonghyup_id;
            }
        }

        return $this;
    }

    // 검색어로 조회
    public function forKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }
}
