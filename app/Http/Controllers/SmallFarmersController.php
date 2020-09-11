<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Laravel Excel
use App\Exports\SmallFarmersExport;
use App\Imports\SmallFarmersImport;
use Maatwebsite\Excel\Facades\Excel;

// use Illuminate\Auth\Access\Response;
// use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Log;

class SmallFarmersController extends Controller
{
    public function __construct()
    {
        // 로그인한 사용자만 접근 가능
        $this->middleware('auth');

        $this->siguns = \App\Sigun::orderBy('sequence')->get();
        $this->nonghyups = \App\User::with('sigun')
                              ->join('siguns', 'users.sigun_code', 'siguns.code')
                              ->select('users.*')
                              ->where('users.is_admin', '!=', 1)
                              ->orderBy('siguns.sequence')
                              ->orderBy('users.sequence')
                              ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // 검색기능 구현
    public function index(Request $request, $slug=null)
    {
        // $query = $slug ? \App\Tag::whereSlgu($slug)->firstOrFail()->articles()
        //               : new Article;

        // $query = new \App\SmallFarmer;
        // dd($request);
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun_code = $request->input('sigun_code', '');
        $nonghyup_id = $request->input('nonghyup_id', '');
        $sort = $request->input('sort', 'users.created_at');
        $order = $request->input('order', 'desc');
        $keyword = request()->input('q');
        $user = auth()->user();
        //
        // if ($keyword = request()->input('q')) {
        //     // $raw = 'MATCH(title, content) AGAINST (? IN BOOLEAN MODE)';
        //     $raw = 'MATCH(name, address, remark) AGAINST (? IN BOOLEAN MODE)';
        //     $query = $query->whereRaw($raw, [$keyword]);
        // }

        if (!$user->isAdmin()) {
            if (!$sigun_code) {
                $sigun_code = $user->sigun->code;
            }
            if (!$nonghyup_id) {
                $nonghyup_id = $user->nonghyup_id;
            }
        }

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(small_farmers.name, small_farmers.address, small_farmers.remark) AGAINST (? IN BOOLEAN MODE)';
            $keyword = '%'.$keyword.'%';
        } else {
            $raw = '';
        }

        $farmers = \App\SmallFarmer::with('sigun')->with('nonghyup')
                                  ->join('siguns', 'small_farmers.sigun_code', 'siguns.code')
                                  ->join('users', 'small_farmers.nonghyup_id', 'users.nonghyup_id')
                                  ->select(
                                      'small_farmers.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                                      'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                                    )
                                  ->where('small_farmers.business_year', $year)
                                  // ->when($user, function($query, $user) {
                                  //     if (!$user->isAdmin())  // 관리자인 모든 시군이 보이도록
                                  //       return $query->where('small_farmers.sigun_code', $user->sigun_code);
                                  //     else
                                  //       return $query;
                                  // })
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('small_farmers.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('small_farmers.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 농가명으로 검색
                                      return $query->whereRaw(
                                                    '(siguns.name like ? or users.name like ? or small_farmers.name like ?)',
                                                    [$keyword, $keyword, $keyword]
                                                  );
                                  })
                                  // // FullTextSearch
                                  // ->when($keyword, function($query, $keyword) use ($raw) {
                                  //     return $query->whereRaw($raw, [$keyword]);
                                  // })
                                  // ->when($keyword, function($query, $keyword) use ($raw) {
                                  //     return $query->whereRaw($raw, [$keyword]);
                                  // })
                                  // ->orderby('users.sequence')
                                  // ->orderby('users.created_at', 'desc')
                                  // ->orderby($sort, $order)
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('small_farmers.created_at', 'desc')
                                  ->paginate(10);

        if ($user->isAdmin()) {
            $nonghyups = $this->nonghyups;
        } else {
            // $nonghyups = \App\User::when($sigun_code, function($query, $sigun_code) {
            //                                 return $query->where('sigun_code', $sigun_code)->orderBy('sequence')->get();
            //                               });
            $nonghyups = \App\User::where('sigun_code', $sigun_code)
                                  ->orderBy('sequence')
                                  ->get();
        }

        // 데이터 입력 일정 적용
        $schedule = \App\Schedule::first();
        if ($schedule->is_period) {
            if (now() < $schedule->input_start_date || now() > $schedule->input_end_date)
                $schedule->is_allow = false;
        }

        $siguns = $this->siguns;

