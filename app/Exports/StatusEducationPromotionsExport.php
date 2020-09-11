<?php

namespace App\Exports;

use App\StatusEducationPromotion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StatusEducationPromotionsExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;
        $year = $this->year;
        $keyword = $this->keyword;

        $query = \App\StatusEducationPromotion::with('sigun')->with('nonghyup')
                                  ->join('siguns', 'status_education_promotions.sigun_code', '=', 'siguns.code')
                                  ->join('users', 'status_education_promotions.nonghyup_id', 'users.nonghyup_id')
                                  ->select('status_education_promotions.*', 'siguns.sequence', 'siguns.name as sigun_name', 'users.sequence', 'users.name as nonghyup_name')
                                  ->where('status_education_promotions.business_year', $business_year)
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('status_education_promotions.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('status_education_promotions.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 지출항목, 지급대상으로 검색
                                      return $query->whereRaw(
                                                    '(siguns.name like ? or users.name like ? or status_education_promotions.item like ? or status_education_promotions.targer like ?)',
                                                    [$keyword, $keyword, $keyword, $keyword]
                                                  );
                                  })
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('status_education_promotions.created_at', 'desc');

        return $query;
    }

    public function map($row): array
    {
        return [
            [
                $row->business_year,
                $row->sigun_name,
                $row->nonghyup_name,
                Date::dateTimeToExcel($row->payment_date),
                $row->item,
                $row->target,
                $row->detail,
                $row->payment_sum,
                $row->payment_do,
                $row->payment_sigun,
                $row->payment_center,
                $row->payment_unit,
                $row->remark,
                Date::dateTimeToExcel($row->created_at),
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
           '대상년도',
           '시군명', '대상농협',
           '지출일자', '지출항목', '지급대상', '내용',
           '지급액(계)',
           '도비', '시군비', '중앙회', '지역농협',
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
