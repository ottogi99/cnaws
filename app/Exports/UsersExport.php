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
        // $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $keyword = $this->keyword;

        $query = \App\User::with('sigun')
                    ->join('siguns', 'users.sigun_code', 'siguns.code')
                    ->select('users.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name')
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
                // $user->business_year,
                $user->sigun_name
                ,$user->name
                ,$user->nonghyup_id
                ,$user->address
                ,$user->contact
                ,$user->representative
                ,($user->activated) ? '활성' : '비활성'
                ,($user->is_input_allowed) ? '허용' : '불가'
                ,($user->is_admin) ? '관리자' : '사용자'
                ,$user->sequence
                ,Date::dateTimeToExcel($user->created_at)
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD
            // 'J' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
            // '대상년도',
            '시군명', '농협명', '농협ID'
            ,'주소', '연락처', '대표자'
            ,'사용자 활성화', '입력 허용', '사용자 권한', '순서'
            , '등록일자'
            // ,'삭제일자'
        ];
    }

    // public function forYear($year)
    // {
    //     $this->year = ($year) ? $year : now()->year;
    //     return $this;
    // }

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
