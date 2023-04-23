<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesmanController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['foo' => Auth::user()->tokenCan('server:update')], 200);
    }

    public function create(Request $request)
    {

    }

    public function update(string $uuid, Request $request)
    {

    }

    public function destroy(string $uuid, Request $request)
    {

    }
}
