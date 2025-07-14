<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\FormFieldType;

class FormField extends Model
{
    protected $fillable = ['form_id','name','type','options'];

    protected $casts = [
        'type' => FormFieldType::class,
        'options' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
