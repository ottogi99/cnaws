<?php

namespace App\Imports;

use App\SmallFarmer;
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
use Illuminate\Validation\Rule;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SmallFarmersImport implements ToModel, WithValidation, WithStartRow, SkipsOnFailure, SkipsOnError, WithBatchInserts, WithChunkReading//, WithEvents
{
    use Importable, SkipsFailures, SkipsErrors;
    // use RemembersRowNumber;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

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
            // 꼭 아래와 같은 return new SmallFarmer([...]) 형태로 반환해라 아니면 오류난다.
            // return new SmallFarmer([
            //     'business_year'   => $row[0],
            //     'sigun_code'      => $sigun->code, //$row[0],
            //     'nonghyup_id'     => $nonghyup->nonghyup_id, //$row[1],
            //     'name'            => $row[2],
            //     'age'             => $row[3],
            //     'sex'             => ($row[4] == '남' ? 'M' : 'F'),
            //     'address'         => $row[5],
            //     'contact'         => $row[6],
            //     'sum_acreage'     => ($row[7] + $row[8] + $row[9]),
            //     'acreage1'        => $row[7],
            //     'acreage2'        => $row[8],
            //     'acreage3'        => $row[9],
            //     'remark'          => $row[10],
            // ]);

            $farmer = new SmallFarmer([
                'business_year'   => $row[0],                 //$row[0]
                'sigun_code'      => $sigun->code,            //$row[1],
                'nonghyup_id'     => $nonghyup->nonghyup_id,  //$row[2],
                'name'            => $row[3],
                'age'             => $row[4],
                'sex'             => ($row[5] == '남' ? 'M' : 'F'),
                'address'         => $row[6],
                'contact'         => $row[7],
                'sum_acreage'     => ($row[8] + $row[9] + $row[10]),
                'acreage1'        => $row[8],
                'acreage2'        => $row[9],
                'acreage3'        => $row[10],
                'remark'          => $row[11],
            ]);

            return $farmer;
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
            '0' => function($attribute, $value, $onFailure) {
                if ($this->is_valid_numeric($value)){
                    $business_year = Carbon::createFromDate($value);

                    if (!$business_year == now()->format('Y'))
                        $onFailure('당해년도 데이터만 입력할 수 있습니다.: '.$value);
                } else {
                    $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
                }
            },
            '1' => function($attribute, $value, $onFailure) {
                $sigun = \App\Sigun::where('name', trim($value))->first();
                if (!$sigun) {
                    $onFailure('해당 시군이 존재하지 않습니다: '.$value);
                    return;
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
                    return;
                }

                $user = auth()->user();
                if (!$user->isAdmin() && $user->nonghyup_id != $nonghyup->nonghyup_id) {
                    $onFailure('타 농협의 데이터는 등록할 수 없습니다.: '.$value);
                    return;
                }
            },
            '4' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            },
            '8' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            },
            '9' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            },
            '10' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            }
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

    // public function registerEvents(): array
    // {
    //     return [
    //         ImportFailed::class => function(ImportFailed $event) {
    //             $this->importedBy->notify(new ImportHasFailedNotification);
    //         },
    //     ];
    // }
}
