<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Section;
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

    public function sum()
    {
        return Cost::get()->sum('value');
    }

    public function get_label_cost(Request $request)
    {
        $costs = Cost::where('label_id', $request['label_id'])->where('group_id', $request['group_code'])->with('label', 'section')->get();
        $sum = Cost::where('label_id', $request['label_id'])->where('group_id', $request['group_code'])->sum('value');
        $sumInRange = Cost::whereBetween('label_id', [1,$request['label_id']])->sum('value');
        return Response::json(['costs' => $costs,'sum' => $sum ,'range_sum' => $sumInRange]);
    }

    public function sum_pw()
    {
        // return Cost::whereBetween('label_id', [1,97])->where('group_id', 811)->sum('value');
        // return $sum_811;
        $sum_811 = Cost::whereBetween('label_id', [1,97])->where('group_id', 811)->sum('value');
        return $sum_811 + Cost::whereBetween('label_id', [1,72])->where('group_id', 812)->sum('value');
        // return Cost::where('label_id', 64)->where('group_id', 812)->sum('value');
        // return Cost::where('group_id', 811)->sum('value');
    }
}
