<?php

namespace App\Exports;

use App\StatusManpowerSupporter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StatusManpowerSupportersExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $business_year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;
        $year = $this->year;
        $keyword = $this->keyword;

        $query = \App\StatusManpowerSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
                                  ->join('siguns', 'status_manpower_supporters.sigun_code', '=', 'siguns.code')
                                  ->join('users', 'status_manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                                  ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                                  ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                                  ->select(
                                    'status_manpower_supporters.*', 'siguns.sequence', 'siguns.name as sigun_name',
                                    'users.sequence', 'users.name as nonghyup_name',
                                    'large_farmers.name as farmer_name', 'large_farmers.address as farmer_address', 'large_farmers.sex as farmer_sex',
                                    'manpower_supporters.name as supporter_name'
                                  )
                                  ->where('status_manpower_supporters.business_year', $business_year)
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('status_manpower_supporters.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('status_manpower_supporters.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 농가명, 작업자명으로 검색
                                      return $query->whereRaw(
                                                '(siguns.name like ? or users.name like ? or large_farmers.name like ? or large_farmers.name like ? or manpower_supporters.name like ?)',
                                                [$keyword, $keyword, $keyword, $keyword, $keyword]
                                              );
                                  })
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('status_manpower_supporters.created_at', 'desc');

        return $query;
    }

    public function map($row): array
    {
        return [
            [
                $row->business_year,
                $row->sigun_name,
                $row->nonghyup_name,
                $row->farmer_name,
                $row->farmer_address,
                ($row->farmer_sex == 'M') ? '남' : '여',
                $row->supporter_name,
                Date::dateTimeToExcel($row->job_start_date),
                Date::dateTimeToExcel($row->job_end_date),
                $row->working_days,
                $row->work_detail,
                ($row->recipient == 'S') ? '지원단' : '농가',
                $row->payment_item1,
                $row->payment_item2,
                $row->payment_item3,
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
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'V' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
            '대상년도',
            '시군명', '대상농협',
            '농가명', '농가주소', '농가성별',
            '작업자명', '작업시작일', '작업종료일',
            '작업일수', '작업내용',
            '제공자', '교통비', '간식비', '마스크구입비',
            '지급액(계)', '도비', '시군비', '중앙회', '지역농협',
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
