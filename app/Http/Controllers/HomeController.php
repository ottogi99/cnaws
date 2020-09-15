<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $slug=null)
    {
        $year = now()->year;
        $sigun_code = $request->input('sigun_code', '');
        $nonghyup_id = $request->input('nonghyup_id', '');
        $sort = $request->input('sort', 'users.created_at');
        $order = $request->input('order', 'desc');
        $keyword = request()->input('q');
        $user = auth()->user();

        // 농기계지원반 모집현황
        $rows_machine = \App\StatusMachineSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
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
                    ->when($keyword, function($query, $keyword) {
                        // 시군명, 대상농협, 농가명, 작업자명으로 검색
                        return $query->whereRaw(
                                      '(siguns.name like ? or users.name like ? or small_farmers.name like ? or status_machine_supporters.name like ?)',
                                      [$keyword, $keyword, $keyword, $keyword]
                                    );
                    })
                    // ->when($keyword, function($query, $keyword) use ($raw) {
                    //     return $query->whereRaw($raw, [$keyword]);
                    // })
                    ->orderby('siguns.sequence')
                    ->orderby('users.sequence')
                    ->orderby('status_machine_supporters.created_at', 'desc')
                    //->orderby($sort, $order)
                    ->paginate(10);

      $rows_manpower = \App\StatusManpowerSupporter::with('sigun')->with('nonghyup')->with('farmer')->with('supporter')
                  ->join('siguns', 'status_manpower_supporters.sigun_code', 'siguns.code')
                  ->join('users', 'status_manpower_supporters.nonghyup_id', 'users.nonghyup_id')
                  ->join('large_farmers', 'status_manpower_supporters.farmer_id', 'large_farmers.id')
                  ->join('manpower_supporters', 'status_manpower_supporters.supporter_id', 'manpower_supporters.id')
                  ->select(
                      'status_manpower_supporters.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                      'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name',
                      'large_farmers.name as farmer_name', 'large_farmers.address as farmer_address', 'large_farmers.sex as farmer_sex',
                      'manpower_supporters.name as supporter_name',
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
                  //->orderby($sort, $order)
                  ->paginate(10);


        return view('home.index', compact('rows_machine', 'rows_manpower'));
    }
}
