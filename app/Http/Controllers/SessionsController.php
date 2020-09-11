<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nonghyup_id' => 'required',
            'password' => 'required'
        ]);

        if (! auth()->attempt($request->only('nonghyup_id', 'password'))) {
            flash('ID 또는 비밀번호가 맞지 않습니다.');

            return back()->withInput();
        }

        if (! auth()->user()->activated) {
            auth()->logout();
            flash('가입 확인 여부를 관리자에게 문의바랍니다.');

            return back()->withInput();
        }

        flash(auth()->user()->name . '님. 환영합니다');

        return redirect()->intended('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        auth()->logout();
        flash('로그 아웃 되었습니다.');

        return redirect('/');
    }
}
