<?php

namespace App\Exports;

use App\Budgets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class BudgetsExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;
        $keyword = $this->keyword;

        $query = \App\Budget::with('sigun')->with('nonghyup')
                              ->join('siguns', 'budgets.sigun_code', 'siguns.code')
                              ->join('users', 'budgets.nonghyup_id', 'users.nonghyup_id')
                              ->select(
                                  'budgets.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                                  'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                                )
                              ->where('budgets.business_year', $business_year)
                              ->where('users.is_admin', '!=', 1)
                              ->when($sigun_code, function($query, $sigun_code) {
                                  return $query->where('budgets.sigun_code', $sigun_code);
                              })
                              ->when($nonghyup_id, function($query, $nonghyup_id) {
                                  return $query->where('budgets.nonghyup_id', $nonghyup_id);
                              })
                              ->when($keyword, function($query, $keyword) {
                                  // 시군명, 대상농협, 농가명으로 검색
                                  return $query->whereRaw(
                                                '(siguns.name like ? or users.name like ?)',
                                                [$keyword, $keyword]
                                              );
                              })
                              ->orderby('siguns.sequence')
                              ->orderby('users.sequence')
                              ->orderby('budgets.created_at', 'desc');

        return $query;
    }

    public function map($budget): array
    {
        return [
            [
                $budget->business_year,
                $budget->sigun_name,
                $budget->nonghyup_name,
                $budget->amount,
                $budget->payment_do,
                $budget->payment_sigun,
                $budget->payment_center,
                $budget->payment_unit,
                Date::dateTimeToExcel($budget->created_at),
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
            '대상년도',
            '시군명', '대상농협',
            '사업비(합계)',
            '도비', '시군비', '중앙회', '지역농협',
            '등록일'
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
