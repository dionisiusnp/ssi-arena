<?php

namespace App\Enums;

enum SettingGroupEnum: string
{
    case LEVEL = 'level';
    case PERKQUESTLEVEL = 'perk_quest_level';
    case PERKCUSTOM = 'perk_custom';
    
    public function label(): string
    {
        return match($this) {
            self::LEVEL => 'Level',
            self::PERKQUESTLEVEL => 'Perk Quest Level',
            self::PERKCUSTOM => 'Perk Custom',
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
