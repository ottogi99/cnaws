<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'sigun_code' => ['required'],
          'user_id' => ['required','unique:users','regex:/^[A-Za-z]{1}[A-Za-z0-9_]{3,11}$/'], // 아이디 4자리~12자리
          'password' => ['required','confirmed','regex:/^.*(?=.{8,17})(?=.*[0-9])(?=.*[a-zA-Z]).*$/'],    // 영문,숫자 혼용해서 8~17자리
          'is_admin' => ['required'],
          // 'name' => ['required','min:3','max:255'],
          // 'address' => ['required','max:255'],
          // 'contact' => ['required','regex:/^01(0|1|6|7|8|9)([0-9]{3,4})([0-9]{4})$/'],        // 전화번호 형식
          // 'contact' => ['required','regex:/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/'],        // 전화번호 형식
          // 'representative' => ['required','min:2','max:15'],
        ];
    }

    // 사용자 정의 오류메시지 정의
    public function messages()
    {
        return [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'unique'  => '이미 등록된 :attribute 항목이 존재합니다',
            'regex'   => ':attribute가 유효한 형식이 아닙니다',
            'min' => ':attribute은(는) 최소 :min 글자 이상이 필요합니다.',
            'max' => ':attrubute은(는) 최대 :max 글자를 초과할 수 없습니다',
            'confirmed' => ':attribute가 일치하지 않습니다.',
        ];
    }

    public function attributes()
    {
        return [
            'sigun_code'      => '시군항목',
            'user_id'         => '농협ID',
            'password'        => '비밀번호',
            'is_admin'        => '권한',
            // 'name'            => '농협명',
            // 'address'         => '도로명 주소',
            // 'contact'         => '연락처',
            // 'representative'  => '대표자',
        ];
    }
}
