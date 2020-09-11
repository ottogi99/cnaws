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
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class StatusManpowerSupportersImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        // $currentRowNumber = $this->getRowNumber();
        // if ($currentRowNumber == 1)
        //     return;
        //
        $row = array_map('trim', $row);

        $sigun = \App\Sigun::where('name', $row[1])->first();
        $nonghyup = \App\User::with('sigun')->where('name', $row[2])->first();
        $farmer = \App\LargeFarmer::with('sigun')->with('nonghyup')
                                ->where('business_year', now()->year)
                                ->where('name', $row[3])->First();
        $supporter = \App\ManpowerSupporter::with('sigun')->with('nonghyup')
                                ->where('business_year', now()->year)
                                ->where('name', $row[4])->First();

        // $job_start_date = new Carbon($row[5]);
        // $job_end_date   = new Carbon($row[6]);
        // $working_days = $job_start_date->diffInDays($job_end_date)+1;//->format('%H:%I:%S');
        //
        // dd([$row[5], $row[6]]);
        $job_start_date = Date::excelToDateTimeObject($row[5]);
        $job_end_date = Date::excelToDateTimeObject($row[6]);
        // $job_start_date = new DateTime($row[5]);
        // $job_end_date = new DateTime($row[6]);
        $working_days = $job_start_date->diff($job_end_date)->days + 1;

        $payment_sum = $row[9] + $row[10] + $row[11];

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
                'work_detail'     => $row[7],
                'recipient'       => ($row[8] == '지원단') ? 'S' : 'F',    // S: 지원단, F: 농가
                'payment_item1'   => $row[9],
                'payment_item2'   => $row[10],
                'payment_item3'   => $row[11],
                'payment_sum'     => $payment_sum,
                'payment_do'      => $payment_sum * 0.21,
                'payment_sigun'   => $payment_sum * 0.49,
                'payment_center'  => $payment_sum * 0.2,
                'payment_unit'    => $payment_sum * 0.1,
                'remark'          => $row[12],
            ]);

            Log::debug($row);

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
            '0' => function($attribute, $value, $onFailure) {                       // 대상년도
                if ($this->is_valid_numeric($value)){
                    $business_year = Carbon::createFromDate($value);

                    if (!$business_year == now()->format('Y'))
                        $onFailure('당해년도 데이터만 입력할 수 있습니다.('. $value.')');
                } else {
                    $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.('. $value.')');
                }
            },
            '1' =>  // 시군명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $sigun = \App\Sigun::where('name', trim($value))->first();
                    if (!$sigun) {
                        $onFailure('해당 시군이 존재하지 않습니다.('. $value.')');
                    }
                },
            ],
            '2' =>  // 대상농협
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $nonghyup = \App\User::where('name', trim($value))->first();
                    if (!$nonghyup) {
                        $onFailure('해당 농협이 존재하지 않습니다.('. $value.')');
                        return;
                    }

                    $key = substr($attribute, 0, 1);
                    // $this->stack[$key] = $nonghyup->nonghyup_id;
                    $this->stack[$key] = array('nonghyup_id' => $nonghyup->nonghyup_id);
                },
            ],
            '3' =>  // 농가명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;

                    $farmer = \App\LargeFarmer::with('sigun')->with('nonghyup')
                                              ->when($nonghyup_id, function($query, $nonghyup_id) {
                                                  $query->where('nonghyup_id', $nonghyup_id);
                                                })
                                              ->where('name', trim($value))->First();

                    if (!$farmer) {
                        $onFailure('해당 농가가 존재하지 않습니다.('. $value.')');
                        return;
                    }

                    $array_farmer = array('farmer_id' => $farmer->id, 'farmer_name' => $farmer->name);
                    $this->stack[$key] = array_merge($this->stack[$key], $array_farmer);
                },
            ],
            '4' =>  // 작업자명
            [
                'required',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;

                    $supporter = \App\ManpowerSupporter::with('sigun')->with('nonghyup')
                                              ->where('nonghyup_id', $nonghyup_id)
                                              ->where('name', trim($value))->First();
                    if (!$supporter) {
                        $onFailure('해당 인력지원반이 존재하지 않습니다.('. $value.')');
                        return;
                    }

                    $array_supporter = array('supporter_id' => $supporter->id, 'supporter_name' => $supporter->name);
                    $this->stack[$key] = array_merge($this->stack[$key], $array_supporter);
                },
            ],
            '5' =>  // 작업시작일
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
            '6' => // 작업종료일
            [
                'required',
                // 'date_format:Y-m-d',
                function($attribute, $value, $onFailure) {
                    $key = substr($attribute, 0, 1);
                    $nonghyup_id = isset($this->stack[$key]['nonghyup_id']) ? $this->stack[$key]['nonghyup_id'] : null;
                    $supporter_name = isset($this->stack[$key]['supporter_name']) ? $this->stack[$key]['supporter_name'] : null;
                    $job_start_date = isset($this->stack[$key]['job_start_date']) ? $this->stack[$key]['job_start_date'] : null;

                    try {
                        $job_end_date = Date::excelToDateTimeObject($value)->format('Y-m-d');
                        $array_job_end = array('job_start_date' => $job_end_date);
                        $this->stack[$key] = array_merge($this->stack[$key], $array_job_end);
                    } catch (\Exception $e) {
                        $onFailure('날짜 형태의 데이터만 입력할 수 있습니다.('. $value.')');
                    }

                    $duplicated_items = $this->check_duplicate($supporter_name, $job_start_date, $job_end_date);
                    if (count($duplicated_items) > 0)
                    {
                        $onFailure('요청하신 농기계지원반의 작업일자가 이미 등록되어 있습니다. [작업자명: '.$supporter_name.', 작업시작일: '.$job_start_date.', 작업종료일: '.$job_end_date.']');
                    }
                }
            ],
            'required',
            '7' => 'required',
            '9' => [
                      'required',
                      function ($attribute, $value, $onFailure) {
                        if (!$this->is_valid_numeric($value))
                          $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.('. $value.')');
                      },
	                 ],
            '10' => [
                   'required',
                   function ($attribute, $value, $onFailure) {
                     if (!$this->is_valid_numeric($value))
                       $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.('. $value.')');
                   },
                 ],
            '11' => [
                     'required',
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
        ];
    }

    public function customValidationAttributes()
    {
        return [
          '0' => '대상년도',
          '1' => '시군명',
          '2' => '대상농협',
          '3' => '농가명',
          '4' => '작업자명',
          '5' => '작업시작일',
          '6' => '작업종료일',
          '7' => '제공자',
          '8' => '교통비',
          '9' => '간식비',
          '10' => '마스크구압비',
          '11' => '비고',
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

    private function check_duplicate($supporter_name, $job_start_date, $job_end_date)
    {
        // 중복 체크(지원반의 id가 아니라 이름으로 검색하여야 한다.)
        return $duplicated_items = \App\StatusManpowerSupporter::with('nonghyup')->with('farmer')->with('supporter')
                                      ->join('users', 'status_manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                                      ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                                      ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                                      ->select(
                                          'status_manpower_supporters.*',
                                          'users.name as nonghyup_name',
                                          'large_farmers.name as farmer_name', 'large_farmers.address as farmer_address',
                                          'manpower_supporters.name as supporter_name',
                                        )
                                      ->where('status_manpower_supporters.business_year', now()->year)
                                      // ->where('status_manpower_supporters.supporter_id', $supporter_id)
                                      // id 중복이 아니라 이름 중복을 검색하여야 한다.
                                      ->where('manpower_supporters.name', $supporter_name)
                                      ->where(function ($query) use ($job_start_date, $job_end_date) {
                                          $query->whereBetween('status_manpower_supporters.job_start_date', [$job_start_date, $job_end_date])
                                                ->orWhereBetween('status_manpower_supporters.job_end_date', [$job_start_date, $job_end_date]);
                                      })
                                      ->get();
                                      // ->exists())
    }
}
