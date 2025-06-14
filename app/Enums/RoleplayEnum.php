<?php

namespace App\Enums;

enum RoleplayEnum: string
{
    case FULLSTACK = 'fullstack';
    case BACKEND = 'backend';
    case FRONTEND = 'frontend';
    case DEVOPS = 'devops';
    case MOBILE = 'mobile';

    public function label(): string
    {
        return match($this) {
            self::FULLSTACK => 'FullStack',
            self::BACKEND => 'BackEnd',
            self::FRONTEND => 'FrontEnd',
            self::DEVOPS => 'DevOps',
            self::MOBILE => 'Mobile',
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
