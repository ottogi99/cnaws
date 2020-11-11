<?php

namespace App\Exports;

use App\ManpowerSupporter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class ManpowerSupportersExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;
        $keyword = $this->keyword;

        $query = \App\ManpowerSupporter::with('sigun')->with('nonghyup')
                                  ->join('siguns', 'manpower_supporters.sigun_code', '=', 'siguns.code')
                                  ->join('users', 'manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                                  ->select('manpower_supporters.*', 'siguns.sequence', 'siguns.name as sigun_name', 'users.sequence', 'users.name as nonghyup_name')
                                  ->where('manpower_supporters.business_year', $business_year)
                                  ->where('users.is_admin', '!=', 1)
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('manpower_supporters.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('manpower_supporters.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 지원반 성명으로 검색
                                      return $query->whereRaw(
                                                    '(siguns.name like ? or users.name like ? or manpower_supporters.name like ?)',
                                                    [$keyword, $keyword, $keyword]
                                                  );
                                  })
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('manpower_supporters.created_at', 'desc');

        return $query;
    }

    public function map($supporter): array
    {
        $age = \Carbon\Carbon::parse($supporter->birth)->diffInYears(\Carbon\Carbon::now());

        return [
            [
                $supporter->business_year,
                $supporter->sigun_name,
                $supporter->nonghyup_name,
                $supporter->name,
                // $supporter->age,
                $supporter->birth,
                $age,
                ($supporter->sex == 'M' ? '남' : '여'),
                $supporter->address,
                // $supporter->contact,
                $supporter->phoneNumber(),
                $supporter->training_date1,
                $supporter->training_date2,
                $supporter->training_date3,
                ($supporter->has_insurance == '1' ? '여' : '부'),
                $supporter->bank_name,
                $supporter->bank_account,
                $supporter->remark,
                Date::dateTimeToExcel($supporter->created_at),
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'N' => DataType::TYPE_STRING,
            'Q' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
            '대상년도',
           '시군명', '대상농협',
           // '성명', '연령(세)', '성별', '주소','연락처',
           '성명', '생년월일', '연령(세)', '성별', '주소','연락처',
           '교육참여일1', '교육참여일2', '교육참여일3',
           '상해보험가입여부',
           '은행명','계좌번호',
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
