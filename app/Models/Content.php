<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'type', 'data'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}