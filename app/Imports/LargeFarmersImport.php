<?php

namespace App\Imports;

use App\LargeFarmer;
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
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class LargeFarmersImport implements ToModel, WithValidation, WithStartRow, SkipsOnFailure, SkipsOnError//, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    private $rows = 0;

    public function model(array $row)
    {
        // 칼럼수가 맞지 않으면
        if (count($row) != 13) {
            Log::debug('엑셀 칼럼수가 맞지 않습니다. 필요(13), 입력('.count($row).')');
            return null;
        }

        ++$this->rows;

        $row = array_map(function($value) {
            return trim($value);
        }, $row);

        $sigun = \App\Sigun::where('name', $row[1])->first();
        $nonghyup = \App\User::where('name', $row[2])->first();

        if ($sigun && $nonghyup) {
            $farmer = new LargeFarmer([
                'business_year'   => $row[0],
                'sigun_code'      => $sigun->code, //$row[1],
                'nonghyup_id'     => $nonghyup->nonghyup_id, //$row[2],
                'name'            => $row[3],
                // 'age'             => $row[4],
                'birth'           => Date::excelToDateTimeObject($row[4])->format('Y-m-d'),
                'sex'             => ($row[5] == '남' ? 'M' : 'F'),
                'address'         => $row[6],
                'contact'         => $row[7],
                'acreage'         => $row[8],
                'cultivar'        => $row[9],
                'bank_name'       => $row[10],
                'bank_account'    => $row[11],
                'remark'          => $row[12],
            ]);

            return $farmer;
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
            '0' => function($attribute, $value, $onFailure) {
                $key = substr($attribute, 0, 1);
                $this->stack[$key] = [];

                if ($this->is_valid_numeric($value)){
                    $business_year = Carbon::createFromDate($value);

                    if (!$business_year == now()->format('Y')) {
                        $onFailure('당해년도 데이터만 입력할 수 있습니다.: '.$value);
                        return;
                    }
                } else {
                    $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
                    return;
                }

                $this->stack[$key] = array('business_year' => $value);
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

                // 중복처리
                $key = substr($attribute, 0, 1);
                $this->stack[$key] = array_merge($this->stack[$key], array('nonghyup_id' => $nonghyup->nonghyup_id));
            },
            '3' =>  // 성명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $this->stack[$key] = array_merge($this->stack[$key], array('name' => $value));
                },
            ],
            '4' => // 생년월일
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $birth = '';
                    try {
                        $birth = Date::excelToDateTimeObject($value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $onFailure('날짜 형태의 데이터만 입력할 수 있습니다.('. $value.')');
                        return;
                    }

                    $key = substr($attribute, 0, 1);
                    $business_year = isset($this->stack[$key]['business_year']) ? $this->stack[$key]['business_year'] : null;
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;
                    $name = isset($this->stack[$key]['name']) ? $this->stack[$key]['name'] : null;

                    // 중복검사
                    $duplicated_items = $this->check_duplicate($business_year, $nonghyup_id, $name, $birth);
                    if (count($duplicated_items) > 0)
                    {
                        $onFailure('요청하신 농가가 이미 등록되어 있습니다. [농가: '.$name.', 생년월일: '.$birth.']');
                    }
                },
            ],
            '4' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            },
            '8' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
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

    private function check_duplicate($business_year, $nonghyup_id, $name, $birth)
    {
        // 중복 체크(지원반의 id가 아니라 이름으로 검색하여야 한다.)
        return $duplicated_items = \App\LargeFarmer::where('business_year', $business_year)
                                      ->where('nonghyup_id', $nonghyup_id)
                                      ->where('name', $name)
                                      ->where('birth', $birth)
                                      ->get();
                                      // ->exists())
    }
}
