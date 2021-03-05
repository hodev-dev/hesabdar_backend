<?php

namespace App\Imports;

use App\Models\Label;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LableImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $group_code = substr($row['code'], 0, 3);
            $code = substr($row['code'], 3, 5);
            error_log($group_code);
            error_log($code);
            Label::create([
                'group_code' => $group_code,
                'code' => $code,
                'name' => $row['name']
            ]);
        }
    }
}
