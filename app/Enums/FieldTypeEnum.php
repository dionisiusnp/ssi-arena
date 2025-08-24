<?php

namespace App\Enums;

enum FieldTypeEnum: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case SELECT = 'select';
    case RADIO = 'radio';
    case FILE = 'file';
    case DATE = 'date';
    public function label(): string
    {
        return match($this) {
            self::TEXT => 'Text',
            self::SELECT => 'Select',
            self::RADIO => 'Radio',
            self::FILE => 'File',
            self::DATE => 'Date',
            self::NUMBER => 'Number',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }
}
