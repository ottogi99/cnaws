<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SigunsController extends Controller
{
    public function __construct()
    {
        // 로그인한 사용자만 접근 가능
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $siguns = \App\Sigun::orderBy('sequence')->get();

        return view('siguns.index', compact('siguns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sigun = new \App\Sigun;

        return view('siguns.create', compact('sigun'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'sequence' => ['max:99'],
            'code' => ['required','unique:siguns','min:2','max:2'],
            'name' => ['required','unique:siguns','min:3','max:3'],
        ];

        // 사용자 정의 오류메시지 정의
        $message = [
            'sequence.max' => '순번은 최대 :max 까지 입력 가능합니다.',
            'code.required' => '시군코드는 필수 입력 항목입니다.',
            'code.min' => '시군코드는 영문 :min 글자로만 입력 가능합니다.',
            'code.max' => '시군코드는 영문 :max 글자로만 입력 가능합니다.',
            'code.unique' => '시군코드가 이미 사용되고 있습니다.',
            'name.required' => '시군명은 필수 입력 항목입니다.',
            'name.min' => '시군명은 한글 :min 글자로만 입력 가능합니다.',
            'name.max' => '시군명은 한글 :max 글자로만 입력 가능합니다.',
            'name.unique' => '시군명은 이미 사용되고 있습니다.',
        ];

        // 유효성 검사는 트레이트 메서드를 활용
        $this->validate($request, $rules, $message);

        $siguns = \App\Sigun::create($request->all());

        if (! $siguns) {
            flash('시군항목이 저장되지 않았습니다.');
            return back()->withInput();
        }

        flash('새 시군항목이 저장되었습니다.');
        return redirect(route('siguns.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    public function edit(\App\Sigun $sigun)
    {
        //
        return view('siguns.edit', compact('sigun'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    public function update(Request $request, \App\Sigun $sigun)
    {
        $sigun->update($request->all());
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('siguns.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(\App\Sigun $sigun)
    public function destroy($id)
    {
        //
        $sigun = \App\Sigun::findOrFail($id);
        // 인가 적용
        // $this->authorize('delete', $sigun);
        $sigun->delete();

        flash()->success('삭제되었습니다');
        return response()->json([], 204);
    }
}
