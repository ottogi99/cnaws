<?php

namespace App\Imports;

use App\StatusOperatingCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class StatusOperatingCostsImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, SkipsOnError//, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    private $rows = 0;

    public function model(array $row)
    {
        // 칼럼수가 맞지 않으면
        if (count($row) != 9) {
            Log::debug('엑셀 칼럼수가 맞지 않습니다. 필요(9), 입력('.count($row).')');
            return null;
        }

        ++$this->rows;

        $row = array_map(function($value) {
            return trim($value);
        }, $row);

        // $sigun = \App\Sigun::where('name', $row[1])->first();
        // $nonghyup = \App\User::where('name', $row[2])->first();
        $sigun = \App\Sigun::where('name', $row[1])->first();
        $nonghyup = \App\User::where('sigun_code', $sigun->code)->where('name', $row[2])->first();

        $payment_sum = $row[7];
        $payment_do = floor($payment_sum * 0.21);
        $payment_sigun = floor($payment_sum * 0.49);
        $payment_center = floor($payment_sum * 0.2);
        $payment_unit = floor($payment_sum * 0.1);
        $payment_diff = $payment_sum - ($payment_do + $payment_sigun + $payment_center + $payment_unit);

        if ($sigun && $nonghyup) {
            $row = new StatusOperatingCost([
                'business_year'   => $row[0],
                'sigun_code'      => $sigun->code,
                'nonghyup_id'     => $nonghyup->nonghyup_id,
                'payment_date'    => Date::excelToTimestamp($row[3]),
                'item'            => $row[4],
                'target'          => $row[5],
                'detail'          => $row[6],
                'payment_sum'     => $payment_sum,
                'payment_do'      => $payment_do + $payment_diff,
                'payment_sigun'   => $payment_sigun,
                'payment_center'  => $payment_center,
                'payment_unit'    => $payment_unit,
                'remark'          => $row[8],
            ]);

            return $row;
        }

        return null;
    }

    public function startRow(): int
    {
        return 2;
    }

    // public function batchSize(): int
    // {
    //     return 1000;
    // }
    //
    // public function chunkSize(): int
    // {
    //     return 1000;
    // }

    public function rules(): array
    {
        return [
            '0' =>
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $this->stack[$key] = [];

                    if ($this->is_valid_numeric($value)){
                        // 2021.01.04. 당해년도 데이터가 아니라도 입력할수 있도록 수정
                        // $business_year = Carbon::createFromDate($value)->year;
                        // if (!($business_year == now()->format('Y'))){
                        //     $onFailure('당해년도 데이터만 입력할 수 있습니다.: '.$value);
                        //     return;
                        // }
                    } else {
                        $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
                        return;
                    }

                    $this->stack[$key] = array('business_year' => $value);
                },
            ],
            '1' =>
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);

                    $sigun = \App\Sigun::where('name', trim($value))->first();
                    if (!$sigun) {
                        $onFailure('해당 시군이 존재하지 않습니다: '.$value);
                        return;
                    }

                    $this->stack[$key] = array_merge($this->stack[$key], array('sigun' => $sigun->code));

                    $user = auth()->user();
                    if (!$user->isAdmin() && $user->sigun_code != $sigun->code) {
                        $onFailure('타 지역의 데이터는 등록할 수 없습니다.: '.$value);
                        return;
                    }
                },
            ],
            '2' =>
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    // $nonghyup = \App\User::where('name', trim($value))->first();
                    $nonghyup = \App\User::where('sigun_code', $this->stack[$key]['sigun'])->where('name', trim($value))->first();

                    if (!$nonghyup) {
                        $onFailure('해당 농협이 존재하지 않습니다: '.$value);
                        return;
                    }

                    $user = auth()->user();
                    if (!$user->isAdmin() && $user->nonghyup_id != $nonghyup->nonghyup_id) {
                        $onFailure('타 농협의 데이터는 등록할 수 없습니다.: '.$value);
                        return;
                    }
                },
            ],
            '7' => ['required', function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            }],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => ':attribute 값은 필수항목입니다.',
            '1.required' => ':attribute 값은 필수항목입니다.',
            '2.required' => ':attribute 값은 필수항목입니다.',
            '3.required' => ':attribute 값은 필수항목입니다.',
            '4.required' => ':attribute 값은 필수항목입니다.',
            '5.required' => ':attribute 값은 필수항목입니다.',
            '6.required' => ':attribute 값은 필수항목입니다.',
            '7.required' => ':attribute 값은 필수항목입니다.',
        ];
    }


    public function customValidationAttributes()
    {
        return [
          '0' => '대상년도',
          '1' => '시군명',
          '2' => '대상농협',
          '3' => '지출일자',
          '4' => '지출항목',
          '5' => '지급대상',
          '6' => '지출내용',
          '7' => '지급액(원)',
        ];
    }

    protected function is_valid_numeric($value) : bool
    {
        if (!empty($value)) {
            if (!is_numeric($value)) {
                return false;
            }
        }
        return true;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
