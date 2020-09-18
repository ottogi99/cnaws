<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Laravel Excel
use App\Exports\LargeFarmersExport;
use App\Imports\LargeFarmersImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Log;

class LargeFarmersController extends Controller
{
    public function __construct()
    {
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

    public function index(Request $request, $slug=null)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun_code = $request->input('sigun_code', '');
        $nonghyup_id = $request->input('nonghyup_id', '');
        $sort = $request->input('sort', 'users.created_at');
        $order = $request->input('order', 'desc');
        $keyword = request()->input('q');
        $user = auth()->user();

        if (!$user->isAdmin()) {
            if (!$sigun_code) {
                $sigun_code = $user->sigun->code;
            }
            if (!$nonghyup_id) {
                $nonghyup_id = $user->nonghyup_id;
            }
        }

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(large_farmers.name, large_farmers.address, large_farmers.remark) AGAINST (? IN BOOLEAN MODE)';
            $keyword = '%'.$keyword.'%';
        } else {
            $raw = '';
        }

        $farmers = \App\LargeFarmer::with('sigun')->with('nonghyup')
                                  ->join('siguns', 'large_farmers.sigun_code', 'siguns.code')
                                  ->join('users', 'large_farmers.nonghyup_id', 'users.nonghyup_id')
                                  ->select(
                                      'large_farmers.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                                      'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                                    )
                                  ->where('large_farmers.business_year', $year)
                                  ->where('users.is_admin', '!=', 1)
                                  ->when($sigun_code, function($query, $sigun_code) {
                                      return $query->where('large_farmers.sigun_code', $sigun_code);
                                  })
                                  ->when($nonghyup_id, function($query, $nonghyup_id) {
                                      return $query->where('large_farmers.nonghyup_id', $nonghyup_id);
                                  })
                                  ->when($keyword, function($query, $keyword) {
                                      // 시군명, 대상농협, 농가명으로 검색
                                      return $query->whereRaw(
                                                    '(siguns.name like ? or users.name like ? or large_farmers.name like ?)',
                                                    [$keyword, $keyword, $keyword]
                                                  );

                                  })
                                  // ->when($keyword, function($query, $keyword) use ($raw) {
                                  //     return $query->whereRaw($raw, [$keyword]);
                                  // })
                                  ->orderby('siguns.sequence')
                                  ->orderby('users.sequence')
                                  ->orderby('large_farmers.created_at', 'desc')
                                  // ->orderby($sort, $order)
                                  ->paginate(10);

