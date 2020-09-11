<?php

namespace App\Exports;

use App\PerformanceOperating;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;

class PerformanceOperatingExport implements FromArray, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $year;
    protected $sigun_code;
    protected $nonghyup_id;

    public function __construct(array $parameters)
    {
        $this->year = $parameters[0];
        $this->sigun_code = $parameters[1];
        $this->nonghyup_id = $parameters[2];
    }

    public function array(): array
    {
        $year = $this->year;
        $sigun_code = $this->sigun_code;
        $nonghyup_id = $this->nonghyup_id;

        $raw = sprintf("CALL GetPerformanceOperating('%s', '%s', '%s')", $year, $sigun_code, $nonghyup_id);
        $rows = DB::select(DB::raw($raw));

        return $rows;
    }

    public function map($row): array
    {
        return [
            [
                $row->year,
                $row->sigun_name,
                $row->nonghyup_name,
                ($row->small_farmer_number) ? $row->small_farmer_number : '0',
                ($row->machine_supporter_number) ? $row->machine_supporter_number : '0',
                '',
                ($row->machine_supporter_working_area) ? $row->machine_supporter_working_area : '0',
                ($row->large_farmer_number) ? $row->large_farmer_number : '0',
                ($row->manpower_supporter_number) ? $row->manpower_supporter_number : '0',
                '',
                ($row->manpower_supporter_working_days) ? $row->manpower_supporter_working_days : '0',
                '',
                '',
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'H' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            // 'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            // 'V' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function headings(): array
    {
        return [
            '대상년도',
            '시군명', '대상농협',
            '농가모집(명)', '지원단모집(명)', '지원농가(호)', '면적(㏊)',
            '농가모집(명)', '지원단모집(명)', '지원농가(호)', '지원인력(명)',
            '교육횟수', '홍보횟수'
        ];
    }
}
