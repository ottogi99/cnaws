<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Laravel Excel
use App\Exports\BudgetsExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Log;

class BudgetsController extends Controller
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

        $budgets = \App\Budget::with('sigun')->with('nonghyup')
                              ->join('siguns', 'budgets.sigun_code', 'siguns.code')
                              ->join('users', 'budgets.nonghyup_id', 'users.nonghyup_id')
                              ->select(
                                  'budgets.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                                  'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                                )
                              ->where('budgets.business_year', $year)
                              ->where('users.is_admin', '!=', 1)
                              ->when($sigun_code, function($query, $sigun_code) {
                                  return $query->where('budgets.sigun_code', $sigun_code);
                              })
                              ->when($nonghyup_id, function($query, $nonghyup_id) {
                                  return $query->where('budgets.nonghyup_id', $nonghyup_id);
                              })
                              ->when($keyword, function($query, $keyword) {
                                  // 시군명, 대상농협, 농가명으로 검색
                                  return $query->whereRaw(
                                                '(siguns.name like ? or users.name like ?)',
                                                [$keyword, $keyword]
                                              );

                              })
                              ->orderby('siguns.sequence')
                              ->orderby('users.sequence')
                              ->orderby('budgets.created_at', 'desc')
                              // ->orderby($sort, $order)
                              ->paginate(10);

        if ($user->isAdmin()) {
            $nonghyups = $this->nonghyups;
        } else {
            $nonghyups = \App\User::where('sigun_code', $sigun_code)
                                  ->orderBy('sequence')
                                  ->get();
        }

        $siguns = $this->siguns;

        return view('budgets.index', compact('budgets', 'siguns', 'nonghyups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $siguns = $this->siguns;
        $nonghyups = $this->nonghyups;
        $budget = new \App\Budget;

        return view('budgets.create', compact('budget', 'nonghyups', 'siguns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'sigun_code' => ['required'],
            'nonghyup_id' => ['required'],
            'amount' => ['required','numeric'],
        ];

        $messages = [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'numeric' => ':attribute은(는) 숫자형태만 가능합니다.',
        ];

        $attributes = [
            'sigun_code'      => '시군항목',
            'nonghyup_id'     => '농협ID',
            'amount'        => '비밀번호',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $payload = array_merge($request->all(), [
          'password' => bcrypt($request->input('password'))
        ]);

        $user = auth()->user();

        if ($user->isAdmin()) {
            $sigun_code = $request->input('sigun_code');
            $nonghyup_id = $request->input('nonghyup_id');
        } else {
          $sigun_code = $user->sigun_code;
          $nonghyup_id = $user->nonghyup_id;
        }
        $business_year = $request->input('business_year');//now()->format('Y');
        $payload = array_merge($request->all(), [
          'sigun_code' => $sigun_code,
          'nonghyup_id' => $nonghyup_id,
          // 'business_year' => $business_year  // 생성은 그 해에 입력하는 데이터로 한다.(수정불가)
        ]);

        Log::debug($request->all());
        if (\App\Budget::where('business_year', $business_year)
                          ->where('nonghyup_id', $nonghyup_id)->exists())
        {
            flash('해당 년도의 사업비는 이미 추가되어있습니다.')->error();
            return back()->withInput();
        }

        $budget = \App\Budget::create($payload);

        if (! $budget) {
            flash('사업비 항목이 저장되지 않았습니다.');
            return back()->withInput();
        }

        flash('사업비 항목이 저장되었습니다.');
        return redirect(route('budgets.index'));
        dd($payload);
    }

    // public function show($id)
    // {
    //     $budget = \App\Budget::findOrFail($id);
    //     $this->authorize('show-budgets', $budget);
    //
    //     return view('budgets.show', compact('budget'));
    // }

    public function edit($id)
    {
        $siguns = \App\Sigun::orderBy('sequence')->get();
        $nonghyups = $this->nonghyups;
        $budget = \App\Budget::findOrFail($id);

        // $this->authorize('edit-budgets', $budget);
        return view('budgets.edit', compact('siguns', 'nonghyups', 'budget'));
    }

    public function update(Request $request, $id)
    {
        $budget = \App\Budget::findOrFail($id);
        $this->authorize('edit-budgets', $budget);

        if ($request->input('business_year') != $budget->business_year) {
          if (\App\Budget::where('business_year', $request->input('business_year'))
                            ->where('nonghyup_id', $request->input('nonghyup_id'))->exists())
          {
              flash('해당 년도의 사업비는 이미 추가되어있습니다.')->error();
              return back()->withInput();
          }
        }

        $budget->update($request->all());

        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('budgets.index'));
    }

    public function destroy($id)
    {
        $budget = \App\Budget::findOrFail($id);
        Log::debug($budget->nonghyup_id);
        Log::debug(auth()->user()->nonghyup_id);
        $this->authorize('delete-budgets', $budget);
        $budget->delete();

        flash()->success('삭제되었습니다');
        return response()->json([], 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        // $this->authorize('delete-budgets', $budgets);
        $budgets = \App\Budget::whereIn('id', explode(",", $ids))->delete();

        return response()->json(['status'=>true, 'message'=>"삭제 되었습니다."], 200);
    }

    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun = $request->input('sigun');
        $nonghyup = $request->input('nonghyup');
        $keyword = $request->input('q');
        $user = auth()->user();

        // $this->authorize('export-large-farmer', $nonghyup);

        return (new BudgetsExport())
                  ->forYear($year)
                  ->forSigun($sigun, $user)
                  ->forNonghyup($nonghyup, $user)
                  ->forKeyword($keyword)
                  ->download('사업비현황.xlsx');
    }
}
