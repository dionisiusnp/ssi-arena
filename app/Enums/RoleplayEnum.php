<?php

namespace App\Enums;

enum RoleplayEnum: string
{
    case FULLSTACK = 'fullstack';
    case BACKEND = 'backend';
    case FRONTEND = 'frontend';
    case DEVOPS = 'devops';

    public function label(): string
    {
        return match($this) {
            self::FULLSTACK => 'Fullstack',
            self::BACKEND => 'Backend',
            self::FRONTEND => 'Frontend',
            self::DEVOPS => 'Devops',
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
