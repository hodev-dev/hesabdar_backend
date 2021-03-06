<?php

use App\Models\Cost;
use App\Models\Label;
use App\Models\Section;
use App\Models\Tashimlog;
use App\Models\Tashimorder;
use Illuminate\Http\Request;
use App\HelperClass\TashimClass;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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

Route::get('/test_sum_3', function (Request $request) {
    $a1 = Cost::where('group_id', 811)->where('label_id', 1)->sum('prev_value');
    $a2 = Cost::where('group_id', 811)->where('label_id', 2)->sum('prev_value');
    $a3 = Cost::where('group_id', 811)->where('label_id', 3)->sum('prev_value');
    $a4 = Cost::where('group_id', 811)->where('label_id', 4)->sum('prev_value');
    $a5 = Cost::where('group_id', 811)->where('label_id', 5)->sum('prev_value');
    $a6 = Cost::where('group_id', 811)->where('label_id', 6)->sum('prev_value');
    $a7 = Cost::where('group_id', 811)->where('label_id', 7)->sum('prev_value');
    $a12 = Cost::where('group_id', 811)->where('label_id', 12)->sum('prev_value');
    $a14 = Cost::where('group_id', 811)->where('label_id', 14)->sum('prev_value');
    $a15 = Cost::where('group_id', 811)->where('label_id', 15)->sum('prev_value');
    $a16 = Cost::where('group_id', 811)->where('label_id', 16)->sum('prev_value');
    return $a1 + $a2 + $a3 +$a4 +$a5+$a6+$a7+$a12+$a14+$a15+$a16;
    // return $a1;
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
    foreach ($from_sections->costs as $cost_index =>$from_cost) {
        $from_label_code = $from_cost->label->code;
        $from_label_id = $from_cost->label->id;
        $total_users = Section::where('sharable', 0)->get()->sum('users');
        $from_users = Section::where('id', $from_cost->section_id)->first()->users;
        $users = $total_users - $from_users;
        $to_sections = Section::where('id', '!=', $request->id)->where('sharable', 0)->get();
        foreach ($to_sections as $section_index => $to_section) {
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
                'change' => $new_from_cost->change - round($cost_for_each_section * $to_section->users),
                'final' =>  $new_from_cost->final - round($cost_for_each_section * $to_section->users)
            ]);
            $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                // 'change' => DB::raw("ROUND(change + ($cost_for_each_section * $to_section->users))"),
                // 'final' => DB::raw("ROUND(final + ($cost_for_each_section * $to_section->users))")
                'change' => $new_to_cost->change + round($cost_for_each_section * $to_section->users),
                'final' =>  $new_to_cost->final + round($cost_for_each_section * $to_section->users)
            ]);
            // $cost_index === count($from_sections->costs) - 1
            if ($section_index === count($to_sections) - 1) {
                error_log($new_from_cost->final);
                error_log($new_to_cost->final);
                $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                    // 'change' => DB::raw("ROUND(change + $cost_for_each_section * $to_section->users))"),
                    // 'final' => DB::raw("ROUND(final - ($cost_for_each_section * $to_section->users))")
                    'change' => $new_from_cost->change - $new_from_cost->final,
                    'final' =>  0
                ]);
                $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                    // 'change' => DB::raw("ROUND(change + ($cost_for_each_section * $to_section->users))"),
                    // 'final' => DB::raw("ROUND(final + ($cost_for_each_section * $to_section->users))")
                    'change' => $new_to_cost->change + $new_from_cost->final,
                    'final' =>  $new_to_cost->final + $new_from_cost->final
                ]);
            }
            
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
    // $sum_final_before = Cost::all()->sum('final');
    // $from_remain = Cost::where('section_id', $from_sections->id)->sum('final');
    // $from_remain_zero = Cost::where('section_id', $from_sections->id)->update(['final' => 0]);
    // $to_remain_final = Cost::where('section_id', 1)->where('label_id', 1)->first();
    // $to_remain = Cost::where('section_id', 1)->where('label_id', 1)->update(['final' => $to_remain_final->final + $from_remain,'change' => $to_remain_final->change + $from_remain]);
    // $sum_final_after = Cost::all()->sum('final');
    // error_log($sum_final_before);
    // error_log($sum_final_after);
    $from_sections->update([
        'sharable' => 1
    ]);
    return Response::json($from_sections);
});