        return view('small_farmers.index', compact('farmers', 'siguns', 'nonghyups', 'schedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Todo: 로그인한 사용자 정보를 사용하여야 한다.
        // $user = \App\User::whereUserId('migun')->firstOrFail();
        // $user = \App\User::findOrFail(1);
        // $nonghyup = auth()->user();

        $nonghyups = $this->nonghyups;
        $farmer = new \App\SmallFarmer;

        // $farmer->sigun_code = $nonghyup->sigun->code;
        // $farmer->nonghyup_id = $nonghyup->user_id;

        return view('small_farmers.create', compact('farmer', 'nonghyups'));

// 관리자가 아닌 경우를 생각하자
        // $siguns = \App\Sigun::orderBy('sequence')->get();
        //
        // if ($user->isAdmin()) {
        //     $nonghyups = \App\User::orderBy('name')->get();
        // }

        // return view('small_farmers.create', compact('user', 'farmer', 'siguns', 'nonghyups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payload = array_merge($request->all(), [
          'business_year' => now()->format('Y'),  // 생성은 그 해에 입력하는 데이터로 한다.(수정불가)
          'sum_acreage' => $request->acreage1 + $request->acreage2 + $request->acreage3
        ]);

        try {
            $farmer = \App\SmallFarmer::create($payload);
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode() == '23000') {
                $first_quotes = strpos($e->getMessage(), '\'');
                $end_quotes = strpos($e->getMessage(), '\'', $first_quotes + 1);
                $duplicated = substr($e->getMessage(), $first_quotes, $end_quotes - $first_quotes + 1);
                flash()->error('동일한 이름의 농가가 이미 존재하고 있습니다. 입력 데이터를 확인하여 다시 시도하시기 바랍니다. (중복 키: '.$duplicated.')');
            } else {
                flash()->error('엑셀 업로드 도중 에러가 발생하였습니다. 관리자에게 문의바랍니다(에러메시지:'.$e->errorInfo[2].')');
            }

