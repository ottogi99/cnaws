<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;


class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $nonghyup = \App\User::with('sigun')->findOrFail($id);

        return view('users.password', compact('nonghyup'));
    }

    public function store(Request $request, $id)
    {
        $rules = [
            'password' => ['required', new MatchOldPassword],    // 영문,숫자 혼용해서 8~17자리
            'newPassword' => ['required','same:passwordConfirm','regex:/^.*(?=.{8,17})(?=.*[0-9])(?=.*[a-zA-Z]).*$/'],    // 영문,숫자 혼용해서 8~17자리
            'passwordConfirm' => ['required']
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'regex'   => ':attribute은(는) 영문, 숫자 혼용한 8~17 문자이어야 합니다',
            'same' => ':attribute가 일치하지 않습니다.',
        ];

        $attributes = [
            'password'        => '현재 비밀번호',
            'newPassword'     => '새 비밀번호',
            'passwordConfirm' => '비밀번호 확인'
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $payload = array_merge($request->all(), [
          'password' => bcrypt($request->input('newPassword'))    // 비밀번호는 별도로 처리
          // 'password' => Hash::make($request->new_password)
        ]);
        // $user->update($request->all());

        $nonghyup = \App\User::findOrFail($id);
        $nonghyup->update($payload);

        // dd($request->password, $request->newPassword);

        flash()->success('비밀번호를 변경하였습니다.');
        return redirect(route('users.edit', $id));
    }
}
