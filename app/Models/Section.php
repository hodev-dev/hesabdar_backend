<?php

namespace App\Models;

use App\Models\Cost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable =['name','group_id','users','produce','code','sharable','tahsimlable_id'];

    public function costs()
    {
        return $this->hasMany(Cost::class);
    }
}
