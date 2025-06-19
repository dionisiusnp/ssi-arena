<?php

namespace App\Enums;

enum StackEnum: string
{
    case FLUTTER = 'flutter';
    case JAVA = 'java';
    case GO = 'go';
    case LARAVEL = 'laravel';
    case LINUX = 'linux';
    case MACOS = 'macos';
    case PYTHON = 'python';
    case WINDOWS = 'windows';

    public function label(): string
    {
        return match($this) {
            self::FLUTTER => 'Flutter',
            self::JAVA => 'Java',
            self::GO => 'Go',
            self::LARAVEL => 'Laravel',
            self::LINUX => 'Linux',
            self::MACOS => 'macOS',
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
