<?php

namespace App\Enums;

enum SettingGroupEnum: string
{
    case LEVEL = 'level';
    case RANKED = 'ranked';
    case PERKQUESTLEVEL = 'perk_quest_level';
    case PERKCUSTOM = 'perk_custom';
    case GENERAL = 'general';
    
    public function label(): string
    {
        return match($this) {
            self::LEVEL => 'Level',
            self::RANKED => 'Ranked',
            self::PERKQUESTLEVEL => 'Perk Quest Level',
            self::PERKCUSTOM => 'Perk Custom',
            self::GENERAL => 'General',
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
