<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function __construct()
    {
        // 이미 로그인한 사용자가 회원 가입 주소를 직접 입력하는 것을 막자
        // 관리자만 회원 가입 주소를 입력할 수 있게 바꿔야 한다.
        // $this->middleware('guest');
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

    // 검색 기능 구현
    public function index(Request $request, $slug=null)
    {
        // $query = $slug
        //     ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
        //     : new Article;
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun_code = $request->input('sigun_code', '');
        $sort = $request->input('sort', 'users.created_at');
        $order = $request->input('order', 'desc');
        $keyword = request()->input('q');
        $user = auth()->user();

        if ($user->isAdmin()) {
            $users = \App\User::with('sigun')
                        ->join('siguns', 'users.sigun_code', 'siguns.code')
                        ->join('activated_users', 'users.nonghyup_id', 'activated_users.nonghyup_id')
                        ->select(
                            'users.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                          )
                        ->where('activated_users.business_year', $year)
                        ->when($sigun_code, function($query, $sigun_code) {
                            return $query->where('users.sigun_code', $sigun_code);
                        }, function($query, $user) {
                            return $query;
                        })
                        ->when($keyword, function($query, $keyword) {
                            return $query->where('users.nonghyup_id', 'like', '%'.$keyword.'%')
                                          ->orWhere('users.name', 'like', '%'.$keyword.'%')
                                          ->orWhere('users.representative', 'like', '%'.$keyword.'%');
                        })
                        // ->orderby($sort, $order)
                        ->orderby('siguns.sequence')
                        ->orderby('users.is_admin', 'DESC')
                        ->orderby('users.sequence')
                        ->paginate(10);
        } else {
            $users = \App\User::with('sigun')
                        ->join('siguns', 'users.sigun_code', 'siguns.code')
                        ->join('activated_users', 'users.nonghyup_id', 'activated_users.nonghyup_id')
                        ->select(
                            'users.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                          )
                        ->where('activated_users.business_year', $year)
                        ->where('users.nonghyup_id', $user->nonghyup_id)
                        ->when($sigun_code, function($query, $sigun_code) {
                            return $query->where('users.sigun_code', $sigun_code);
                        }, function($query, $user) {
                            return $query;
                        })
                        ->when($keyword, function($query, $keyword) {
                            return $query->where('users.nonghyup_id', 'like', '%'.$keyword.'%')
                                          ->orWhere('users.name', 'like', '%'.$keyword.'%')
                                          ->orWhere('users.representative', 'like', '%'.$keyword.'%');
                        })
                        // ->orderby($sort, $order)
                        ->orderby('siguns.sequence')
                        ->orderby('users.is_admin', 'DESC')
                        ->orderby('users.sequence')
                        ->paginate(10);
        }

        $siguns = $this->siguns;

        // 데이터 입력 일정 적용
        $schedule = \App\Schedule::first();
        if ($schedule->is_period) {
            if (now() < $schedule->input_start_date || now() > $schedule->input_end_date)
                $schedule->is_allow = false;
        }

        $this->authorize('index-user', $user->nonghyup_id);

        // return view('users.index', compact(['siguns', 'nonghyups', 'users']));
        return view('users.index', compact(['siguns', 'users', 'schedule']));
    }


    public function create()
    {
        $nonghyup = new \App\User;
        $nonghyups = $this->nonghyups;
        $siguns = \App\Sigun::orderBy('sequence')->get();

        return view('users.create', compact('siguns', 'nonghyup', 'nonghyups'));
    }

    // public function store(\App\Http\Requests\UserRequest $request)
    public function store(Request $request)
    {
        $rules = [
            'sigun_code' => ['required'],
            'nonghyup_id' => ['required','unique:users','regex:/^[A-Za-z]{1}[A-Za-z0-9_]{3,11}$/'], // 아이디 4자리~12자리
            'password' => ['required','confirmed','regex:/^.*(?=.{8,17})(?=.*[0-9])(?=.*[a-zA-Z]).*$/'],    // 영문,숫자 혼용해서 8~17자리
            'is_admin' => ['required'],
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'unique'  => '이미 등록된 :attribute 항목이 존재합니다',
            'regex'   => ':attribute가 유효한 형식이 아닙니다',
            'min' => ':attribute은(는) 최소 :min 글자 이상이 필요합니다.',
            'max' => ':attrubute은(는) 최대 :max 글자를 초과할 수 없습니다',
            'confirmed' => ':attribute가 일치하지 않습니다.',
        ];

        $attributes = [
            'sigun_code'      => '시군항목',
            'nonghyup_id'     => '농협ID',
            'password'        => '비밀번호',
            'is_admin'        => '권한',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $payload = array_merge($request->all(), [
          'password' => bcrypt($request->input('password'))
        ]);

        DB::beginTransaction();
        try
        {
            // $users = \App\User::create($request->all());
            $nonghyup = \App\User::create($payload);

            \App\ActivatedUser::create([
                'nonghyup_id' => $request->nonghyup_id,
                'business_year' => now()->format('Y'),
            ]);

            $history = \App\UserHistory::create([
                'worker_id' => auth()->user()->nonghyup_id,
                'target_id' => $request->nonghyup_id,
                'contents' => '사용자 생성',
            ]);

            DB::commit();

            flash('새 농협 항목이 저장되었습니다.');
            return redirect(route('users.index'));
        }
        catch (Exception $e)
        {
            DB::rollback();

            // if (! $users) {
                flash('농협 항목이 저장되지 않았습니다.');
                return back()->withInput();
            // }
        }
    }

    // public function show(\App\User $user)
    public function show($id)
    {
        $nonghyup = \App\User::findOrFail($id);
        $this->authorize('show-users', $nonghyup->nonghyup_id);
        return view('users.show', compact('nonghyup'));
    }

    // public function edit(\App\User $user)
    public function edit($id)
    {
        $nonghyup = \App\User::with('sigun')->findOrFail($id);
        $this->authorize('edit-users', $nonghyup->nonghyup_id);
        $siguns = \App\Sigun::orderBy('sequence')->get();

        return view('users.edit', compact('siguns', 'nonghyup'));
    }

    // public function update(\App\Http\Requests\UserUpdateRequest $request, \App\User $user)
    public function update(Request $request, $id)
    {
        // dd($request);
        $rules = [
            'sigun_code' => ['required'],
            'nonghyup_id' => ['required','regex:/^[A-Za-z]{1}[A-Za-z0-9_]{3,11}$/'], // 아이디 4자리~12자리
            'password' => ['required','regex:/^.*(?=.{8,17})(?=.*[0-9])(?=.*[a-zA-Z]).*$/'],    // 영문,숫자 혼용해서 8~17자리
            'name' => ['required','min:3','max:255'],
            'address' => ['required','max:255'],
            'contact' => ['required','regex:/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/'],        // 전화번호 형식
            'representative' => ['required','min:2','max:15'],
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'unique'  => '이미 등록된 :attribute 항목이 존재합니다',
            'regex'   => ':attribute가 유효한 형식이 아닙니다',
            'min' => ':attribute은(는) 최소 :min 글자 이상이 필요합니다.',
            'max' => ':attribute은(는) 최대 :max 글자를 초과할 수 없습니다',
        ];

        $attributes = [
            'sigun_code'      => '시군항목',
            'nonghyup_id'     => '농협ID',
            'password'        => '비밀번호',
            'name'            => '농협명',
            'address'         => '도로명 주소',
            'contact'         => '연락처',
            'representative'  => '대표자',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $payload = array_merge($request->all(), [
          'password' => bcrypt($request->input('password'))
        ]);
        // $user->update($request->all());
        $nonghyup = \App\User::findOrFail($id);

        if ($nonghyup->is_admin != $request->input('is_admin')) {
           $contents = '권한 변경(';
           if ($request->input('is_admin')) {
              $contents = $contents.'관리자)';
           } else {
              $contents = $contents.'일반)';
           }

           $history = \App\UserHistory::create([
               'worker_id' => auth()->user()->nonghyup_id,
               'target_id' => $request->nonghyup_id,
               'contents' => $contents,
           ]);
        }

        $nonghyup->update($payload);

        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('users.index'));
    }

    public function destroy($id)
    {
        $nonghyup = \App\User::findOrFail($id);
        $this->authorize('delete-users', $nonghyup->nonghyup_id);

        DB::beginTransaction();
        try
        {
            $nonghyup->delete();

            $activated_user = \App\ActivatedUser::where('business_year', now()->format('Y'))
                                              ->where('nonghyup_id', $nonghyup->nonghyup_id)
                                              ->firstOrFail();
            $activated_user->delete();

            $history = \App\UserHistory::create([
                'worker_id' => auth()->user()->nonghyup_id,
                'target_id' => $nonghyup->nonghyup_id,
                'contents' => '사용자 삭제',
            ]);

            DB::commit();

            flash()->success('삭제되었습니다');
            return response()->json([], 204);
        }
        catch (Exception $e)
        {
            DB::rollback();

            flash('농협 항목을 삭제하지 못하였습니다.');
            return response()->json([], 500);
        }
    }


    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        // $this->authorize('delete-small-farmer', $farmer);
        $budgets = \App\User::whereIn('id', explode(",", $ids))->delete();

        return response()->json(['status'=>true, 'message'=>"삭제 되었습니다."], 200);
    }

    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun = $request->input('sigun');
        $keyword = $request->input('q');

        return (new UsersExport())
                  ->forYear($year)
                  ->forSigun($sigun)
                  ->forKeyword($keyword)
                  ->download('참여농협정보.xlsx');
    }

    public function import(Request $request, $file)
    {
        $import = new UsersImport();
        $import->import('uploaded_users.xlsx', 'local', \Maatwebsite\Excel\Excel::XLSX);

        $failures = $import->failures();   // Import Failure
        $errors = $import->errors();    // Import Error

        if ($failures) {
            foreach($failures as $failure) {
                $row = $failure->row();
                $column = $failure->attribute();
                Log::debug($row.'행 '.$column.'열: '.$failure->errors()[0]);
            }
        }

        if ($errors->count() > 0) {
            foreach($errors as $error) {
                Log::debug($error->getCode());      // DB 에러코드 (SQLSTATE error code)
                Log::debug($error->getMessage());   // 에러 메시지-
                // Log::debug($error->errorInfo);      // SQLSTATE error code / Driver-specific error code / Driver-specific error message
            }
        }

        flash()->success('저장 되었습니다.');
        return redirect(route('users.index'));
    }

    public function activate(Request $request, $id)
    {
        if ($request->ajax() & $request->isMethod('PATCH')) {
            $user = \App\User::findOrFail($id);
            $flag = $request->input('activated');

            $this->authorize('activate-users', $user->nonghyup_id);

            $user->update(['activated' => !$flag]);

            $message = sprintf('사용자를 %s 하였습니다.', $flag == '0' ? '활성화' : '비활성화');
            flash()->success($message);
            return response()->json([], 204);
        }
    }

    public function copy(Request $request, $business_year)
    {
        $users = \App\User::join('siguns', 'users.sigun_code', 'siguns.code')
                    ->join('activated_users', 'users.nonghyup_id', 'activated_users.nonghyup_id')
                    ->select(
                        'users.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                      )
                    ->where('activated_users.business_year', $business_year)
                    ->get();

        $this->authorize('copy-users', $users[0]->nonghyup_id);

        foreach ($users as $user) {
            \App\ActivatedUser::create([
              'business_year' => now()->format('Y'),
              'nonghyup_id' => $user->nonghyup_id,
            ]);
        }

        flash()->success('전년도 사용자 데이터를 금년도 사용자로 데이터로 복사 하였습니다.');
        return redirect(route('users.index'));
    }

    public function list(Request $request)
    {
        $sigun_code = $request->input('sigun_code', '');

        $nonghyups = \App\User::join('siguns', 'users.sigun_code', 'siguns.code')
                              ->select(
                                  'users.nonghyup_id', 'users.name'
                                )
                              ->when($sigun_code, function($query, $sigun_code) {
                                  return $query->where('users.sigun_code', $sigun_code);
                              }, function($query, $user) {
                                  return $query;
                              })
                              ->where('users.is_admin', '!=', 1)
                              ->orderby('siguns.sequence')
                              ->orderby('users.is_admin', 'DESC')
                              ->orderby('users.sequence')
                              ->get()->toArray();
        // $this->authorize('delete-users', $nonghyup->nonghyup_id);

        // flash()->success('삭제되었습니다');
        // return response()->json([], 204);
        return response()->json($nonghyups, 200);
    }
}
