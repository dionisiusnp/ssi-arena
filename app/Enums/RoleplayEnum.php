<?php

namespace App\Enums;

enum RoleplayEnum: string
{
    case AIENGINEER = 'aiengineer';
    case BACKEND = 'backend';
    case DEVOPS = 'devops';
    case FRONTEND = 'frontend';
    case FULLSTACK = 'fullstack';
    case LEGAL = 'legal';
    case MARKETING = 'marketing';
    case STAKEHOLDER = 'stakeholder';

    public function label(): string
    {
        return match($this) {
            self::AIENGINEER => 'AI Engineer',
            self::BACKEND => 'BackEnd',
            self::DEVOPS => 'DevOps',
            self::FRONTEND => 'FrontEnd',
            self::FULLSTACK => 'FullStack',
            self::LEGAL => 'Legal',
            self::MARKETING => 'Marketing',
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
