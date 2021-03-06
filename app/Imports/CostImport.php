<?php

namespace App\Imports;

use App\Models\Cost;
use App\Models\Label;
use App\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CostImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // error_log($row['produce_value']);
            // error_log($row['group']);
            // error_log((integer) $row['code']);
            // error_log((integer) $row['group']);
            $code = (integer) $row['section_id'] + 100000;
            $section = Section::where('code', $code)->firstOrFail();
            $label = Label::where('group_code', (integer) $row['group'])
            ->where('code', (integer) $row['code'])
            ->firstOrFail();
            Cost::create([
                'label_id' => $label->id,
                'section_id' => $section->id,
                'value' => (integer) $row['produce_value']
            ]);
        }
    }
}