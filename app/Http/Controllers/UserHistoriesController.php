<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UserHistoriesExport;

class UserHistoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->siguns = \App\Sigun::orderBy('sequence')->get();
    }

    public function index(Request $request, $slug=null)
    {
        $item = $request->input('item', '');
        $keyword = request()->input('q');
        $user = auth()->user();

        // $users = \App\UserHistory::with('sigun')
        //             ->join('siguns', 'users.sigun_code', 'siguns.code')
        //             ->join('activated_users', 'users.nonghyup_id', 'activated_users.nonghyup_id')
        //             ->select(
        //                 'users.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
        //               )
        //             ->where('activated_users.business_year', $year)
        //             ->when($sigun_code, function($query, $sigun_code) {
        //                 return $query->where('users.sigun_code', $sigun_code);
        //             }, function($query, $user) {
        //                 return $query;
        //             })
        //             ->when($keyword, function($query, $keyword) {
        //                 return $query->where('users.nonghyup_id', 'like', '%'.$keyword.'%')
        //                               ->orWhere('siguns.name', 'like', '%'.$keyword.'%');
        //             })
        //             // ->orderby($sort, $order)
        //             ->orderby('siguns.sequence')
        //             ->orderby('users.is_admin', 'DESC')
        //             ->orderby('users.sequence')
        //             ->paginate(10);

        if ($item) {
          $histories = \App\UserHistory::when($keyword, function($query, $keyword) {
                                            return $query->whereRaw($item.' like \'%'.$keyword.'%\'');
                                          })
                                          ->orderby('created_at', 'DESC')->paginate(10);
        } else {
          $histories = \App\UserHistory::orderby('created_at', 'DESC')->paginate(10);
        }

        $siguns = $this->siguns;

        // $this->authorize('index-user', $user->nonghyup_id);

        // return view('users.index', compact(['siguns', 'nonghyups', 'users']));
        return view('user_histories.index', compact(['siguns', 'histories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        $item = $request->input('item');
        $keyword = $request->input('q');

        return (new UserHistoriesExport())
                  ->forItem($item)
                  ->forKeyword($keyword)
                  ->download('사용자이력.xlsx');
    }
}
