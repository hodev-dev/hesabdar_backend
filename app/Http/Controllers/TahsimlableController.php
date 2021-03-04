<?php

namespace App\Http\Controllers;

use App\Models\Tahsimlable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TahsimlableController extends Controller
{
    protected $fillable = ['name'];

    public function get_tahsimlabel()
    {
        return Tahsimlable::all();
    }
    public function remove_tashimlabel(Request $request)
    {
        Tahsimlable::where('id', $request['id'])->delete();
        return Response::json([$request['id']]);
    }
    public function add_tahsimlable(Request $request)
    {
        Tahsimlable::insert(['name' => $request['name']]);
        return Response::json([$request['name']]);
    }
}
