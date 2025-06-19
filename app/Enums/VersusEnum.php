<?php

namespace App\Enums;

enum VersusEnum: string
{
    case PVE = 'pve';
    case PVP = 'pvp';
    
    public function label(): string
    {
        return match($this) {
            self::PVE => 'PvE',
            self::PVP => 'PvP',
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
