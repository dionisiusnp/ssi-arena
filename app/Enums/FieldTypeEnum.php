<?php

namespace App\Enums;

enum FieldTypeEnum: string
{
    case STRING = 'string';
    case TEXT = 'text';
    case INTEGER = 'integer';
    case SELECT = 'select';
    case RADIO = 'radio';
    case FILE = 'file';
    case DATE = 'date';

    public function label(): string
    {
        return match($this) {
            self::STRING => 'String',
            self::TEXT => 'Text',
            self::INTEGER => 'Integer',
            self::SELECT => 'Select',
            self::RADIO => 'Radio',
            self::FILE => 'File',
            self::DATE => 'Date',
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
