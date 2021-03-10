<?php

use App\Models\Cost;
use App\Models\Label;
use App\Models\Section;
use App\Models\Tashimlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CostController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TashimlogController;
use App\Http\Controllers\TahsimlableController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//genreral
Route::get('/', function () {
    return view('welcome');
});

// sections
Route::post('/add_section', [SectionController::class,'add_section']);
Route::get('/get_section', [SectionController::class,'get_section']);
Route::get('/get_section_cost', [SectionController::class,'get_section_cost']);
Route::post('/get_section_cost_with_id', [SectionController::class,'get_section_cost_with_id']);
Route::post('/remove_section', [SectionController::class,'remove_section']);
Route::post('/update_section', [SectionController::class,'update_section']);
//label
Route::get('/get_label', [LabelController::class,'get_label']);
Route::post('/add_label', [LabelController::class,'add_label']);
Route::post('/remove_label', [LabelController::class,'remove_label']);
Route::post('/import_label', [LabelController::class,'import_label']);
Route::get('/export_label', [LabelController::class,'export_label']);
//tahsim
Route::get('/get_tahsimlabel', [TahsimlableController::class,'get_tahsimlabel']);
Route::post('/remove_tashimlabel', [TahsimlableController::class,'remove_tashimlabel']);
Route::post('/add_tahsimlable', [TahsimlableController::class,'add_tahsimlable']);

// cost
Route::post('/import_cost', [CostController::class,'import_cost']);
Route::post('/get_label_cost', [CostController::class,'get_label_cost']);


// test
Route::get('/sum', [CostController::class,'sum']);
Route::get('/sum_pw', [CostController::class,'sum_pw']);
// tashim
Route::get('/get_tashim_log', [TashimlogController::class,'get_tashim_log']);

Route::get('/tahsim', function (Request $request) {
    $from_sections = Section::where('id', 19)->with('costs.label')->first();
    foreach ($from_sections->costs as $from_cost) {
        $from_label_code = $from_cost->label->code;
        $from_label_id = $from_cost->label->id;
        $total_users = Section::all()->sum('users');
        $from_users = Section::where('id', $from_cost->section_id)->first()->users;
        $users = $total_users - $from_users;
        $cost_for_each_section = $from_cost->value / $users;
        $to_sections = Section::where('id', '!=', 19)->get();
        foreach ($to_sections as $to_section) {
            $to_group_code = 0;
            if ($to_section->group_id === 1) {
                $to_group_code = 811 ;
            } elseif ($to_section->group_id === 2) {
                $to_group_code = 812 ;
            } else {
                $to_group_code = 813 ;
            }

            $to_label =  Label::where('code', $from_label_code)->where('group_code', $to_group_code)->first();
            $to_label_id = $to_label->id;

            $to_costs = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->firstOrCreate([
                'label_id' => $to_label_id
            ], [
                'label_id' => $to_label_id,
                'section_id' => $to_section->id,
                'group_id' => $to_group_code,
                'value' => 0
            ]);
           

            $from_group_code = 0;
            if ($from_sections->group_id === 1) {
                $from_group_code = 811 ;
            } elseif ($to_section->group_id === 2) {
                $from_group_code = 812 ;
            } else {
                $from_group_code = 813 ;
            }
            
            $new_from_cost = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->first();
            $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                'value' => DB::raw("ROUND(value - ($cost_for_each_section * $to_section->users))")
            ]);
            
            $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                'value' => DB::raw("ROUND(value + ($cost_for_each_section * $to_section->users))")
            ]);

            if ($from_label_code === 1) {
                error_log($new_from_cost->value - $cost_for_each_section * $to_section->users);
                // error_log($cost_for_each_section * $to_section->users);
                // error_log($to_costs->value);
            }
        }
    }
    return Response::json(['done']);
});
