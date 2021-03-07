<?php

namespace App\Models;

use App\Models\Cost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Label extends Model
{
    use HasFactory;
    protected $fillable = ['name','code','group_code'];

    public function costs()
    {
        return $this->hasMany(Cost::class);
    }
}
