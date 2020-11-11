<?php

namespace App\Exports;

use App\LargeFarmer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LargeFarmersExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;
        $keyword = $this->keyword;

        $query = \App\LargeFarmer::with('sigun')->with('nonghyup')
                                  ->join('siguns', 'large_farmers.sigun_code', '=', 'siguns.code')
                                  ->join('users', 'large_farmers.nonghyup_id', 'users.nonghyup_id')
                                  ->select('large_farmers.*', 'siguns.sequence', 'siguns.name as sigun_name', 'users.sequence', 'users.name as nonghyup_name')
                                  ->where('large_farmers.business_year', $business_year)
                                  ->where('users.is_admin', '!=', 1)
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('large_farmers.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('large_farmers.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 농가명으로 검색
                                      return $query->whereRaw(
                                                    '(siguns.name like ? or users.name like ? or large_farmers.name like ?)',
                                                    [$keyword, $keyword, $keyword]
                                                  );

                                  })
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('large_farmers.created_at', 'desc');

        return $query;
    }

    public function map($farmer): array
    {
        $age = \Carbon\Carbon::parse($farmer->birth)->diffInYears(\Carbon\Carbon::now());

        return [
            [
                $farmer->business_year,
                $farmer->sigun_name,
                $farmer->nonghyup_name,
                $farmer->name,
                // $farmer->age,
                $farmer->birth,
                $age,
                ($farmer->sex == 'M' ? '남' : '여'),
                $farmer->address,
                // $farmer->contact,
                $farmer->phoneNumber(),
                $farmer->acreage,
                $farmer->cultivar,
                $farmer->bank_name,
                $farmer->bank_account,
                $farmer->remark,
                Date::dateTimeToExcel($farmer->created_at),
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
          '대상년도',
           '시군명', '대상농협',
           // '성명', '연령(세)', '성별', '주소','연락처',
           '성명', '생년월일', '연령(세)', '성별', '주소','연락처',
           '소유경지면저(㏊)','재배품목','은행명','계좌번호',
           '비고', '등록일'
        ];
    }

    public function forYear($year)
    {
        $this->year = ($year) ? $year : now()->year;
        return $this;
    }

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
