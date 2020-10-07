<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class MatchOldPassword implements Rule
{
    public function passes($attribute, $value)
    {
        return Hash::check($value, auth()->user()->password);
    }

    public function message()
    {
        return '이전 :attribute 가 일치하지 않습니다.';
    }
}
