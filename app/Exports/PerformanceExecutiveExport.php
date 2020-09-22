<?php

namespace App\Exports;

use App\PerformanceExecutive;
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
use Illuminate\Support\Facades\Log;

class PerformanceExecutiveExport implements FromArray, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
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

        $raw = sprintf("CALL GetPerformanceExecutive('%s', '%s', '%s')", $year, $sigun_code, $nonghyup_id);

        Log::debug($raw);
        $rows = DB::select(DB::raw($raw));

        return $rows;
    }

    public function map($row): array
    {
        return [
            [
                $row->business_year,
                $row->sigun_name,
                $row->nonghyup_name,
                ($row->budget_sum) ? $row->budget_sum : '0',
                ($row->budget_do) ? $row->budget_do : '0',
                ($row->budget_sigun) ? $row->budget_sigun : '0',
                ($row->budget_center) ? $row->budget_center : '0',
                ($row->budget_unit) ? $row->budget_unit : '0',
                ($row->payment_sum) ? $row->payment_sum : '0',
                ($row->payment_do) ? $row->payment_do : '0',
                ($row->payment_sigun) ? $row->payment_sigun : '0',
                ($row->payment_center) ? $row->payment_center : '0',
                ($row->payment_unit) ? $row->payment_unit : '0',
                ($row->balance_sum) ? $row->balance_sum : '0',
                ($row->balance_do) ? $row->balance_do : '0',
                ($row->balance_sigun) ? $row->balance_sigun : '0',
                ($row->balance_center) ? $row->balance_center : '0',
                ($row->balance_unit) ? $row->balance_unit : '0',
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
            '예산액-합계(100%)', '예산액-도비(21%)', '예산액-시군비(49%)', '예산액-중앙회(20%)', '예산액-지역농협(10%)',
            '집행액-합계(100%)', '집행액-도비(21%)', '집행액-시군비(49%)', '집행액-중앙회(20%)', '집행액-지역농협(10%)',
            '잔액-합계(100%)', '잔액-도비(21%)', '잔액-시군비(49%)', '잔액-중앙회(20%)', '잔액-지역농협(10%)',
        ];
    }
}
