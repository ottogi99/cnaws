<?php

namespace App\Imports;

use App\MachineSupporter;
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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MachineSupportersImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        $row = array_map(function($value) {
            return trim($value);
        }, $row);

        $sigun = \App\Sigun::where('name', $row[1])->first();
        $nonghyup = \App\User::where('name', $row[2])->first();

        if ($sigun && $nonghyup) {
            $supporter = new MachineSupporter([
                'business_year'   => $row[0],
                'sigun_code'      => $sigun->code,
                'nonghyup_id'     => $nonghyup->nonghyup_id,
                'name'            => $row[3],
                'age'             => $row[4],
                'sex'             => ($row[5] == '남' ? 'M' : 'F'),
                'address'         => $row[6],
                'contact'         => $row[7],
                'machine1'        => $row[8],
                'machine2'        => $row[9],
                'machine3'        => $row[10],
                'machine4'        => $row[11],
                'bank_name'       => $row[12],
                'bank_account'    => $row[13],
                'remark'          => $row[14],
            ]);

            return $supporter;
        }

        return null;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '0' => [ function($attribute, $value, $onFailure) {
                if ($this->is_valid_numeric($value)){
                    $business_year = Carbon::createFromDate($value);

                    if (!$business_year == now()->format('Y'))
                        $onFailure('당해년도 데이터만 입력할 수 있습니다.: '.$value);
                } else {
                    $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
                }
            }],
            '1' => function($attribute, $value, $onFailure) {
                $sigun = \App\Sigun::where('name', trim($value))->first();
                if (!$sigun) {
                    $onFailure('해당 시군이 존재하지 않습니다: '.$value);
                }

                $user = auth()->user();
                if (!$user->isAdmin() && $user->sigun_code != $sigun->code) {
                    $onFailure('타 지역의 데이터는 등록할 수 없습니다.: '.$value);
                    return;
                }
            },
            '2' => function($attribute, $value, $onFailure) {
                $nonghyup = \App\User::where('name', trim($value))->first();
                if (!$nonghyup) {
                    $onFailure('해당 농협이 존재하지 않습니다: '.$value);
                }

                $user = auth()->user();
                if (!$user->isAdmin() && $user->nonghyup_id != $nonghyup->nonghyup_id) {
                    $onFailure('타 농협의 데이터는 등록할 수 없습니다.: '.$value);
                    return;
                }
            },
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
