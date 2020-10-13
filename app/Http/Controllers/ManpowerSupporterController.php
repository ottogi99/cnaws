<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ManpowerSupporterController extends Controller
{
    public function search(Request $request)
    {
        $currentPage = $request->input("currentPage");
        $countPerPage = $request->input("countPerPage");
        $resultType = $request->input("resultType");
        $confmKey = $request->input("confmKey");
        $keyword = $request->input("keyword");
        $currentPage = $request->input("page");

        $year = now()->year;
        $nonghyup_id = $request->input("nonghyup_id");

        $supporters = \App\ManpowerSupporter::searchManpower($keyword)
                    ->whereRaw('manpower_supporters.business_year = ?', [$year])
                    ->when($nonghyup_id, function($query, $nonghyup_id) {
                        return $query->whereRaw('manpower_supporters.nonghyup_id = ?', [$nonghyup_id]);
                    })
                    ->orderbyRaw('siguns.sequence')
                    ->orderbyRaw('users.sequence')
                    ->orderbyRaw('manpower_supporters.name')
                    ->get()->toArray();//paginate(1);

        // Log::debug($farmers);
        // Log::debug($farmers->toJson());
        // Log::debug($farmers->links());

        $rows = $this->arrayPaginator($supporters, $request);
        Log::debug($rows->toJson());

        return response()->json([
            'status' => 'success',
            'results' => $rows
        ], 200);
    }

    public function arrayPaginator($array, $request)
    {
        $page = $request->input("currentPage");
        $perPage = 5;
        $offset = ($page * $perPage) - $perPage;

        $itemsCurrentPage = array_slice($array, $offset, $perPage, true);

        return new LengthAwarePaginator(array_values($itemsCurrentPage), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
