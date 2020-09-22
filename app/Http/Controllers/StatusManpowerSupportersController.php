<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Laravel Excel
use App\Exports\StatusManpowerSupportersExport;
use App\Imports\StatusManpowerSupportersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class StatusManpowerSupportersController extends Controller
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
            $raw = 'MATCH(status_manpower_supporters.item, status_manpower_supporters.target, status_manpower_supporters.detail) AGAINST (? IN BOOLEAN MODE)';
            $keyword = '%'.$keyword.'%';
        } else {
            $raw = '';
        }

        $rows = \App\StatusManpowerSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
                    ->join('siguns', 'status_manpower_supporters.sigun_code', 'siguns.code')
                    ->join('users', 'status_manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                    ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                    ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                    ->select(
                        'status_manpower_supporters.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                        'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name',
                        'large_farmers.name as farmer_name', 'large_farmers.address as farmer_address', 'large_farmers.sex as farmer_sex',
                        'manpower_supporters.name as supporter_name'
                      )
                    ->where('status_manpower_supporters.business_year', $year)
                    ->where('users.is_admin', '!=', 1)
                    ->when($sigun_code, function($query, $sigun_code) {
                        return $query->where('status_manpower_supporters.sigun_code', $sigun_code);
                    })
                    ->when($nonghyup_id, function($query, $nonghyup_id) {
                        return $query->where('status_manpower_supporters.nonghyup_id', $nonghyup_id);
                    })
                    ->when($keyword, function($query, $keyword) {
                        // 시군명, 대상농협, 농가명, 작업자명으로 검색
                        return $query->whereRaw(
                                      '(siguns.name like ? or users.name like ? or large_farmers.name like ? or status_manpower_supporters.name like ?)',
                                      [$keyword, $keyword, $keyword, $keyword]
                                    );
                    })
                    // ->when($keyword, function($query, $keyword) use ($raw) {
                    //     return $query->whereRaw($raw, [$keyword]);
                    // })
                    ->orderby('siguns.sequence')
                    ->orderby('users.sequence')
                    ->orderby('users.name')
                    ->orderby('status_manpower_supporters.created_at', 'desc')
                    //->orderby($sort, $order)
                    ->paginate(20);

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

        return view('status_manpower_supporters.index', compact('rows', 'siguns', 'nonghyups', 'schedule'));
    }

    public function create()
    {
        $siguns = \App\Sigun::orderBy('sequence')->get();
        $nonghyups = $this->nonghyups;
        $row = new \App\StatusManpowerSupporter;
        //if (auth()->user()->is_admin)
        //    $row->nonghyup_id = 'nh485014';   //[TODO] 관리자인 경우 ca(천안)으로 일단 정의
        //else
        $row->nonghyup_id = auth()->user()->nonghyup_id;

        $farmers = \App\LargeFarmer::where('nonghyup_id', $row->nonghyup_id)
                              ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        // dd($row->nonghyup_id);
        $supporters = \App\ManpowerSupporter::where('nonghyup_id', $row->nonghyup_id)
                              ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        return view('status_manpower_supporters.create', compact('row', 'supporters', 'farmers', 'nonghyups', 'siguns'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $business_year = now()->format('Y');

        $supporter_id = $request->input('supporter_id');
        $job_start_date = $request->input('job_start_date');
        $job_end_date = $request->input('job_end_date');

        $supporter = \App\ManpowerSupporter::where('id', $supporter_id)->first();
        $supporter_name = $supporter->name;

        $duplicated_items = $this->check_duplicate($supporter_name, $job_start_date, $job_end_date);

        // dd($duplicated_items);
          if (count($duplicated_items) > 0)
          {
            // $error_message = '요청하신 데이터 정보<br/>';
            flash()->error('요청하신 농기계지원반의 작업일자가 이미 등록되어 있습니다. 중복을 확인하여 주세요.');

            $warning_message = '[ 기존 등록된 데이터 정보 ]<br/>';
            foreach ($duplicated_items as $index => $item) {
                $warning_message .= ($index + 1) . '. 농협: ' . $item->nonghyup_name . ', 농가: ' . $item->farmer_name . ', 작업반: ' . $item->supporter_name . ', 시작일자: '
                            . $item->job_start_date->format('Y-m-d') . ', 종료일자: ' . $item->job_end_date->format('Y-m-d') . '<br/>';
            }
            flash()->warning($warning_message);
            return back()->withInput();
        }

        $payment_sum = $request->input('payment_sum');
        $job_start_date = new Carbon($request->input('job_start_date'));
        $job_end_date   = new Carbon($request->input('job_end_date'));
        $working_days = $job_start_date->diffInDays($job_end_date) + 1;//->format('%H:%I:%S');

        $payload = array_merge($request->all(), [
          'business_year' => $business_year,  // 생성은 그 해에 입력하는 데이터로 한다.(수정불가)
          'working_days' => $working_days,
          'payment_do' => $payment_sum * 0.21,
          'payment_sigun' => $payment_sum * 0.49,
          'payment_center' => $payment_sum * 0.2,
          'payment_unit' => $payment_sum * 0.1,
        ]);

        try {
            $row = \App\StatusManpowerSupporter::create($payload);
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

        flash('농기계지원반 지원현황 항목이 저장되었습니다.');
        return redirect(route('status_manpower_supporters.index'));
    }

    public function show($id)
    {
        $row = \App\StatusManpowerSupporter::findOrFail($id);
        $this->authorize('show-status-manpower-supporter', $row);

        return view('status_manpower_supporters.show', compact('row'));
    }

    public function edit($id)
    {
        $siguns = \App\Sigun::orderBy('sequence')->get();
        $row = \App\StatusManpowerSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
                                          ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                                          ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                                          ->select(
                                                'status_manpower_supporters.*',
                                                'large_farmers.name as farmer_name',
                                                'manpower_supporters.name as supporter_name',
                                            )
                                          ->where('status_manpower_supporters.business_year', now()->year)
                                          ->findOrFail($id);

        // $this->authorize('edit-status-manpower-supporter', $row);

        // 목록(농협(사용자), 농가(영세농), 농기계지원반)
        $nonghyups = $this->nonghyups;
        $farmers = \App\LargeFarmer::where('nonghyup_id', $row->nonghyup_id)
                              ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        // dd($row->nonghyup_id);
        $supporters = \App\ManpowerSupporter::where('nonghyup_id', $row->nonghyup_id)
                              ->where('business_year', now()->year)
                              ->orderBy('name')
                              ->get();

        return view('status_manpower_supporters.edit', compact('row', 'supporters', 'farmers', 'nonghyups', 'siguns'));
    }

    public function update(Request $request, $id)
    {
        $row = \App\StatusManpowerSupporter::findOrFail($id);
        $this->authorize('edit-status-manpower-supporter', $row);

        // 중복 체크 start >>
        $supporter_id = $request->input('supporter_id');
        $job_start_date = $request->input('job_start_date');
        $job_end_date = $request->input('job_end_date');

        $supporter = \App\ManpowerSupporter::where('id', $supporter_id)->first();
        $supporter_name = $supporter->name;

        $duplicated_items = $this->check_duplicate($supporter_name, $job_start_date, $job_end_date);

        if (count($duplicated_items) > 0)
        {
          // $error_message = '요청하신 데이터 정보<br/>';
          flash()->error('요청하신 농기계지원반의 작업일자가 이미 등록되어 있습니다. 중복을 확인하여 주세요.');

          $warning_message = '[ 기존 등록된 데이터 정보 ]<br/>';
          foreach ($duplicated_items as $index => $item) {
              $warning_message .= ($index + 1) . '. 농협: ' . $item->nonghyup_name . ', 농가: ' . $item->farmer_name . ', 작업반: ' . $item->supporter_name . ', 시작일자: '
                          . $item->job_start_date->format('Y-m-d') . ', 종료일자: ' . $item->job_end_date->format('Y-m-d') . '<br/>';
          }
          flash()->warning($warning_message);
          return back()->withInput();
      }
        // << End.

        $job_start_date = new Carbon($request->input('job_start_date'));
        $job_end_date   = new Carbon($request->input('job_end_date'));
        $working_days = $job_start_date->diffInDays($job_end_date) + 1;//->format('%H:%I:%S');

        $payload = array_merge($request->all(), [
            'working_days' => $working_days,
        ]);

        $row->update($request->all());

        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('status_manpower_supporters.index'));
    }

    public function destroy($id)
    {
        $row = \App\StatusManpowerSupporter::findOrFail($id);
        $this->authorize('delete-status-manpower-supporter', $row);
        $row->delete();

        flash()->success('삭제되었습니다');
        return response()->json([], 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        // $this->authorize('delete-status-supporter', $row);
        $budgets = \App\StatusManpowerSupporter::whereIn('id', explode(",", $ids))->delete();

        return response()->json(['status'=>true, 'message'=>"삭제 되었습니다."], 200);
    }

    public function example()
    {
        $pathToFile = storage_path('app/public/example/' . 'uploaded_status_manpower_supporters.xlsx');
        return response()->download($pathToFile, '전업농_지원현황(예시).xlsx');
    }

    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun = $request->input('sigun');
        $nonghyup = $request->input('nonghyup');
        $keyword = $request->input('q');
        $user = auth()->user();

        $this->authorize('export-status-manpower-supporter', $nonghyup);

        return (new StatusManpowerSupportersExport())
                  ->forYear($year)
                  ->forSigun($sigun, $user)
                  ->forNonghyup($nonghyup, $user)
                  ->forKeyword($keyword)
                  ->download('인력지원반_지원현황.xlsx');
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

        $import = new StatusManpowerSupportersImport();

        // $import->import('uploaded_status_manpower_supporters.xlsx', 'local', \Maatwebsite\Excel\Excel::XLSX);
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
            return redirect(route('status_manpower_supporters.index'));
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
            return redirect(route('status_manpower_supporters.index'));
        }

        flash()->success($inserted_rows . '건의 데이터가 업로드 완료 되었습니다.');
        return redirect(route('status_manpower_supporters.index'));
    }

    private function check_duplicate($supporter_name, $job_start_date, $job_end_date)
    {
        // 중복 체크(지원반의 id가 아니라 이름으로 검색하여야 한다.)
        return $duplicated_items = \App\StatusManpowerSupporter::with('nonghyup')->with('farmer')->with('supporter')
                                      ->join('users', 'status_manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                                      ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                                      ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                                      ->select(
                                          'status_manpower_supporters.*',
                                          'users.name as nonghyup_name',
                                          'large_farmers.name as farmer_name', 'large_farmers.address as farmer_address',
                                          'manpower_supporters.name as supporter_name',
                                        )
                                      ->where('status_manpower_supporters.business_year', now()->year)
                                      ->where('manpower_supporters.name', $supporter_name)
                                      ->where(function ($query) use ($job_start_date, $job_end_date) {
                                          $query->whereBetween('status_manpower_supporters.job_start_date', [$job_start_date, $job_end_date])
                                                ->orWhereBetween('job_end_date', [$job_start_date, $job_end_date]);
                                      })
                                      ->get();
                                      // ->exists())
    }
}
