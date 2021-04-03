<?php

namespace App\Imports;

use Throwable;
use App\Models\Cost;
use App\Models\Label;
use App\Models\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CostImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    public $err = [];
    use SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row_index => $row) {
            // error_log($row['produce_value']);
            // error_log($row['group']);
            // error_log((integer) $row['code']);
            // error_log((integer) $row['group']);
            // error_log($section->id);
            // error_log($section->name);
            error_log($row['produce_value']);
            error_log((integer)$row['produce_value']);
            
            $code = (integer) $row['section_id'];
            $section = Section::where('code', $code)->first();
            $label = Label::where('group_code', (integer) $row['group'])
                ->where('code', (integer) $row['code'])
                ->first();
            if ($row['group'] == '' || $row['produce_value'] == '') {
                $genretaed_error = $this->generateError('خطای مقدار خالی', $row, $row_index);
                array_push($this->err, $genretaed_error);
            } elseif (is_null($section)) {
                $genretaed_error = $this->generateError('کد مرکز هزینه نا معتبر', $row, $row_index);
                array_push($this->err, $genretaed_error);
            } elseif (is_null($label)) {
                $genretaed_error = $this->generateError('شرح هزینه نا معتبر', $row, $row_index);
                array_push($this->err, $genretaed_error);
            } elseif (!is_null($label) && !is_null($section)) {
                Cost::create([
                        'label_id' => $label->id,
                        'section_id' => $section->id,
                        'group_id' => (integer) $row['group'],
                        'prev_value' => (integer) $row['produce_value'],
                        'change' => (integer) 0,
                        'final' =>  $row['produce_value' ],
                    ]);
            } else {
                $genretaed_error = $this->generateError('خطای عمومی', $row, $row_index);
                array_push($this->err, $genretaed_error);
            }

            $genretaed_error =  null;
            $section = null;
            $label = null;
            $code = null;
        }
    }

    public function generateError($type, $row, $index)
    {
        return [
            'name' => $type,
            'index' => $index + 2,
            'label' => $row['label'],
            'group' => $row['group'],
            'code' => $row['code'],
            'id' => $row['section_id'],
            'value' => $row['produce_value'],
        ];
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function batchSize(): int
    {
        return 10;
    }

    public function onError(Throwable $e)
    {
    }
}
