<?php

namespace App\Enums;

enum RoleplayEnum: string
{
    case BACKEND = 'backend';
    case DEVOPS = 'devops';
    case FRONTEND = 'frontend';
    case FULLSTACK = 'fullstack';
    case LEGAL = 'legal';
    case MARKETING = 'marketing';
    case AIENGINEER = 'aiengineer';
    case STAKEHOLDER = 'stakeholder';

    public function label(): string
    {
        return match($this) {
            self::BACKEND => 'BackEnd',
            self::DEVOPS => 'DevOps',
            self::FRONTEND => 'FrontEnd',
            self::FULLSTACK => 'FullStack',
            self::LEGAL => 'Legal',
            self::MARKETING => 'Marketing',
            self::AIENGINEER => 'AI Engineer',
            self::STAKEHOLDER => 'Stakeholder',
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
