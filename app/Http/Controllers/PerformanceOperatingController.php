<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
// Laravel Excel
use App\Exports\PerformanceOperatingExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Log;

class PerformanceOperatingController extends Controller
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

        $raw = sprintf("CALL GetPerformanceOperating('%s', '%s', '%s')", $year, $sigun_code, $nonghyup_id);
        Log::debug($raw);

        $rows = DB::select(DB::raw($raw));
        // $rows = DB::select($raw);
        $rows = $this->arrayPaginator($rows, $request);

        $siguns = $this->siguns;
        if ($user->isAdmin()) {
            $nonghyups = $this->nonghyups;
        } else {
            $nonghyups = \App\User::where('sigun_code', $sigun_code)
                                  ->orderBy('sequence')
                                  ->get();
        }

        return view('performance_operating.index', compact('rows', 'siguns', 'nonghyups'));
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

        // $this->authorize('export-performance_operating', $nonghyup);
        $export = new PerformanceOperatingExport([$year, $sigun_code, $nonghyup_id]);
        return $export->download('농작업지원단_운영실적.xlsx');

        // return (new PerformanceOperatingExport())
        //           ->forYear($year)
        //           ->forSigun($sigun)
        //           ->forNonghyup($nonghyup)
        //           ->forKeyword($keyword)
        //           ->download('농작업지원단_운영실적.xlsx');
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
