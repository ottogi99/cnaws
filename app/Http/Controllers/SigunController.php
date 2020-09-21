<?php

namespace App\Http\Controllers;

use App\Sigun;
use Illuminate\Http\Request;

class SigunController extends Controller
{
    public function index ()
    {
        $siguns = Sigun::all();

        return response()->json([
            'siguns' => $siguns
        ], 200);
    }
}
