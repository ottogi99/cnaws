<?php

namespace App\Imports;

// use App\User;
use Illuminate\Support\Facades\Hash;
// // use Maatwebsite\Excel\Concerns\ToModel;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\Importable;

use App\User;
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


class UsersImport implements ToModel, WithValidation, WithStartRow, SkipsOnFailure, SkipsOnError//, WithBatchInserts, WithChunkReading//, WithEvents
{
    use Importable, SkipsFailures, SkipsErrors;
    // use RemembersChunkOffset;

    private $rows = 0;

    public function model(array $row)
    {
        if (!isset($row[10])) {
            return null;
        }

        ++$this->rows;

        // 공백 제거
        $row = array_map(function($value) {
            return trim($value);
        }, $row);

        // row[0] :시군명
        // row[1] : 농협명
        // row[2] : 농협ID (유효성 검사)
        // row[3] : 비밀번호
        // row[4] : 주소
        // row[5] : 연락처
        // row[6] : 대표자
        // row[7] : 사용자 활성화
        // row[8] : 입력 허용
        // row[9] : 사용자 권한
        // row[10] : 일련번호

        $sigun = \App\Sigun::where('name', $row[0])->first();

        return new User([
            'sigun_code'        => $sigun->code,
            'name'              => $row[1],
            'nonghyup_id'       => $row[2],
            'password'          => Hash::make($row[3]),
            'address'           => $row[4],
            'contact'           => $row[5],
            'representative'    => $row[6],
            'activated'         => ($row[7] == '활성' ? 1 : 0),
            'is_input_allowed'  => ($row[8] == '허용' ? 1 : 0),
            'is_admin'          => ($row[9] == '관리자' ? 1 : 0),
            'sequence'          => $row[10],
        ]);
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
            '0' => [
                      'required',
                      function($attribute, $value, $onFailure) {
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
                  ],
            '1' => ['required', 'min:3', 'max:10'],                                   //농협명
            '2' => ['required', 'regex:/^[A-Za-z]{1}[A-Za-z0-9_]{3,11}$/'],           //농협ID
            '3' => ['required', 'regex:/^.*(?=.{8,17})(?=.*[0-9])(?=.*[a-zA-Z]).*$/'],
            // 사용자 활성화
            '7' => Rule::in(['활성','비활성']),
            // 입력허용
            '8' => Rule::in(['허용', '불가']),
            // 사용자 권한
            '9' => Rule::in(['관리자', '사용자']),
            // 순서
            '10' => function ($attribute, $value, $onFailure) {
                if (!$this->is_valid_numeric($value))
                  $onFailure('숫자 형식의 데이터만 입력할 수 있습니다.: '.$value);
            }
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => ':attribute 값은 필수항목입니다.',     // 시군명
            '1.required' => ':attribute 값은 필수항목입니다.',     // 농협명
            '2.required' => ':attribute 값은 필수항목입니다.',     // 농협ID
            '3.required' => ':attribute 값은 필수항목입니다.',     // 비밀번호
            '4.required' => ':attribute 값은 필수항목입니다.',     // 주소
            '5.required' => ':attribute 값은 필수항목입니다.',     // 연락처
            '6.required' => ':attribute 값은 필수항목입니다.',     // 대표자
            '7.required' => ':attribute 값은 필수항목입니다.',     // 사용자활성화
            '8.required' => ':attribute 값은 필수항목입니다.',     // 입력 허용
            '9.required' => ':attribute 값은 필수항목입니다.',     // 사용자 권한
            '10.required' => ':attribute 값은 필수항목입니다.',    // 순서
        ];
    }

    public function customValidationAttributes()
    {
        return [
          '0' => '시군명',
          '1' => '농협명',
          '2' => '농협ID',
          '3' => '비밀번호',
          '4' => '주소',
          '5' => '연락처',
          '6' => '대표자',
          '7' => '사용자 활성화',
          '8' => '입력 허용',
          '9' => '사용자 권한',
          '10' => '순서',
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
