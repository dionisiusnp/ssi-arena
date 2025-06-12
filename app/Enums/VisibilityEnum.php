<?php

namespace App\Enums;

enum VisibilityEnum: string
{
    case SHARED = 'shared';
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match($this) {
            self::SHARED => 'Shared',
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
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
