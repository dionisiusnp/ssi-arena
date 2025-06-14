<?php

namespace App\Enums;

enum LessonContentEnum: string
{
    case EDITOR = 'editor';
    case LINK = 'link';
    case TERMINAL = 'terminal';
    case TEXT = 'text';

    public function label(): string
    {
        return match($this) {
            self::EDITOR => 'Editor',
            self::LINK => 'Link',
            self::TERMINAL => 'Terminal',
            self::TEXT => 'Text',
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
