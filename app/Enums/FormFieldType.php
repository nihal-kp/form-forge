<?php

namespace App\Enums;

enum FormFieldType: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';

    public function label(): string
    {
        return match($this) {
            self::TEXT => 'Text',
            self::NUMBER => 'Number',
            self::TEXTAREA => 'Textarea',
            self::SELECT => 'Select',
        };
    }
}