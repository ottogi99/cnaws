<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index (Request $request)
    {
        $sigun_code = request()->input('sigun_code');

        $users = User::with('sigun')
                      ->join('siguns', 'users.sigun_code', 'siguns.code')
                      ->select('users.*')
                      ->where('users.is_admin', '!=', 1)
                      ->orderBy('siguns.sequence')
                      ->orderBy('users.sequence')
                      ->get();

        return response()->json([
            'users' => $users
        ], 200);
    }
}
