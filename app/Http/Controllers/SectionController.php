<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SectionController extends Controller
{
    public function add_section(Request $request)
    {
        $name = $request['name'];
        $group = $request['group'];
        $workers_count = $request['workers'];
        $produce = $request['produce'];
        $save = Section::create([
            'name' => $name,
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
        $group = $request['group'];
        $users = $request['workers'];
        $produce = $request['produce'];

        $selected_section = Section::where('id', $request['id'])
        ->update(
            [
                'name'=> $name,
                'group_id' => $group,
                'users' => $users,
                'produce' => $produce
            ]
        );
        return Response::json($selected_section);
    }
}
