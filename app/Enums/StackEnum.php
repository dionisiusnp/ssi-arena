<?php

namespace App\Enums;

enum StackEnum: string
{
    case AI = 'ai';
    case FLUTTER = 'flutter';
    case GENERAL = 'general';
    case GO = 'go';
    case JAVA = 'java';
    case LARAVEL = 'laravel';
    case LINUX = 'linux';
    case MACOS = 'macos';
    case NEXT = 'next';
    case PYTHON = 'python';
    case WINDOWS = 'windows';

    public function label(): string
    {
        return match($this) {
            self::AI => 'AI',
            self::FLUTTER => 'Flutter',
            self::GENERAL => 'General',
            self::GO => 'Go',
            self::JAVA => 'Java',
            self::LARAVEL => 'Laravel',
            self::LINUX => 'Linux',
            self::MACOS => 'macOS',
            self::NEXT => 'Next',
            self::PYTHON => 'Python',
            self::WINDOWS => 'Windows',
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
