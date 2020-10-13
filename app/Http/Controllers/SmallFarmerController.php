<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class SmallFarmerController extends Controller
{
    //
    public function __construct()
    {
        // 로그인한 사용자만 접근 가능
        // $this->middleware('auth');
        //
        // $this->siguns = \App\Sigun::orderBy('sequence')->get();
        // $this->nonghyups = \App\User::with('sigun')
        //                       ->join('siguns', 'users.sigun_code', 'siguns.code')
        //                       ->select('users.*')
        //                       ->where('users.is_admin', '!=', 1)
        //                       ->orderBy('siguns.sequence')
        //                       ->orderBy('users.sequence')
        //                       ->get();
    }

    public function searchTest(Request $request, $keyword)
    {
        $year = now()->year;
        $nonghyup_id = '';
        $farmers = \App\SmallFarmer::searchSmall('농가1')
                    ->whereRaw('small_farmers.business_year', 2002)
                    ->when($nonghyup_id, function($query, $nonghyup_id) {
                        return $query->whereRaw('small_farmers.nonghyup_id', $nonghyup_id);
                    })
                    // // ->when($sigun_code, function($query, $sigun_code) {
                    // //     return $query->where('small_farmers.sigun_code', $sigun_code);
                    // // })
                    ->orderbyRaw('siguns.sequence')
                    ->orderbyRaw('users.sequence')
                    ->orderbyRaw('small_farmers.created_at', 'desc')
                    ->get()->toJson();

        return response()->json([
            'status' => 'success',
            'data' => $farmers
        ], 200);
    }

    public function search(Request $request)
    {
        // Log::debug($request->all());

        $currentPage = $request->input("currentPage");
        $countPerPage = $request->input("countPerPage");
        $resultType = $request->input("resultType");
        $confmKey = $request->input("confmKey");
        $keyword = $request->input("keyword");
        $currentPage = $request->input("page");

        $year = now()->year;
        $nonghyup_id = $request->input("nonghyup_id");

        // if (!auth()->user()->isAdmin())
        //     $nonghyup_id = auth()->user()->nonghyup_id;

        // Log::debug($keyword);

        // Log::debug($this->fullTextWildcards($keyword));

        // $farmers = \App\SmallFarmer::search('농가1')->get()->toJson();
        // $farmers = \App\SmallFarmer::search($keyword)->get()->toJson();

        // $farmers = \App\SmallFarmer::search($keyword)
        //             ->whereRaw('small_farmers.business_year', $year)
        //             ->when($nonghyup_id, function($query, $nonghyup_id) {
        //                 return $query->whereRaw('small_farmers.nonghyup_id', $nonghyup_id);
        //             })
        //             // ->when($sigun_code, function($query, $sigun_code) {
        //             //     return $query->where('small_farmers.sigun_code', $sigun_code);
        //             // })
        //             ->orderbyRaw('siguns.sequence')
        //             ->orderbyRaw('users.sequence')
        //             ->orderbyRaw('small_farmers.created_at', 'desc')
        //             ->get()->toJson();

        $farmers = \App\SmallFarmer::searchSmall($keyword)
                    ->whereRaw('small_farmers.business_year = ?', [$year])
                    ->when($nonghyup_id, function($query, $nonghyup_id) {
                        return $query->whereRaw('small_farmers.nonghyup_id = ?', [$nonghyup_id]);
                    })
                    // ->when($sigun_code, function($query, $sigun_code) {
                    //     return $query->where('small_farmers.sigun_code', $sigun_code);
                    // })
                    ->orderbyRaw('siguns.sequence')
                    ->orderbyRaw('users.sequence')
                    ->orderbyRaw('small_farmers.name')
                    // ->paginate();//paginate(1);
                    ->get()->toArray();//paginate(1);

        // $farmers = \App\SmallFarmer::search($keyword)->paginate();

        // Log::debug($farmers);
        // Log::debug($farmers->toJson());
        // Log::debug($farmers->links());

        $rows = $this->arrayPaginator($farmers, $request);
        // Log::debug($rows->toJson());

        return response()->json([
            'status' => 'success',
            'results' => $rows
        ], 200);
    }

    public function arrayPaginator($array, $request)
    {
        // $page = Input::get('page', 1);
        // $page = $request->input('page', 1);
        $page = $request->input("currentPage");
        $perPage = 5;
        $offset = ($page * $perPage) - $perPage;

        // return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
        //     ['path' => $request->url(), 'query' => $request->query()]);

        $itemsCurrentPage = array_slice($array, $offset, $perPage, true);

        return new LengthAwarePaginator(array_values($itemsCurrentPage), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    // public function arrayPaginator($collection, $request)
    // {
    //     // $page = Input::get('page', 1);
    //     // $page = $request->input('page', 1);
    //     $page = $request->input("currentPage");
    //     $perPage = 1;
    //     $offset = ($page * $perPage) - $perPage;
    //
    //     return new LengthAwarePaginator($collection->slice($offset, $perPage), $collection->count(), $perPage, $page,
    //         ['path' => $request->url(), 'query' => $request->query()]);
    // }
}
