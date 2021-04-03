<?php
namespace App\HelperClass;

use App\Models\Cost;
use App\Models\Label;
use App\Models\Section;

class TashimClass
{
    public static function withUsers($ID)
    {
        $test_cost = 0;
        $final_test = 0;
        $from_sections = Section::where('id', $ID)->with('costs.label')->first();
        $test_sections = Section::where('id', '!=', $ID)->get();
        foreach ($from_sections->costs as $cost_index =>$from_cost) {
            $from_label_code = $from_cost->label->code;
            $from_label_id = $from_cost->label->id;
            $total_users = Section::where('sharable', 0)->get()->sum('users');
            $from_users = Section::where('id', $from_cost->section_id)->first()->users;
            $users = $total_users - $from_users;
            $to_sections = Section::where('id', '!=', $ID)->where('sharable', 0)->get();
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
                $cost_for_each_section = round($from_cost->final / $users);
                $new_to_cost = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->first();
                $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                  'change' => $new_from_cost->change - round($cost_for_each_section * $to_section->users),
                  'final' =>  $new_from_cost->final - round($cost_for_each_section * $to_section->users)
              ]);
                $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                  'change' => $new_to_cost->change + round($cost_for_each_section * $to_section->users),
                  'final' =>  $new_to_cost->final + round($cost_for_each_section * $to_section->users)
              ]);
                if ($section_index === count($to_sections) - 1) {
                    error_log($new_from_cost->final);
                    error_log($new_to_cost->final);
                    $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                      'change' => $new_from_cost->change - $new_from_cost->final,
                      'final' =>  0
                  ]);
                    $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                      'change' => $new_to_cost->change + $new_from_cost->final,
                      'final' =>  $new_to_cost->final + $new_from_cost->final
                  ]);
                }
                error_log($total_users);
                error_log($to_section->users);
                error_log(round($cost_for_each_section * $to_section->users));
                error_log('---------------------------------------------------');
            }
        }
        $from_sections->update([
          'sharable' => 1
      ]);
        return $from_sections;
    }

    public static function withProduce($ID)
    {
        $test_cost = 0;
        $final_test = 0;
        $from_sections = Section::where('id', $ID)->with('costs.label')->first();
        $test_sections = Section::where('id', '!=', $ID)->get();
        $total_produce = Section::where('sharable', 0)->get()->sum('produce');
        foreach ($from_sections->costs as $from_cost) {
            $from_label_code = $from_cost->label->code;
            $from_label_id = $from_cost->label->id;
            $from_users = Section::where('id', $from_cost->section_id)->first()->users;
            $to_sections = Section::where('id', '!=', $ID)->where('sharable', 0)->get();
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
              
                $new_from_cost = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->first();
                $cost_for_each_section = $from_cost->final / $total_produce;
                $new_to_cost = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->first();
                $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                  'change' => $new_from_cost->change - round($cost_for_each_section * $to_section->produce),
                  'final' =>  $new_from_cost->final - round($cost_for_each_section * $to_section->produce)
              ]);
                $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
                  'change' => $new_to_cost->change + round($cost_for_each_section * $to_section->produce),
                  'final' =>  $new_to_cost->final + round($cost_for_each_section * $to_section->produce)
              ]);
                if ($section_index === count($to_sections) - 1) {
                    error_log($new_from_cost->final);
                    $from_cost_update = Cost::where('section_id', $from_cost->section_id)->where('label_id', $from_label_id)->update([
                      'change' => $new_from_cost->change - $new_from_cost->final,
                      'final' =>  0
                  ]);
                    $to_costs_update = Cost::where('section_id', $to_section->id)->where('label_id', $to_label_id)->update([
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
        $from_sections->update([
          'sharable' => 1
      ]);
        return $from_sections;
    }
}
