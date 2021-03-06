<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Imports\CostImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class CostController extends Controller
{
    public function import_cost(Request $request)
    {
        Excel::import(new CostImport, $request['exel']);
        return Response::json(['imported']);
    }
}