Route::post('/tahsim_produce', function (Request $request) {
    $test_cost = 0;
    $final_test = 0;
    $from_sections = Section::where('id', $request->id)->with('costs.label')->first();
    $test_sections = Section::where('id', '!=', $request->id)->get();
    $total_produce = Section::where('sharable', 0)->get()->sum('produce');
    foreach ($from_sections->costs as $from_cost) {
        $from_label_code = $from_cost->label->code;
        $from_label_id = $from_cost->label->id;
        $from_users = Section::where('id', $from_cost->section_id)->first()->users;
        $to_sections = Section::where('id', '!=', $request->id)->where('sharable', 0)->get();
        foreach ($to_sections as $section_index => $to_section) {
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
            
            // $change_sum_from =  Cost::where('label_id', 73)->where('section_id', 9)->sum('change');
            // $change_sam_target =  Cost::where('section_id', '!=', 9)->orWhere('label_id', 28)->orWhere('label_id', 73)->orWhere('label_id', 120)->sum('change');
            $new_from_cost = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->first();
            // $cost_for_each_section = round($new_from_cost->final / $users);
            $cost_for_each_section = $from_cost->final / $total_produce;
            $new_to_cost = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->first();
            $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                // 'change' => DB::raw("ROUND(change + $cost_for_each_section * $to_section->users))"),
                // 'final' => DB::raw("ROUND(final - ($cost_for_each_section * $to_section->users))")
                'change' => $new_from_cost->change - round($cost_for_each_section * $to_section->produce),
                'final' =>  $new_from_cost->final - round($cost_for_each_section * $to_section->produce)
            ]);
            $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                // 'change' => DB::raw("ROUND(change + ($cost_for_each_section * $to_section->users))"),
                // 'final' => DB::raw("ROUND(final + ($cost_for_each_section * $to_section->users))")
                'change' => $new_to_cost->change + round($cost_for_each_section * $to_section->produce),
                'final' =>  $new_to_cost->final + round($cost_for_each_section * $to_section->produce)
            ]);
            if ($section_index === count($to_sections) - 1) {
                error_log($new_from_cost->final);
                $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                    // 'change' => DB::raw("ROUND(change + $cost_for_each_section * $to_section->users))"),
                    // 'final' => DB::raw("ROUND(final - ($cost_for_each_section * $to_section->users))")
                    'change' => $new_from_cost->change - $new_from_cost->final,
                    'final' =>  0
                ]);
                $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                    // 'change' => DB::raw("ROUND(change + ($cost_for_each_section * $to_section->users))"),
                    // 'final' => DB::raw("ROUND(final + ($cost_for_each_section * $to_section->users))")
                    'change' => $new_to_cost->change + $new_from_cost->final,
                    'final' =>  $new_to_cost->final + $new_from_cost->final
                ]);
            }
            if ($from_label_code === 5) {
                error_log($total_produce);
                error_log($to_section->produce);
                error_log($cost_for_each_section);
                error_log(round($cost_for_each_section * $to_section->produce));
                $test_cost = $test_cost + $cost_for_each_section * $to_section->produce;
                error_log($test_cost);
                error_log('---------------------------------------------------');
            }
        }
    }
    // $sum_final_before = Cost::all()->sum('final');
    // $from_remain = Cost::where('section_id', $from_sections->id)->sum('final');
    // $from_remain_zero = Cost::where('section_id', $from_sections->id)->update(['final' => 0]);
    // $to_remain_final = Cost::where('section_id', 1)->where('label_id', 1)->first();
    // $to_remain = Cost::where('section_id', 1)->where('label_id', 1)->update(['final' => $to_remain_final->final + $from_remain,'change' => $to_remain_final->fianl + $from_remain]);
    // $sum_final_after = Cost::all()->sum('final');
    // error_log($sum_final_before);
    // error_log($sum_final_after);
    $from_sections->update([
        'sharable' => 1
    ]);
    return Response::json($from_sections);
});

Route::get('/refresh', function () {
    $start  = Artisan::call("migrate:fresh --seed");
    return Artisan::output();
});

