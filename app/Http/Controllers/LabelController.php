<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Exports\LableExport;
use App\Imports\LableImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class LabelController extends Controller
{
    protected function get_label(Request $request)
    {
        return Label::all();
    }
    protected function add_label(Request $request)
    {
        $name = $request['name'];
        $add_label = Label::insert(['name' => $name]);
        if ($add_label) {
            return Response::json([true]);
        } else {
            return Response::json([false]);
        }
    }
    protected function remove_label(Request $request)
    {
        $id = $request['id'];
        Label::where('id', $id)->delete();
        return Response::json([$id]);
    }

    protected function import_label(Request $request)
    {
        Excel::import(new LableImport, $request['exel']);
        return Response::json(['imported']);
    }

    protected function export_label()
    {
        return Excel::download(new LableExport, 'label.xlsx');
    }
}
