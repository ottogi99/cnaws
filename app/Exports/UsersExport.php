<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $keyword = $this->keyword;

        $query = \App\User::with('sigun')
                    ->join('siguns', 'users.sigun_code', 'siguns.code')
                    ->join('activated_users', 'users.nonghyup_id', 'activated_users.nonghyup_id')
                    ->select('activated_users.business_year as business_year', 'users.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name')
                    ->where('activated_users.business_year', $business_year)
                    ->when($sigun_code, function($query, $sigun_code) {
                        return $query->where('users.sigun_code', $sigun_code);
                    })
                    ->when($keyword, function($query, $keyword) {
                        return $query->where('users.nonghyup_id', 'like', '%'.$keyword.'%')
                                      ->orWhere('users.name', 'like', '%'.$keyword.'%')
                                      ->orWhere('users.representative', 'like', '%'.$keyword.'%');
                    })
                    // ->orderby($sort, $order)
                    ->orderby('siguns.sequence')
                    ->orderby('users.is_admin', 'DESC')
                    ->orderby('users.sequence');

        return $query;
    }

    public function map($user): array
    {
        return [
            [
                $user->business_year,
                $user->sigun_name,
                $user->nonghyup_id,
                $user->name,
                $user->address,
                $user->contact,
                $user->representative,
                ($user->activated) ? '활성' : '비활성',
                ($user->is_admin) ? '관리자' : '사용자',
                Date::dateTimeToExcel($user->created_at),
                ($user->deleted_at) ? Date::dateTimeToExcel($user->deleted_at) : '',
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
            '대상년도',
            '시군명', '농협ID', '농협명',
            '주소', '연락처', '대표자',
            '활성화', '권한', '등록일자', '삭제일자'
        ];
    }

    public function forYear($year)
    {
        $this->year = ($year) ? $year : now()->year;
        return $this;
    }

    public function forSigun($sigun_code)
    {
        $this->sigun_code = $sigun_code;
        return $this;
    }
    // 검색어로 조회
    public function forKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }
}
