<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FormStatus;

class Form extends Model
{
    use SoftDeletes;

    protected $fillable = ['label','status'];

    protected $casts = [
        'status' => FormStatus::class,
    ];

    public function formFields()
    {
        return $this->hasMany(FormField::class);
    }
}
