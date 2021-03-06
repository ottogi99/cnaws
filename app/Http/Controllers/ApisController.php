<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApisController extends Controller
{
    public function __construct()
    {
        // 이미 로그인한 사용자가 회원 가입 주소를 직접 입력하는 것을 막자
        // 관리자만 회원 가입 주소를 입력할 수 있게 바꿔야 한다.
        // $this->middleware('guest');
        $this->middleware('auth');
    }

    public function index()
    {
        return view('apis.index');
    }

    public function popup()
    {
        return view('apis.popup');
    }

    public function searchPopup(Request $request)
    {
        $type = $request->input('type');
        $nid = $request->input('nid');

        if ($type == 'small') {
            return view('apis.popup_search', ['nonghyup_id' => $nid]);
        }
        elseif ($type == 'large') {
            return view('apis.popup_search_large', ['nonghyup_id' => $nid]);
        }
        elseif ($type == 'machine') {
            return view('apis.popup_search_machine', ['nonghyup_id' => $nid]);
        }
        else {
            return view('apis.popup_search_manpower', ['nonghyup_id' => $nid]);
        }
    }

    public function callback(Request $request)
    {
        $post_data = $request->post();
        return view('apis.popup', compact('post_data'));
    }

    public function excel()
    {
        return view('apis.excel');
    }
}
