<?php

namespace App\Models;

use App\Models\Cost;
use App\Models\Label;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cost extends Model
{
    use HasFactory;
    protected $fillable = ['label_id','section_id','value','group_id'];
    public function label()
    {
        return $this->belongsTo(Label::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