        if ($user->isAdmin()) {
            $nonghyups = $this->nonghyups;
        } else {
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

        return view('large_farmers.index', compact('farmers', 'siguns', 'nonghyups', 'schedule'));
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
        //
        $nonghyups = $this->nonghyups;
        $farmer = new \App\SmallFarmer;

        // $farmer->sigun_code = $nonghyup->sigun->code;
        // $farmer->nonghyup_id = $nonghyup->user_id;

        return view('large_farmers.create', compact('farmer', 'nonghyups'));

// 관리자가 아닌 경우를 생각하자
        // $siguns = \App\Sigun::orderBy('sequence')->get();
        //
        // if ($user->isAdmin()) {
        //     $nonghyups = \App\User::orderBy('name')->get();
        // }

        // return view('large_farmers.create', compact('user', 'farmer', 'siguns', 'nonghyups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $business_year = now()->format('Y');

        $payload = array_merge($request->all(), [
          'business_year' => $business_year,  // 생성은 그 해에 입력하는 데이터로 한다.(수정불가)
        ]);

        // if (\App\LargeFarmer::where('business_year', $business_year)
        //                     ->where('nonghyup_id', $user->user_id)
        //                     ->where('name', $request->input('name'))->exists())
        // {
        //     flash('추가하려는 농가가 해당농협에 이미 존재하고 있습니다. 중복을 확인하여 주세요.')->error();
        //     return back()->withInput();
        // }

        try {
          $farmer = \App\LargeFarmer::create($payload);
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

        flash()->success('일손필요농가(대규모·전업농) 항목이 저장되었습니다.');
        return redirect(route('large_farmers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $farmer = \App\LargeFarmer::findOrFail($id);
        $this->authorize('show-large-farmer', $farmer);

        return view('large_farmers.show', compact('farmer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $siguns = \App\Sigun::orderBy('sequence')->get();
        $nonghyups = $this->nonghyups;
        $farmer = \App\LargeFarmer::findOrFail($id);

        $this->authorize('edit-large-farmer', $farmer);

        return view('large_farmers.edit', compact('farmer', 'nonghyups', 'siguns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $farmer = \App\LargeFarmer::findOrFail($id);

        $this->authorize('edit-large-farmer', $farmer);

        $farmer->update($request->all());

        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('large_farmers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $farmer = \App\LargeFarmer::findOrFail($id);
        $this->authorize('delete-large-farmer', $farmer);
        $farmer->delete();

        flash()->success('삭제되었습니다');
        return response()->json([], 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        // $this->authorize('delete-small-farmer', $farmer);
        $budgets = \App\LargeFarmer::whereIn('id', explode(",", $ids))->delete();

        return response()->json(['status'=>true, 'message'=>"삭제 되었습니다."], 200);
    }

    public function example()
    {
        $pathToFile = storage_path('app/public/example/' . 'uploaded_large_farmers.xlsx');
        return response()->download($pathToFile, '전업농_모집현황(예시).xlsx');
    }

    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun = $request->input('sigun');
        $nonghyup = $request->input('nonghyup');
        $keyword = $request->input('q');
        $user = auth()->user();

        $this->authorize('export-large-farmer', $nonghyup);

        return (new LargeFarmersExport())
                  ->forYear($year)
                  ->forSigun($sigun, $user)
                  ->forNonghyup($nonghyup, $user)
                  ->forKeyword($keyword)
                  ->download('농작업지원단(대규모전업농)_모집현황.xlsx');
    }

    // public function import(Request $request, $file)
    public function import(Request $request)
    {
        $allowed = ["xls", "xlsx"];

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

        $import = new LargeFarmersImport();

        // $import->import('uploaded_large_farmers.xlsx', 'local', \Maatwebsite\Excel\Excel::XLSX);
        $import->import($excel, \Maatwebsite\Excel\Excel::XLSX);

        $inserted_rows = $import->getRowCount();

        $failures = $import->failures();   // Import Failure
        $errors = $import->errors();    // Import Error

        // 에러 검사 먼저 (에러가 난 경우 DB Insert가 모두 롤백된다)
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
            return redirect(route('large_farmers.index'));
        }

        $total_rows = 0;
        $failure_rows = [];
        if (count($failures) > 0) {
            $failure_message = '[실패한 입력 데이터].<br/>';
            foreach ($failures as $index => $failure) {
                // $failure->row(); // row that went wrong
                // $failure->attribute(); // either heading key (if using heading row concern) or column index
                // $failure->errors(); // Actual error messages from Laravel validator
                // $failure->values(); // The values of the row that has failed.

                $row = $failure->row();
                $value = isset($failure_rows[(string)$row]) ? $failure_rows[(string)$row] : 0;
                $failure_rows += [(string)$row => $value + 1];
                // array_push($failure_rows, (string)$row, );
                // dd($failure_rows[(string)$row]);
                $column = $failure->attribute();
                Log::warning($row.'행 '.$column.'열: '.$failure->errors()[0]);

                 // if ($index <= 10)
                $failure_message .= ($index+1). ')' . $row.'행 '.$column.': '.$failure->errors()[0].'<br/>';
            }
            $total_rows = $inserted_rows + count($failure_rows);

            flash()->error('전체 '.$total_rows.'개의 데이터 중 '.$inserted_rows.' 건의 데이터가 입력 되었습니다.');
            // success(), error(), warning(),

            // $message = '입력한 데이터의 형식이 잘못되었습니다. 행 [';
            // foreach($failure_rows as $row_number => $values) {
            //     $message = $message . $row_number . ', ';
            // }
            // $message = $message . ']';

            flash()->warning($failure_message);
            return redirect(route('large_farmers.index'));
        }

        flash()->success($inserted_rows . '건의 데이터가 업로드 완료 되었습니다.');
        return redirect(route('large_farmers.index'));
    }

    public function list(Request $request)
    {
        $nonghyup_id = $request->input('nonghyup_id', '');

        $farmers = \App\LargeFarmer::select('large_farmers.id', 'large_farmers.name', 'large_farmers.address')
                              ->when($nonghyup_id, function($query, $nonghyup_id) {
                                  return $query->where('large_farmers.nonghyup_id', $nonghyup_id);
                              }, function($query, $nonghyup_id) {
                                  return $query;
                              })
                              ->where('large_farmers.business_year', now()->year)
                              ->orderBy('large_farmers.name')
                              ->get()->toArray();

        return response()->json($farmers, 200);
    }

}
