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
            // $code = (integer) $row['section_id'] + 100000;
            $code = (integer) $row['section_id'];
            $section = Section::where('code', $code)->first();
            $label = Label::where('group_code', (integer) $row['group'])
            ->where('code', (integer) $row['code'])
            ->first();

            // error_log($section->id);
            // error_log($section->name);
            // error_log($row['produce_value']);

            Cost::create([
                'label_id' => $label->id,
                'section_id' => $section->id,
                'group_id' => (integer) $row['group'],
                'prev_value' => (integer) $row['produce_value'],
                'change' => (integer) 0,
                'final' => (integer) $row['produce_value' ],
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 10;
    }
}
