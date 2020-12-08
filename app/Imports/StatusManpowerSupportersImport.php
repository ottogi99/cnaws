<?php

namespace App\Imports;

use App\StatusManpowerSupporter;
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

class StatusManpowerSupportersImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, SkipsOnError//, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    private $rows = 0;

    public function model(array $row)
    {
        // 칼럼수가 맞지 않으면
        if (count($row) != 15) {
            Log::debug('엑셀 칼럼수가 맞지 않습니다. 필요(15), 입력('.count($row).')');
            return null;
        }

        ++$this->rows;

        // $currentRowNumber = $this->getRowNumber();
        // if ($currentRowNumber == 1)
        //     return;
        //
        $row = array_map('trim', $row);

        $farmer_birth = Date::excelToDateTimeObject($row[4])->format('Y-m-d');
        $supporter_birth = Date::excelToDateTimeObject($row[6])->format('Y-m-d');

        // $sigun = \App\Sigun::where('name', $row[1])->first();
        // $nonghyup = \App\User::with('sigun')->where('name', $row[2])->first();
        $sigun = \App\Sigun::where('name', $row[1])->first();
        $nonghyup = \App\User::where('sigun_code', $sigun->code)->where('name', $row[2])->first();

        $farmer = \App\LargeFarmer::with('sigun')->with('nonghyup')
                                ->where('business_year', now()->year)
                                // 2020-12-08 동명인이 다른 시군에도 등명으로 등록(생년월일 동일)되어 supporter_id가 변경됨
                                ->where('nonghyup_id', $nonghyup->id)
                                ->where('name', $row[3])
                                ->where('birth', $farmer_birth)
                                ->first();
        $supporter = \App\ManpowerSupporter::with('sigun')->with('nonghyup')
                                ->where('business_year', now()->year)
                                // 2020-12-08 동명인이 다른 시군에도 등명으로 등록(생년월일 동일)되어 supporter_id가 변경됨
                                ->where('nonghyup_id', $nonghyup->id)
                                ->where('name', $row[5])
                                ->where('birth', $supporter_birth)
                                ->first();

        $job_start_date = Date::excelToDateTimeObject($row[7]);
        $job_end_date = Date::excelToDateTimeObject($row[8]);
        $working_days = $job_start_date->diff($job_end_date)->days + 1;

        // 12-04 교통비, 간식비, 마스크구입비(필수항목 아님) 빈 값 입력시 오류
        $row[11] = empty($row[11]) ? 0 : $row[11];
        $row[12] = empty($row[12]) ? 0 : $row[12];
        $row[13] = empty($row[13]) ? 0 : $row[13];
        $payment_sum = $row[11] + $row[12] + $row[13];

        $payment_do = floor($payment_sum * 0.21);
        $payment_sigun = floor($payment_sum * 0.49);
        $payment_center = floor($payment_sum * 0.2);
        $payment_unit = floor($payment_sum * 0.1);
        $payment_diff = $payment_sum - ($payment_do + $payment_sigun + $payment_center + $payment_unit);

        if ($sigun && $nonghyup && $farmer && $supporter) {
            $row = new StatusManpowerSupporter([
                'business_year'   => $row[0],
                'sigun_code'      => $sigun->code,
                'nonghyup_id'     => $nonghyup->nonghyup_id,
                'farmer_id'       => $farmer->id,
                'supporter_id'    => $supporter->id,
                'job_start_date'  => $job_start_date->format('Y-m-d'),
                'job_end_date'    => $job_end_date->format('Y-m-d'),
                'working_days'    => $working_days,
                'work_detail'     => $row[9],
                'recipient'       => ($row[10] == '지원단') ? 'S' : 'F',    // S: 지원단, F: 농가
                'payment_item1'   => $row[11],
                'payment_item2'   => $row[12],
                'payment_item3'   => $row[13],
                'payment_sum'     => $payment_sum,
                'payment_do'      => $payment_do + $payment_diff,
                'payment_sigun'   => $payment_sigun,
                'payment_center'  => $payment_center,
                'payment_unit'    => $payment_unit,
                'remark'          => $row[14],
            ]);

            return $row;
        } else {
            Log::debug('시군: '.$sigun.'농협: '.$nonghyup.'농가: '.$farmer.'지원반: '.$supporter);
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
                function($attribute, $value, $onFailure) {                       // 대상년도
                    $key = substr($attribute, 0, 1);
                    $this->stack[$key] = [];

                    if ($this->is_valid_numeric($value)){
                        $business_year = Carbon::createFromDate($value);

                        if (!$business_year == now()->format('Y')){
                            $onFailure('당해년도 데이터만 입력할 수 있습니다.: '.$value);
                            return;
                        }
                    } else {
                        $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
                        return;
                    }

                    $this->stack[$key] = array('business_year' => $value);
                },
            ],
            '1' =>  // 시군명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);

                    $sigun = \App\Sigun::where('name', trim($value))->first();
                    if (!$sigun) {
                        $onFailure('해당 시군이 존재하지 않습니다.('. $value.')');
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
            '2' =>  // 대상농협
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);

                    // 2020.11.30 동일한 이름의 농협이 존재하여 시군코드까지 함께 조회
                    $nonghyup = \App\User::where('sigun_code', $this->stack[$key]['sigun'])->where('name', trim($value))->first();
                    if (!$nonghyup) {
                        $onFailure('해당 농협이 존재하지 않습니다.('. $value.')');
                        return;
                    }

                    $this->stack[$key] = array_merge($this->stack[$key], array('nonghyup_id' => $nonghyup->nonghyup_id));

                    $user = auth()->user();
                    if (!$user->isAdmin() && $user->nonghyup_id != $nonghyup->nonghyup_id) {
                        $onFailure('타 농협의 데이터는 등록할 수 없습니다.: '.$value);
                        return;
                    }
                },
            ],
            '3' =>  // 농가명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $this->stack[$key] = array_merge($this->stack[$key], array('farmer_name' => $value));
                },
            ],
            '4' =>  // 농가 생년월일
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;
                    $name = isset($this->stack[$key]['farmer_name']) ? $this->stack[$key]['farmer_name'] : null;

                    try {
                        $birth = Date::excelToDateTimeObject($value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $onFailure('날짜 형태의 데이터만 입력할 수 있습니다.('. $value.')');
                        return;
                    }

                    $farmer = \App\LargeFarmer::with('sigun')->with('nonghyup')
                                              ->when($nonghyup_id, function($query, $nonghyup_id) {
                                                  $query->where('nonghyup_id', $nonghyup_id);
                                                })
                                              ->where('name', trim($name))
                                              ->where('birth', trim($birth))
                                              ->first();

                    if (!$farmer) {
                        $onFailure('해당 농가가 존재하지 않습니다.( 농가명: '.$name.', 생년월일: '.$birth.' )');
                        return;
                    }

                    $this->stack[$key] = array_merge($this->stack[$key], array('farmer_id' => $farmer->id));
                },
            ],
            '5' =>  // 작업자명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $this->stack[$key] = array_merge($this->stack[$key], array('supporter_name' => $value));
                },
            ],
            '6' =>  // 작업자 생년월일
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;
                    $name = isset($this->stack[$key]['supporter_name']) ? $this->stack[$key]['supporter_name'] : null;

                    try {
                        $birth = Date::excelToDateTimeObject($value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $onFailure('날짜 형태의 데이터만 입력할 수 있습니다.('. $value.')');
                        return;
                    }

                    $supporter = \App\ManpowerSupporter::where('nonghyup_id', $nonghyup_id)
                                              ->where('name', trim($name))
                                              ->where('birth', trim($birth))
                                              ->first();

                    if (!$supporter) {
                        $onFailure('해당 인력지원반이 존재하지 않습니다.( 성명: '.$name.', 생년월일: '.$birth.' )');
                        return;
                    }

                    $this->stack[$key] = array_merge($this->stack[$key], array('supporter_id' => $supporter->id));
                },
            ],
            '7' =>  // 작업시작일
            [
                'required',
                // 'date_format:Y-m-d',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    try {
                        $array_job_start = array('job_start_date' => Date::excelToDateTimeObject($value)->format('Y-m-d'));
                        $this->stack[$key] = array_merge($this->stack[$key], $array_job_start);
                    } catch (\Exception $e) {
                        $onFailure('날짜 형태의 데이터만 입력할 수 있습니다.('. $value.')');
                    }
                }
            ],
            '8' => // 작업종료일
            [
                'required',
                // 'date_format:Y-m-d',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;
                    $supporter_name = isset($this->stack[$key]['supporter_name']) ? $this->stack[$key]['supporter_name'] : null;
                    $supporter_id = isset($this->stack[$key]['supporter_id']) ? $this->stack[$key]['supporter_id'] : null;
                    $job_start_date = isset($this->stack[$key]['job_start_date']) ? $this->stack[$key]['job_start_date'] : null;

                    try {
                        $job_end_date = Date::excelToDateTimeObject($value)->format('Y-m-d');
                        $array_job_end = array('job_end_date' => $job_end_date);
                        $this->stack[$key] = array_merge($this->stack[$key], $array_job_end);
                    } catch (\Exception $e) {
                        $onFailure('날짜 형태의 데이터만 입력할 수 있습니다.('. $value.')');
                        return;
                    }

                    // 2020-11-11 동명이인 허용 ($supporter_name --> $supporter_id)
                    $duplicated_items = $this->check_duplicate($supporter_id, $job_start_date, $job_end_date);
                    if (count($duplicated_items) > 0)
                    {
                        $onFailure('요청하신 인력지원반의 작업일자가 이미 등록되어 있습니다. [작업자명: '.$supporter_name.', 작업시작일: '.$job_start_date.', 작업종료일: '.$job_end_date.']');
                    }
                }
            ],
            '9' => 'required',
            '11' => [
                      // 'required',
                      function ($attribute, $value, $onFailure) {
                        if (!$this->is_valid_numeric($value))
                          $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.('. $value.')');
                      },
	                 ],
            '12' => [
                   // 'required',
                   function ($attribute, $value, $onFailure) {
                     if (!$this->is_valid_numeric($value))
                       $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.('. $value.')');
                   },
                 ],
            '13' => [
                     function ($attribute, $value, $onFailure) {
                       if (!$this->is_valid_numeric($value))
                         $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.('. $value.')');
                     },
                   ],
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
            '8.required' => ':attribute 값은 필수항목입니다.',
            '9.required' => ':attribute 값은 필수항목입니다.',
            '10.required' => ':attribute 값은 필수항목입니다.',
            '11.required' => ':attribute 값은 필수항목입니다.',
            '12.required' => ':attribute 값은 필수항목입니다.',
        ];
    }

    public function customValidationAttributes()
    {
        return [
          '0' => '대상년도',
          '1' => '시군명',
          '2' => '대상농협',
          '3' => '농가명',
          '4' => '생년월일',
          '5' => '작업자명',
          '6' => '생년월일',
          '7' => '작업시작일',
          '8' => '작업종료일',
          '9' => '작업내용',
          '10' => '제공자',
          '11' => '교통비',
          '12' => '간식비',
          '13' => '마스크구입비',
          '14' => '비고',
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

    private function check_duplicate($supporter_id, $job_start_date, $job_end_date)
    {
        return $duplicated_items = \App\StatusManpowerSupporter::with('nonghyup')->with('farmer')->with('supporter')
                                      ->join('users', 'status_manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                                      ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                                      ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                                      ->select(
                                          'status_manpower_supporters.*',
                                          'users.name as nonghyup_name',
                                          'large_farmers.name as farmer_name', 'large_farmers.address as farmer_address',
                                          'manpower_supporters.name as supporter_name'
                                        )
                                      ->where('status_manpower_supporters.business_year', now()->year)
                                      ->where('manpower_supporters.id', $supporter_id)
                                      ->where(function ($query) use ($job_start_date, $job_end_date) {
                                              $query->whereRaw('
                                                (status_manpower_supporters.job_start_date <= ? and ? <= status_manpower_supporters.job_end_date)
                                                or
                                                (status_manpower_supporters.job_start_date <= ? and ? <= status_manpower_supporters.job_end_date)
                                                or
                                                (status_manpower_supporters.job_start_date > ? and ? > status_manpower_supporters.job_end_date)
                                              ', [$job_start_date, $job_start_date, $job_end_date, $job_end_date, $job_start_date, $job_end_date]);
                                      })->get();
    }
}
