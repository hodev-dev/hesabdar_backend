<?php

use App\Models\Cost;
use App\Models\Label;
use App\Models\Section;
use App\Models\Tashimlog;
use App\Models\Tashimorder;
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
Route::get('/test_sum', function (Request $request) {
    $sum_prev=  Cost::all()->sum('prev_value');
    $sum_final = Cost::all()->sum('final');
    return Response::json(['sum_prev' => $sum_prev,'sum_final' => $sum_final]);
});

Route::get('/test_sum_2', function (Request $request) {
    // $change_before =  Cost::where('label_id', 73)->where('section_id', 9)->sum('change');
    $change_after =  Cost::orWhere('label_id', 28)->orWhere('label_id', 73)->orWhere('label_id', 120)->where('section_id', '!==', 9)->sum('change');
    return Response::json(['change_before' => $change_before,'change_after' => $change_after]);
});

// tashim
Route::post('/get_section_tashim', function (Request $request) {
    return Tashimlog::where('to_section_id', $request->id)->where('type', 1)->with('section', 'label')->latest('label_id')->orderBy('final', 'ASC')->get();
    // return Tashimlog::where('to_section_id', $request->id)->where('type', 1)->with('section', 'label')->latest('label_id')->orderBy('prev_value', 'DESC')->get()->unique('label_id');
    // return DB::table('tashimlogs')->distinct('label_id')->where('to_section_id', $request->id)->where('type', 1)->with('section', 'label')->latest('label_id')->get();
});

Route::get('/test_1', function (Request $request) {
    return Tashimlog::where('to_section_id', 1)->where('type', 1)->with('section', 'label')->latest('label_id')->orderBy('prev_value', 'DESC')->get()->unique('label_id');
});



Route::get('/get_tashim_log', [TashimlogController::class,'get_tashim_log']);

Route::post('/tahsim', function (Request $request) {
    $test_cost = 0;
    $final_test = 0;
    $from_sections = Section::where('id', $request->id)->with('costs.label')->first();
    $test_sections = Section::where('id', '!=', $request->id)->get();
    foreach ($from_sections->costs as $from_cost) {
        $from_label_code = $from_cost->label->code;
        $from_label_id = $from_cost->label->id;
        $total_users = Section::where('sharable', 0)->get()->sum('users');
        $from_users = Section::where('id', $from_cost->section_id)->first()->users;
        $users = $total_users - $from_users;
        $to_sections = Section::where('id', '!=', $request->id)->where('sharable', 0)->get();
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
                'prev_value' => 0,
                'change' => 0,
                'final' => 0
            ]);
           

            $from_group_code = 0;
            if ($from_sections->group_id === 1) {
                $from_group_code = 811 ;
            } elseif ($to_section->group_id === 2) {
                $from_group_code = 812 ;
            } else {
                $from_group_code = 813 ;
            }
            
            $change_sum_from =  Cost::where('label_id', 73)->where('section_id', 9)->sum('change');
            $change_sam_target =  Cost::where('section_id', '!=', 9)->orWhere('label_id', 28)->orWhere('label_id', 73)->orWhere('label_id', 120)->sum('change');
            $new_from_cost = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->first();
            // $cost_for_each_section = round($new_from_cost->final / $users);
            $cost_for_each_section = round($from_cost->final / $users);
            $new_to_cost = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->first();
            $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                // 'change' => DB::raw("ROUND(change + $cost_for_each_section * $to_section->users))"),
                // 'final' => DB::raw("ROUND(final - ($cost_for_each_section * $to_section->users))")
                'change' => $new_from_cost->change + round($cost_for_each_section * $to_section->users),
                'final' =>  $new_from_cost->final - round($cost_for_each_section * $to_section->users)
            ]);
            $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                // 'change' => DB::raw("ROUND(change + ($cost_for_each_section * $to_section->users))"),
                // 'final' => DB::raw("ROUND(final + ($cost_for_each_section * $to_section->users))")
                'change' => $new_to_cost->change + round($cost_for_each_section * $to_section->users),
                'final' =>  $new_to_cost->final + round($cost_for_each_section * $to_section->users)
            ]);
            // if ($from_label_code === 72) {
               
            error_log($total_users);
            error_log($to_section->users);
            error_log(round($cost_for_each_section * $to_section->users));
            error_log('---------------------------------------------------');
            // Tashimlog::create([
            //     'type' => 0,
            //     'label_id' => $from_label_id,
            //     'from_section_id' => $from_sections->id,
            //     'to_section_id' => $to_section->id,
            //     'prev_value' => $new_from_cost->prev_value,
            //     'receive' => 0,
            //     'send' => round($cost_for_each_section * $to_section->users),
            //     'final' => $new_from_cost->prev_value - round($cost_for_each_section * $to_section->users)
            // ]);
            // Tashimlog::create([
            //     'type' => 1,
            //     'label_id' => $from_label_id,
            //     'from_section_id' => $from_sections->id,
            //     'to_section_id' => $to_section->id,
            //     'prev_value' => $to_costs->prev_value,
            //     'receive' => round($cost_for_each_section * $to_section->users),
            //     'send' => 0,
            //     'final' => $to_costs->prev_value + round($cost_for_each_section * $to_section->users)
            // ]);
        }
    }
    $from_sections->update([
        'sharable' => 1
    ]);
    return Response::json($to_label->id);
});
