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
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('apis.index');
    }

    public function popup()
    {
      $post_data = [
        'inputYn'=>'',
        'roadFullAddr'=>'',
        'roadAddrPart1'=>'',
        'roadAddrPart2'=>'',
        'engAddr'=>'',
        'jibunAddr'=>'',
        'zipNo'=>'',
        'addrDetail'=>'',
        'admCd'=>'',
        'rnMgtSn'=>'',
        'bdMgtSn'=>'',
        'detBdNmList'=>'',
        'bdNm'=>'',
        'bdKdcd'=>'',
        'siNm'=>'',
        'sggNm'=>'',
        'emdNm'=>'',
        'liNm'=>'',
        'rn'=>'',
        'udrtYn'=>'',
        'buldMnnm'=>'',
        'buldSlno'=>'',
        'mtYn'=>'',
        'lnbrMnnm'=>'',
        'lnbrSlno'=>'',
        'emdNo'=>'',
      ];

      return view('apis.popup', compact('post_data'));
    }

    public function callback(Request $request)
    {
      $post_data = $request->post();
      return view('apis.popup', compact('post_data'));
    }
}
