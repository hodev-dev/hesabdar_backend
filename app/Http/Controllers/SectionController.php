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
        $section_with_costs = Section::where('id', $id)->with('costs.label')->first();
        $sum = Cost::where('section_id', $id)->sum('value');
        return Response::json(['sections_with_costs' => $section_with_costs,'sum' => $sum ]);
    }
}
