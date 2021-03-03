<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SectionController extends Controller
{
    public function add_section(Request $request)
    {
        return Response::json([$request]);
    }
}
