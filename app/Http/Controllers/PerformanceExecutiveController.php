<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
// Laravel Excel
use App\Exports\PerformanceExecutiveExport;
use Maatwebsite\Excel\Facades\Excel;

class PerformanceExecutiveController extends Controller
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

        $raw = sprintf("CALL GetPerformanceExecutive('%s', '%s', '%s')", $year, $sigun_code, $nonghyup_id);
        $rows = DB::select(DB::raw($raw));
        $rows = $this->arrayPaginator($rows, $request);

        $siguns = $this->siguns;
        if ($user->isAdmin()) {
            $nonghyups = $this->nonghyups;
        } else {
            $nonghyups = \App\User::where('sigun_code', $sigun_code)
                                  ->orderBy('sequence')
                                  ->get();
        }

        return view('performance_executive.index', compact('rows', 'siguns', 'nonghyups'));
    }

    public function show($id)
    {
        //
    }

    public function export(Request $request)
    {
        $year = (request()->input('year')) ? request()->input('year') : now()->year;
        $sigun_code = $request->input('sigun');
        $nonghyup_id = $request->input('nonghyup');

        $year = ($year) ? $year : now()->format('Y');

        // $this->authorize('export-performance_executive', $nonghyup);
        $export = new PerformanceExecutiveExport([$year, $sigun_code, $nonghyup_id]);
        return $export->download('농작업지원단_집행실적.xlsx');
    }

    public function arrayPaginator($array, $request)
    {
        // $page = Input::get('page', 1);
        $page = $request->input('page', 1);
        $perPage = 20;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