Route::post('/get_section_label', function (Request $request) {
    $group_cost = Cost::where('group_id', $request['group_id'])->with('label')->get();
    $prev_vlaue_wage_sum = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 3)
        ->orWhere('code', 4)->orWhere('code', 5)->orWhere('code', 6)->orWhere('code', 7)->orWhere('code', 14)
        ->orWhere('code', 15)->orWhere('code', 16)->orWhere('code', 17)->orWhere('code', 18)->orWhere('code', 20)
        ->orWhere('code', 21)->orWhere('code', 22)->orWhere('code', 23)->orWhere('code', 24)->orWhere('code', 25)
        ->orWhere('code', 26);
        // $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 5);
    })->sum('prev_value');

    $change_wage_sum = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 3)
        ->orWhere('code', 4)->orWhere('code', 5)->orWhere('code', 6)->orWhere('code', 7)->orWhere('code', 14)
        ->orWhere('code', 15)->orWhere('code', 16)->orWhere('code', 17)->orWhere('code', 18)->orWhere('code', 20)
        ->orWhere('code', 21)->orWhere('code', 22)->orWhere('code', 23)->orWhere('code', 24)->orWhere('code', 25)
        ->orWhere('code', 26);
    })->sum('change');

    $final_wage_sum = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 3)
        ->orWhere('code', 4)->orWhere('code', 5)->orWhere('code', 6)->orWhere('code', 7)->orWhere('code', 14)
        ->orWhere('code', 15)->orWhere('code', 16)->orWhere('code', 17)->orWhere('code', 18)->orWhere('code', 20)
        ->orWhere('code', 21)->orWhere('code', 22)->orWhere('code', 23)->orWhere('code', 24)->orWhere('code', 25)
        ->orWhere('code', 26);
    })->sum('final');


    $prev_value_bime = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 10)->orWhere('code', 11)->orWhere('code', 12)->orWhere('code', 13);
    })->sum('prev_value');

    $change_bime = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 10)->orWhere('code', 11)->orWhere('code', 12)->orWhere('code', 13);
    })->sum('change');

    $final_bime = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 10)->orWhere('code', 11)->orWhere('code', 12)->orWhere('code', 13);
    })->sum('final');

    $prev_value_sanavat = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 8)->orWhere('code', 9)->orWhere('code', 25);
    })->sum('prev_value');

    $change_sanavat = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 8)->orWhere('code', 9)->orWhere('code', 25);
    })->sum('change');

    $final_sanavat = Cost::where('group_id', $request['group_id'])->whereHas('label', function ($query) {
        $query->where('code', 8)->orWhere('code', 9)->orWhere('code', 25);
    })->sum('final');

    $group_cost_with_sum =  $group_cost->groupBy('label.code')->map(function ($query) {
        return (array) [
            'name' => $query->first()->label->name,
            'code' => $query->first()->label->code,
            'group_id' => $query->first()->group_id,
            'prev_sum' => $query->sum('prev_value'),
            'change_sum' => $query->sum('change'),
            'prev_change' => $query->sum('prev_value') + $query->sum('change'),
            'final_sum' => $query->sum('final')
        ];
    });
    $prev_sum_section = Cost::where('group_id', $request['group_id'])->sum('prev_value');
    $change_sum_section = Cost::where('group_id', $request['group_id'])->sum('change');
    $final_sum_section = Cost::where('group_id', $request['group_id'])->sum('final');
    $chouse_title = ($request['group_id'] == 811) ? 'گزارش ثانویه تسهیم بخش تولید'  : 'گزارش ثانویه تسهیم بخش اداری';
    return Response::json([
        'title' => $chouse_title,
        'group_cost' => $group_cost_with_sum->toArray(),
        'prev_value_sum' => $prev_sum_section,
        'change_sum_section' => $change_sum_section,
        'final_sum_section' => $final_sum_section,
        'prev_vlaue_wage_sum' => $prev_vlaue_wage_sum,
        'change_wage_sum' => $change_wage_sum,
        'final_wage_sum' => $final_wage_sum,
        'prev_value_bime' => $prev_value_bime,
        'change_bime' => $change_bime,
        'final_bime' => $final_bime,
        'prev_value_sanavat' => $prev_value_sanavat,
        'change_sanavat' => $change_sanavat,
        'final_sanavat' => $final_sanavat,
    ]);
});


Route::get('/tashim_all', function (Request $request) {
    $tashim_sections = Section::where('share_order', '!=', 0)->orderBy('share_order')->get();
    foreach ($tashim_sections as $section) {
        if ((int) $section->tahsimlable_id == 1) {
            TashimClass::withUsers($section->id);
        } else {
            TashimClass::withProduce($section->id);
        }
    }
    return $tashim_sections;
});
