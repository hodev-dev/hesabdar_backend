<?php

namespace App\Exports;

use App\Models\Label;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LableExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function headings(): array
    // {
    //     return ["ردیف",'شرح هزینه' ,"کد", "کد گروه",'created_at','updated_at'];
    // }

    public function collection()
    {
        return Label::all();
    }
}
