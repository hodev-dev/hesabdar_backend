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

Route::post('/tashim', function (Request $request) {
    $from_sections = Section::where('id', $request->id)->where('group_id', '!=', 1)->with('costs.label')->first();
    foreach ($from_sections->costs as $from_cost) {
        $from_label = Label::find($from_cost->label_id);
        $from_label_code = $from_label->code;
        $total_users = Section::all()->sum('users');
        $total_users_without_from = $total_users - $from_sections->users;
        $cost_of_each_label_for_each_user = $from_cost->value / $total_users_without_from ;
        $to_label = Label::where('code', $from_label_code)->where('group_code', 811)->first();
        $to_sections_with_costs = Section::where('id', '!=', $request->id)->with('costs.label')->get();
        foreach ($to_sections_with_costs as $to_section) {
            error_log($from_cost->value);
            $get_section_id;
            if ($to_section->group_id === 1) {
                $get_section_id = 811;
            } elseif ($to_section->group_id === 2) {
                $get_section_id = 812;
            } else {
                $get_section_id = 813;
            }
            $cost_of_each_section = $cost_of_each_label_for_each_user * $to_section->users;
            $corresponding_cost =  Cost::where('section_id', $to_section->id)->where('label_id', $to_label->id)
            ->firstOrCreate(
                [
                    'label_id' => $to_label->id
                ],
                [
                'label_id' => $to_label->id,
                'section_id' => $to_section->id,
                'group_id' => $get_section_id,
                'value' => 0
            ]
            );

            Tashimlog::insert([
                'type' => 0,
                'label_id' => $to_label->id,
                'from_section_id' => $from_cost->section_id,
                'to_section_id' => $to_section->id,
                'prev_value' => $from_cost->value,
                'receive' => 0,
                'send' => $cost_of_each_section,
                'final' => $from_cost->value - $cost_of_each_section
            ]);
            Tashimlog::insert([
                'type' => 1,
                'label_id' => $to_label->id,
                'from_section_id' => $from_cost->section_id,
                'to_section_id' => $to_section->id,
                'prev_value' => $corresponding_cost->value,
                'receive' => $cost_of_each_section,
                'send' => 1,
                'final' => $corresponding_cost->value + $cost_of_each_section
            ]);
            
            $corresponding_cost->update([
                    'label_id' => $to_label->id,
                    'section_id' => $to_section->id,
                    'group_id' => $get_section_id,
                    'value' => $corresponding_cost->value + $cost_of_each_section
            ]);

            $from_cost_updated = Cost::where('id', $from_cost->id)->first();
            error_log($from_cost_updated);
        }
    }
    return Response::json(['done']);
});
