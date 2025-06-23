<?php

namespace App\Enums;

enum QuestEnum: string
{
    case CLAIMED = 'claimed';
    case TESTING = 'testing';
    case PENDING = 'pending';
    case CLEAR = 'clear';

    public function label(): string
    {
        return match($this) {
            self::CLAIMED => 'Claimed',
            self::TESTING => 'Testing',
            self::PENDING => 'Pending',
            self::CLEAR => 'Clear',
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
