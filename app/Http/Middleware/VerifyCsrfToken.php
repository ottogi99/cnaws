<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
     // 도로명 주소 연동하는 경우 419 error가 발생하여 일단
     // 도로명 주소를 입력하는 경우 csrf 제외함
    protected $except = [
        '*'
    ];
    // 'apis/*',
    // 'users/*',
    // 'small_farmers/*',
    // 'small_farmer_supporters/*',
    // '/larger_farmers/*',
    // 'larger_farmer_supporters/*'
    // 'apis/*',
    // 'users/*'

}
