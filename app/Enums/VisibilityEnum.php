<?php

namespace App\Enums;

enum VisibilityEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SHARED = 'shared';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draf',
            self::PUBLISHED => 'Member',
            self::SHARED => 'Umum',
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
