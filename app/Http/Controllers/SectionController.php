<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SectionController extends Controller
{
    public function add_section(Request $request)
    {
        $name = $request['name'];
        $code = $request['code'];
        $group = $request['group'];
        $workers_count = $request['workers'];
        $produce = $request['produce'];
        $sharable = $request['sharable'];
        $tahsimLableSelect = $request['tahsimLableSelect'];

        $save = Section::create([
            'name' => $name,
            'code' => $code,
            'sharable' => $sharable,
            'tahsimlable_id' => $tahsimLableSelect,
            'group_id' => $group,
            'users' => $workers_count,
            'produce' => $produce
        ]);
        if ($save->exists) {
            return Response::json(['messege' => 'done']);
        } else {
            return Response::json(['messege' => 'fail']);
        }
    }

    public function get_section()
    {
        return Section::all();
    }

    public function remove_section(Request $request)
    {
        $row = Section::where('id', $request['id'])->delete();
        return Response::json(['done']);
    }

    public function update_section(Request $request)
    {
        $id = $request['id'];
        $name = $request['name'];
        $code = $request['code'];
        $group = $request['group'];
        $workers_count = $request['workers'];
        $produce = $request['produce'];
        $sharable = $request['sharable'];
        $tahsimLableSelect = $request['tahsimLableSelect'];

        $selected_section = Section::where('id', $request['id'])
        ->update(
            [
                'name' => $name,
                'code' => $code,
                'sharable' => $sharable,
                'tahsimlable_id' => $tahsimLableSelect,
                'group_id' => $group,
                'users' => $workers_count,
                'produce' => $produce
            ]
        );
        return Response::json($selected_section);
    }

    public function get_section_cost()
    {
        return Section::with('costs.label')->get();
    }
    public function get_section_cost_with_id(Request $request)
    {
        $id = $request['id'];
        $section_with_costs = Section::where('id', $id)->with('costs.label')->orderBy('code', 'ASC')->first();
        $sum = Cost::where('section_id', $id)->sum('prev_value');
        $final_sum = Cost::where('section_id', $id)->sum('final');
        $final_sum = Cost::where('section_id', $id)->sum('final');
        $prev_vlaue_wage_sum = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 3)
            ->orWhere('code', 4)->orWhere('code', 5)->orWhere('code', 6)->orWhere('code', 7)->orWhere('code', 14)
            ->orWhere('code', 15)->orWhere('code', 16)->orWhere('code', 17)->orWhere('code', 18)->orWhere('code', 20)
            ->orWhere('code', 21)->orWhere('code', 22)->orWhere('code', 23)->orWhere('code', 24)->orWhere('code', 25)
            ->orWhere('code', 26);
            // $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 5);
        })->sum('prev_value');

        $final_wage_sum = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 1)->orWhere('code', 2)->orWhere('code', 3)
            ->orWhere('code', 4)->orWhere('code', 5)->orWhere('code', 6)->orWhere('code', 7)->orWhere('code', 14)
            ->orWhere('code', 15)->orWhere('code', 16)->orWhere('code', 17)->orWhere('code', 18)->orWhere('code', 20)
            ->orWhere('code', 21)->orWhere('code', 22)->orWhere('code', 23)->orWhere('code', 24)->orWhere('code', 25)
            ->orWhere('code', 26);
        })->sum('final');

        $prev_value_bime = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 10)->orWhere('code', 11)->orWhere('code', 12)->orWhere('code', 13);
        })->sum('prev_value');

        $final_bime = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 10)->orWhere('code', 11)->orWhere('code', 12)->orWhere('code', 13);
        })->sum('final');

        $prev_value_sanavat = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 8)->orWhere('code', 9)->orWhere('code', 25);
        })->sum('prev_value');
        
        $final_sanavat = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 8)->orWhere('code', 9)->orWhere('code', 25);
        })->sum('final');

        $prev_value_tamir = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 34)->orWhere('code', 35)->orWhere('code', 36)
            ->orWhere('code', 69)->orWhere('code', 70)->orWhere('code', 71)->orWhere('code', 72)->orWhere('code', 73)
            ->orWhere('code', 74)->orWhere('code', 75)->orWhere('code', 77)->orWhere('code', 78)->orWhere('code', 79)
            ->orWhere('code', 80);
        })->sum('prev_value');
        
        $final_tamir = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->where('code', 34)->orWhere('code', 35)->orWhere('code', 36)
            ->orWhere('code', 69)->orWhere('code', 70)->orWhere('code', 71)->orWhere('code', 72)->orWhere('code', 73)
            ->orWhere('code', 74)->orWhere('code', 75)->orWhere('code', 77)->orWhere('code', 78)->orWhere('code', 79)
            ->orWhere('code', 80);
        })->sum('final');

        $prev_vlaue_estelak = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->whereBetween('code', [90,97]);
        })->sum('prev_value');

        $final_estelak = Cost::where('section_id', $id)->whereHas('label', function ($query) {
            $query->whereBetween('code', [90,97]);
        })->sum('final');

        return Response::json(
            [
            'sections_with_costs' => $section_with_costs,
            'sum' => $sum,
            'final_sum' => $final_sum,
            'prev_vlaue_wage_sum' => $prev_vlaue_wage_sum,
            'final_wage_sum' => $final_wage_sum,
            'prev_value_bime' => $prev_value_bime,
            'final_bime' => $final_bime,
            'prev_value_sanavat' => $prev_value_sanavat,
            'final_sanavat' => $final_sanavat,
            'prev_value_tamir' => $prev_value_tamir,
            'final_tamir' => $final_tamir,
            'prev_vlaue_estelak' => $prev_vlaue_estelak,
            'final_estelak' => $final_estelak
         ]
        );
    }
}