            return back()->withInput();
        }

        flash('일손필요농가(소규모·영세소농) 항목이 저장되었습니다.');
        return redirect(route('small_farmers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // 다중 primary 키를 사용하는 경우
    // public function show($id, $name)
    // public function show($id)
    // 함수 인자는 라우트 모델 바인딩 (라우트와 {farmer}와 묵시적으로 연계된다.)
    // public function show(\App\SmallFarmer $farmer)
    public function show($id)
    {
        $farmer = \App\SmallFarmer::findOrFail($id);

        $this->authorize('show-small-farmer', $farmer);

        return view('small_farmers.show', compact('farmer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     // public function edit(\App\SmallFarmer $farmer)
    // public function edit($id)
    // public function edit($id, $name)
    // public function edit(\App\SmallFarmer $farmer)
    public function edit($id)
    {
        $nonghyups = $this->nonghyups;
        $farmer = \App\SmallFarmer::findOrFail($id);

        $this->authorize('edit-small-farmer', $farmer);

        return view('small_farmers.edit', compact('farmer', 'nonghyups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id, $name)
    // public function update(Request $request, \App\SmallFarmer $farmer)
    public function update(Request $request, $id)
    {
        $farmer = \App\SmallFarmer::findOrFail($id);

        $this->authorize('edit-small-farmer', $farmer);

        $payload = $request->all();
        $payload = array_merge($payload, [
          'sum_acreage' => $request->acreage1 + $request->acreage2 + $request->acreage3
        ]);
        $farmer->update($payload);

        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('small_farmers.index'));
    }

    public function destroy($id)
    {
        $farmer = \App\SmallFarmer::findOrFail($id);
        $this->authorize('delete-small-farmer', $farmer);
        $farmer->delete();

        flash()->success('삭제되었습니다');
        return response()->json([], 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        // $this->authorize('delete-small-farmer', $farmer);
        $budgets = \App\SmallFarmer::whereIn('id', explode(",", $ids))->delete();

        return response()->json(['status'=>true, 'message'=>"삭제 되었습니다."], 200);
    }

    public function example()
    {
        $pathToFile = storage_path('app/public/example/' . 'uploaded_small_farmers.xlsx');
        return response()->download($pathToFile, '영세소농_샘플현황(예시).xlsx');
    }

    // public function export($nonghyup = '', $sigun = '')
    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun = $request->input('sigun');
        $nonghyup = $request->input('nonghyup');
        $keyword = $request->input('q');
        $user = auth()->user();

        $this->authorize('export-small-farmer', $nonghyup);

        return (new SmallFarmersExport())
                  ->forYear($year)
                  ->forSigun($sigun, $user)
                  ->forNonghyup($nonghyup, $user)
                  ->forKeyword($keyword)
                  ->download('농작업지원단(소규모영세농)_모집현황.xlsx');
    }

    // public function import(Request $request, $file)
    public function import(Request $request)
    {
        $allowed = ["xls", "xlsx"];

        // if (!$request->hasFile('excel')) {
        //     flash()->error('업로드할 엑셀파일을 선택해 주세요');
        //     return redirect(route('small_farmers.index'));
        // }

        // $extension = $request->file('excel')->extension();
        // if ( !in_array($extension, $allowed) ) {
        //   flash()->error('업로드 파일은 엑셀형식(.xls, .xlsx)만 가능합니다.');
        //   return redirect(route('small_farmers.index'));
        // }

        $rules = [
            'excel' => ['required', 'file', 'mimes:xls,xlsx', 'max:20000'],  // 512: 512 kilobytes, 1024(1M)
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'mimes' => ':attribute은 엑셀파일(.xls, .xlsx)형식만 가능합니다.',
            'size' => ':attribute의 용량은 20M 이하만 가능합니다'
        ];

        $attributes = [
            'excel' => '업로드 파일',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $excel = $request->file('excel');
        Log::debug($excel->path());
        Log::debug($excel->extension());
        Log::debug($excel->getClientOriginalName());

        $import = new SmallFarmersImport();
        // $import->import('uploaded_small_farmers.xlsx', 'local', \Maatwebsite\Excel\Excel::XLSX);
        $import->import($excel, \Maatwebsite\Excel\Excel::XLSX);

        $inserted_rows = $import->getRowCount();

        $failures = $import->failures();   // Import Failure
        $errors = $import->errors();    // Import Error

        $failure_rows = [];
        if (count($failures) > 0) {
            foreach ($failures as $failure) {
                 // $failure->row(); // row that went wrong
                 // $failure->attribute(); // either heading key (if using heading row concern) or column index
                 // $failure->errors(); // Actual error messages from Laravel validator
                 // $failure->values(); // The values of the row that has failed.

                 foreach($failures as $failure) {
                     $row = $failure->row();
                     $value = isset($failure_rows[(string)$row]) ? $failure_rows[(string)$row] : 0;
                     $failure_rows += [(string)$row => $value + 1];
                     // array_push($failure_rows, (string)$row, );
                     // dd($failure_rows[(string)$row]);
                     $column = $failure->attribute();
                     Log::warning($row.'행 '.$column.'열: '.$failure->errors()[0]);
                 }
            }
            $total_rows = $inserted_rows + count($failure_rows);

            flash()->error('전체 '.$total_rows.'개의 데이터 중 '.$inserted_rows.' 건의 데이터가 입력 되었습니다.');

            $message = '입력한 데이터의 형식이 잘못되었습니다. 행 [';
            foreach($failure_rows as $row_number => $values) {
                $message = $message . $row_number . ', ';
            }
            $message = $message . ']';

            flash()->warning($message);
            return redirect(route('small_farmers.index'));
        }

        if (count($errors) > 0) {
            foreach($errors as $error) {
                Log::error($error->getCode());      // DB 에러코드 (SQLSTATE error code)
                Log::error($error->getMessage());   // 에러 메시지-
                Log::error($error->errorInfo);      // SQLSTATE error code / Driver-specific error code / Driver-specific error message

                if ($error->getCode() == '23000') {
                    $first_quotes = strpos($error->getMessage(), '\'');
                    $end_quotes = strpos($error->getMessage(), '\'', $first_quotes + 1);
                    $duplicated = substr($error->getMessage(), $first_quotes, $end_quotes - $first_quotes + 1);
                    // Log::debug($duplicated);
                    flash()->error('자료 중에 추가하려는 농가가 이미 존재하고 있습니다. 입력 데이터를 확인하여 다시 시도하시기 바랍니다. (중복 키: '.$duplicated.')');
                } else {
                    flash()->error('엑셀 업로드 도중 에러가 발생하였습니다. 관리자에게 문의바랍니다(에러메시지:'.$error->errorInfo[2].')');
                }
            }
            // Log::debug($errors);
            return redirect(route('small_farmers.index'));
        }

        // if ($errors->count() > 0) {
        //     foreach($errors as $error) {
        //         Log::debug($error->getCode());      // DB 에러코드 (SQLSTATE error code)
        //         Log::debug($error->getMessage());   // 에러 메시지-
        //         Log::debug($error->errorInfo);      // SQLSTATE error code / Driver-specific error code / Driver-specific error message
        //     }
        // }

        flash()->success($inserted_rows . '건의 데이터가 업로드 완료 되었습니다.');
        return redirect(route('small_farmers.index'));
    }

    public function list(Request $request)
    {
        $nonghyup_id = $request->input('nonghyup_id', '');

        $farmers = \App\SmallFarmer::select('small_farmers.id', 'small_farmers.name', 'small_farmers.address')
                              ->when($nonghyup_id, function($query, $nonghyup_id) {
                                  return $query->where('small_farmers.nonghyup_id', $nonghyup_id);
                              }, function($query, $nonghyup_id) {
                                  return $query;
                              })
                              ->where('small_farmers.business_year', now()->year)
                              ->orderBy('small_farmers.name')
                              ->get()->toArray();

        return response()->json($farmers, 200);
    }
}
