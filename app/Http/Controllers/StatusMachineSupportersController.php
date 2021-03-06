<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Laravel Excel
use App\Exports\StatusMachineSupportersExport;
use App\Imports\StatusMachineSupportersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class StatusMachineSupportersController extends Controller
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
            $raw = 'MATCH(status_machine_supporters.item, status_machine_supporters.target, status_machine_supporters.detail) AGAINST (? IN BOOLEAN MODE)';
            $keyword = '%'.$keyword.'%';
        } else {
            $raw = '';
        }

        $rows = \App\StatusMachineSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
                    ->join('siguns', 'status_machine_supporters.sigun_code', 'siguns.code')
                    ->join('users', 'status_machine_supporters.nonghyup_id', 'users.nonghyup_id')
                    ->join('small_farmers', 'status_machine_supporters.farmer_id', 'small_farmers.id')
                    ->join('machine_supporters', 'status_machine_supporters.supporter_id', 'machine_supporters.id')
                    ->select(
                        'status_machine_supporters.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                        'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name',
                        'small_farmers.name as farmer_name', 'small_farmers.address as farmer_address', 'small_farmers.sex as farmer_sex',
                        'machine_supporters.name as supporter_name'
                      )
                    ->where('status_machine_supporters.business_year', $year)
                    ->where('users.is_admin', '!=', 1)
                    ->when($sigun_code, function($query, $sigun_code) {
                        return $query->where('status_machine_supporters.sigun_code', $sigun_code);
                    })
                    ->when($nonghyup_id, function($query, $nonghyup_id) {
                        return $query->where('status_machine_supporters.nonghyup_id', $nonghyup_id);
                    })
                    // ->when(!auth()->user()->isAdmin(), function($query) {
                    //     return $query->where('status_machine_supporters.nonghyup_id', auth()->user()->nonghyup_id);
                    // })
                    ->when($keyword, function($query, $keyword) {
                        // 시군명, 대상농협, 농가명, 작업자명으로 검색
                        return $query->whereRaw(
                                      '(siguns.name like ? or users.name like ? or small_farmers.name like ? or small_farmers.name like ? or machine_supporters.name like ?)',
                                      [$keyword, $keyword, $keyword, $keyword, $keyword]
                                    );
                        })
                    ->orderby('siguns.sequence')
                    ->orderby('users.sequence')
                    ->orderby('users.name')
                    ->orderby('status_machine_supporters.created_at', 'desc')
                    ->paginate(20);

        if ($user->isAdmin()) {
            $nonghyups = $this->nonghyups;
        } else {
            $nonghyups = \App\User::where('sigun_code', $sigun_code)
                                  ->orderBy('sequence')
                                  ->get();
        }

        $siguns = $this->siguns;

        return view('status_machine_supporters.index', compact('rows', 'siguns', 'nonghyups'));
    }

    public function create()
    {
        $siguns = \App\Sigun::orderBy('sequence')->get();
        $nonghyups = $this->nonghyups;
        $row = new \App\StatusMachineSupporter;

        $row->nonghyup_id = auth()->user()->nonghyup_id;

        $farmers = \App\SmallFarmer::where('nonghyup_id', $row->nonghyup_id)
                              ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        $supporters = \App\MachineSupporter::where('nonghyup_id', $row->nonghyup_id)
                              ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        return view('status_machine_supporters.create', compact('row', 'supporters', 'farmers', 'nonghyups', 'siguns'));
    }

    public function store(Request $request)
    {
        $rules = [
            'sigun_code' => ['required'],
            'nonghyup_id' => ['required'],      // 농협
            'farmer_id' => ['required'],        // 농가
            'supporter_id' => ['required'],     // 작업자
            'job_start_date' => ['required'],   // 작업시작일
            'job_end_date' => ['required'],     // 작업종료일
            'work_detail' => ['required'],      // 작업내용
            'working_area' => ['required'],     // 작업면적
            'payment_sum' => ['required'],      // 지급액(합계)
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
        ];

        $attributes = [
            'sigun_code'      => '시군항목',
            'nonghyup_id'     => '농협ID',
            'farmer_id'       => '농가명',        // 농가
            'supporter_id'    => '작업자명',     // 작업자
            'job_start_date'  => '작업시작일',   // 작업시작일
            'job_end_date'    => '작업종료일',     // 작업종료일
            'work_detail'     => '작업내용',      // 작업내용
            'working_area'    => '작업면적',     // 작업면적
            'payment_sum'     => '지급액(합계)',      // 지급액(합계)
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $user = auth()->user();
        $business_year = now()->format('Y');

        $farmer_id = $request->input('farmer_id');
        $supporter_id = $request->input('supporter_id');
        $job_start_date = $request->input('job_start_date');
        $job_end_date = $request->input('job_end_date');

        // 2020-11-09 중복일 처리 변경 (지원반 이름에서 지원반 id로: 동명이인 허용되므로)
        // 2020-12-07 농기계지원반의 경우 동일작업자가 필지만 다른 동일 농가의 작업도 진행할 수 있으므로 중복 검사 제외함(신철희 주무관)
        // $duplicated_items = $this->check_duplicate($farmer_id, $supporter_id, $job_start_date, $job_end_date);
        //
        // if (count($duplicated_items) > 0)
        // {
        //     flash()->error('요청하신 농기계지원반의 작업일자가 이미 등록되어 있습니다. 중복을 확인하여 주세요.');
        //
        //     $warning_message = '[ 기존 등록된 데이터 정보 ]<br/>';
        //     foreach ($duplicated_items as $index => $item) {
        //         $warning_message .= ($index + 1) . '. 농협: ' . $item->nonghyup_name . ', 농가: ' . $item->farmer_name . ', 작업반: ' . $item->supporter_name . ', 시작일자: '
        //                     . $item->job_start_date->format('Y-m-d') . ', 종료일자: ' . $item->job_end_date->format('Y-m-d') . '<br/>';
        //     }
        //     flash()->warning($warning_message);
        //     return back()->withInput();
        // }

        $payment_sum = $request->input('payment_sum');
        $job_start_date = new Carbon($request->input('job_start_date'));
        $job_end_date   = new Carbon($request->input('job_end_date'));
        $working_days = $job_start_date->diffInDays($job_end_date) + 1;//->format('%H:%I:%S');

        $payment_do = floor($payment_sum * 0.21);
        $payment_sigun = floor($payment_sum * 0.49);
        $payment_center = floor($payment_sum * 0.2);
        $payment_unit = floor($payment_sum * 0.1);
        $payment_diff = $payment_sum - ($payment_do + $payment_sigun + $payment_center + $payment_unit);

        $payload = array_merge($request->all(), [
          'business_year' => $business_year,  // 생성은 그 해에 입력하는 데이터로 한다.(수정불가)
          'working_days' => $working_days,
          'payment_do' => $payment_do + $payment_diff,
          'payment_sigun' => $payment_sigun,
          'payment_center' => $payment_center,
          'payment_unit' => $payment_unit,
        ]);

        try {
            $row = \App\StatusMachineSupporter::create($payload);
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode() == '23000') {
                $first_quotes = strpos($e->getMessage(), '\'');
                $end_quotes = strpos($e->getMessage(), '\'', $first_quotes + 1);
                $duplicated = substr($e->getMessage(), $first_quotes, $end_quotes - $first_quotes + 1);
                flash()->error('동일한 이름의 농가가 이미 존재하고 있습니다. 입력 데이터를 확인하여 다시 시도하시기 바랍니다. (중복 항목: '.$duplicated.')');
            } else {
                flash()->error('엑셀 업로드 도중 에러가 발생하였습니다. 관리자에게 문의바랍니다(에러메시지:'.$e->errorInfo[2].')');
            }
            return back()->withInput();
        }

        flash('농기계지원반 지원현황 항목이 저장되었습니다.');
        return redirect(route('status_machine_supporters.index'));
    }

    public function show($id)
    {
        $row = \App\StatusMachineSupporter::findOrFail($id);
        $this->authorize('show-status-machine-supporter', $row);

        return view('status_machine_supporters.show', compact('row'));
    }

    public function edit($id)
    {
        $siguns = \App\Sigun::orderBy('sequence')->get();
        $row = \App\StatusMachineSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
                                          ->join('small_farmers', 'status_machine_supporters.farmer_id', 'small_farmers.id')
                                          ->join('machine_supporters', 'status_machine_supporters.supporter_id', 'machine_supporters.id')
                                          ->select(
                                                'status_machine_supporters.*',
                                                'small_farmers.name as farmer_name',
                                                'machine_supporters.name as supporter_name'
                                            )
                                          // ->where('status_machine_supporters.business_year', now()->year)
                                          ->findOrFail($id);

        // $this->authorize('edit-status-machine-supporter', $row);

        // 목록(농협(사용자), 농가(영세농), 농기계지원반)
        $nonghyups = $this->nonghyups;
        $farmers = \App\SmallFarmer::where('nonghyup_id', $row->nonghyup_id)
                              // ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        $supporters = \App\MachineSupporter::where('nonghyup_id', $row->nonghyup_id)
                              // ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        return view('status_machine_supporters.edit', compact('row', 'supporters', 'farmers', 'nonghyups', 'siguns'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'sigun_code' => ['required'],
            'nonghyup_id' => ['required'],      // 농협
            'farmer_id' => ['required'],        // 농가
            'supporter_id' => ['required'],     // 작업자
            'job_start_date' => ['required'],   // 작업시작일
            'job_end_date' => ['required'],     // 작업종료일
            'work_detail' => ['required'],      // 작업내용
            'working_area' => ['required'],     // 작업면적
            'payment_do' => ['required'],      // 지급액(합계)
            'payment_sigun' => ['required'],      // 지급액(합계)
            'payment_center' => ['required'],      // 지급액(합계)
            'payment_unit' => ['required'],      // 지급액(합계)
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
        ];

        $attributes = [
            'sigun_code'      => '시군항목',
            'nonghyup_id'     => '농협ID',
            'farmer_id'       => '농가명',        // 농가
            'supporter_id'    => '작업자명',     // 작업자
            'job_start_date'  => '작업시작일',   // 작업시작일
            'job_end_date'    => '작업종료일',     // 작업종료일
            'work_detail'     => '작업내용',      // 작업내용
            'working_area'    => '작업면적',     // 작업면적
            'payment_sum'     => '지급액(합계)',      // 지급액(합계)
            'payment_do'      => '지급액(도비)',
            'payment_sigun'   => '지급액(시군비)',
            'payment_center'  => '지급액(중앙회)',
            'payment_unit'    => '지급액(지역농협)',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $row = \App\StatusMachineSupporter::findOrFail($id);
        $this->authorize('edit-status-machine-supporter', $row);

        // 2020-12-07 농기계지원반의 경우 동일작업자가 필지만 다른 동일 농가의 작업도 진행할 수 있으므로 중복 검사 제외함(신철희 주무관)
        // $farmer_id = $request->input('farmer_id');
        // $supporter_id = $request->input('supporter_id');
        // $job_start_date = $request->input('job_start_date');
        // $job_end_date = $request->input('job_end_date');
        // if ($row->job_start_date->format('Y-m-d') != $job_start_date || $row->job_end_date->format('Y-m-d') != $job_end_date)
        // {
        //     // 2020-12-04, 수정시 중복검사에서 자신의 id는 제외
        //     $duplicated_items = $this->check_duplicate($farmer_id, $supporter_id, $job_start_date, $job_end_date, $id);
        //
        //     if (count($duplicated_items) > 0)
        //     {
        //         flash()->error('요청하신 농기계지원반의 작업일자가 이미 등록되어 있습니다. 중복을 확인하여 주세요.');
        //
        //         $warning_message = '[ 기존 등록된 데이터 정보 ]<br/>';
        //         foreach ($duplicated_items as $index => $item) {
        //             // if ($item->id != $id) {
        //             $warning_message .= ($index + 1) . '. 농협: ' . $item->nonghyup_name . ', 농가: ' . $item->farmer_name . ', 작업반: ' . $item->supporter_name . ', 시작일자: '
        //                             . $item->job_start_date->format('Y-m-d') . ', 종료일자: ' . $item->job_end_date->format('Y-m-d') . '<br/>';
        //             // }
        //         }
        //
        //         flash()->warning($warning_message);
        //         return back()->withInput();
        //     }
        // }

        $job_start_date = new Carbon($request->input('job_start_date'));
        $job_end_date   = new Carbon($request->input('job_end_date'));
        $working_days = $job_start_date->diffInDays($job_end_date) + 1;//->format('%H:%I:%S');

        $payload = array_merge($request->all(), [
            'working_days' => $working_days,
        ]);

        $row->update($request->all());

        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('status_machine_supporters.index'));
    }

    public function destroy($id)
    {
        $row = \App\StatusMachineSupporter::findOrFail($id);
        $this->authorize('delete-status-machine-supporter', $row);
        $row->delete();

        flash()->success('삭제되었습니다');
        return response()->json([], 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        // $this->authorize('delete-status-supporter', $row);
        $budgets = \App\StatusMachineSupporter::whereIn('id', explode(",", $ids))->delete();

        return response()->json(['status'=>true, 'message'=>"삭제 되었습니다."], 200);
    }

    public function example()
    {
        $pathToFile = storage_path('app/public/example/' . 'uploaded_status_machine_supporters.xlsx');
        return response()->download($pathToFile, '농기계지원반_지원현황(예시).xlsx');
    }

    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun = $request->input('sigun');
        $nonghyup = $request->input('nonghyup');
        $keyword = $request->input('q');
        $user = auth()->user();

        $this->authorize('export-status-machine-supporter', $nonghyup);

        return (new StatusMachineSupportersExport())
                  ->forYear($year)
                  ->forSigun($sigun, $user)
                  ->forNonghyup($nonghyup, $user)
                  ->forKeyword($keyword)
                  ->download('농기계지원반_지원현황.xlsx');
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

        $import = new StatusMachineSupportersImport();
        $import->import($excel, \Maatwebsite\Excel\Excel::XLSX);

        $inserted_rows = $import->getRowCount();

        $failures = $import->failures();  // Import Failure
        $errors = $import->errors();      // Import Error

        if (count($errors) > 0) {
            foreach($errors as $error) {
                Log::error($error->getCode());      // DB 에러코드 (SQLSTATE error code)
                Log::error($error->getMessage());   // 에러 메시지-
                Log::error($error->errorInfo);      // SQLSTATE error code / Driver-specific error code / Driver-specific error message

                if ($error->getCode() == '23000') {
                    $first_quotes = strpos($error->getMessage(), '\'');
                    $end_quotes = strpos($error->getMessage(), '\'', $first_quotes + 1);
                    $duplicated = substr($error->getMessage(), $first_quotes, $end_quotes - $first_quotes + 1);
                    flash()->error('엑셀 자료에서 중복된 자료가 존재하고 있습니다. 중복된 자료를 제거(수정)하여 다시 시도하시기 바랍니다. (중복 항목: '.$duplicated.')');
                } else {
                    flash()->error('엑셀 업로드 도중 에러가 발생하였습니다. 관리자에게 문의바랍니다(에러메시지:'.$error->errorInfo[2].')');
                }
            }
            return redirect(route('status_machine_supporters.index'));
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
                $failure_message .= ($index+1). ')' . $row.'행 '.$column.'열: '.$failure->errors()[0].'<br/>';
            }
            $total_rows = $inserted_rows + count($failure_rows);

            flash()->error('전체 '.$total_rows.'개의 데이터 중 '.$inserted_rows.' 건의 데이터가 입력 되었습니다.');

            // $message = '입력한 데이터의 형식이 잘못되었습니다. 행 [';
            // foreach($failure_rows as $row_number => $values) {
            //     $message = $message . $row_number . ', ';
            // }
            // $message = $message . ']';

            flash()->warning($failure_message);
            return redirect(route('status_machine_supporters.index'));
        }

        if ($inserted_rows == 0) {
            flash()->success($inserted_rows . '건의 데이터가 업로드 완료 되었습니다. (샘플 엑셀파일과 칼럼수가 일치하는지 확인해 주세요.)');
        } else {
            flash()->success($inserted_rows . '건의 데이터가 업로드 완료 되었습니다.');
        }
        return redirect(route('status_machine_supporters.index'));
    }

    private function check_duplicate($farmer_id, $supporter_id, $job_start_date, $job_end_date, $edit_id='')
    {
        return $duplicated_items = \App\StatusMachineSupporter::with('nonghyup')->with('farmer')->with('supporter')
                                      ->join('users', 'status_machine_supporters.nonghyup_id', 'users.nonghyup_id')
                                      ->join('small_farmers', 'status_machine_supporters.farmer_id', 'small_farmers.id')
                                      ->join('machine_supporters', 'status_machine_supporters.supporter_id', 'machine_supporters.id')
                                      ->select(
                                          'status_machine_supporters.*',
                                          'users.name as nonghyup_name',
                                          'small_farmers.name as farmer_name', 'small_farmers.address as farmer_address',
                                          'machine_supporters.name as supporter_name'
                                        )
                                      ->where('status_machine_supporters.business_year', now()->year)
                                      ->where('small_farmers.id', $farmer_id)
                                      ->where('machine_supporters.id', $supporter_id) // 2020-11-09 동명이인 허용으로 id로 검색하는 것으로 변경
                                      ->when($edit_id, function($query, $edit_id) {   // 2020-12-04, 수정시 중복검사에서 자신의 id는 제외
                                            $query->where('status_machine_supporters.id', '<>', $edit_id);
                                        })
                                      ->where(function ($query) use ($job_start_date, $job_end_date) {
                                          $query->whereRaw('
                                              (status_machine_supporters.job_start_date <= ? and ? <= status_machine_supporters.job_end_date)
                                              or
                                              (status_machine_supporters.job_start_date <= ? and ? <= status_machine_supporters.job_end_date)
                                              or
                                              (status_machine_supporters.job_start_date > ? and ? > status_machine_supporters.job_end_date)
                                              ',
                                              [$job_start_date, $job_start_date, $job_end_date, $job_end_date, $job_start_date, $job_end_date]
                                          );
                                        })
                                      ->get();
    }
}
