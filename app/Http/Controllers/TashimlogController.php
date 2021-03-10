<?php

namespace App\Http\Controllers;

use App\Models\Tashimlog;
use Illuminate\Http\Request;

class TashimlogController extends Controller
{
    public function get_tashim_log()
    {
        return Tashimlog::all();
    }
}
