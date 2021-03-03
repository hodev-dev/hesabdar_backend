<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
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
}
