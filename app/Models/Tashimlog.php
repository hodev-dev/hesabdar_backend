<?php

namespace App\Models;

use App\Models\Label;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tashimlog extends Model
{
    use HasFactory;
    protected $fillable = ['type','label_id','from_section_id','to_section_id','prev_value','receive','send','final'];

    public function section()
    {
        return $this->hasOne(Section::class, 'id', 'from_section_id');
    }
    public function label()
    {
        return $this->hasOne(Label::class, 'id', 'label_id');
    }
}
