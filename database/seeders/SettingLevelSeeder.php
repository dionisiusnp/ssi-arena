<?php

namespace Database\Seeders;

use App\Enums\FieldTypeEnum;
use App\Enums\SettingGroupEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 1,
                'key' => 'level_1',
                'name' => 'Level 1',
                'description' => 'Nilai poin minimal untuk Level 1',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 2,
                'key' => 'level_2',
                'name' => 'Level 2',
                'description' => 'Nilai poin minimal untuk Level 2',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 3,
                'key' => 'level_3',
                'name' => 'Level 3',
                'description' => 'Nilai poin minimal untuk Level 3',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 4,
                'key' => 'level_4',
                'name' => 'Level 4',
                'description' => 'Nilai poin minimal untuk Level 4',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 5,
                'key' => 'level_5',
                'name' => 'Level 5',
                'description' => 'Nilai poin minimal untuk Level 5',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 6,
                'key' => 'level_6',
                'name' => 'Level 6',
                'description' => 'Nilai poin minimal untuk Level 6',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 7,
                'key' => 'level_7',
                'name' => 'Level 7',
                'description' => 'Nilai poin minimal untuk Level 7',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 8,
                'key' => 'level_8',
                'name' => 'Level 8',
                'description' => 'Nilai poin minimal untuk Level 8',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 9,
                'key' => 'level_9',
                'name' => 'Level 9',
                'description' => 'Nilai poin minimal untuk Level 9',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 10,
                'key' => 'level_10',
                'name' => 'Level 10',
                'description' => 'Nilai poin minimal untuk Level 10',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 11,
                'key' => 'level_11',
                'name' => 'Level 11',
                'description' => 'Nilai poin minimal untuk Level 11',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 12,
                'key' => 'level_12',
                'name' => 'Level 12',
                'description' => 'Nilai poin minimal untuk Level 12',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 13,
                'key' => 'level_13',
                'name' => 'Level 13',
                'description' => 'Nilai poin minimal untuk Level 13',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 14,
                'key' => 'level_14',
                'name' => 'Level 14',
                'description' => 'Nilai poin minimal untuk Level 14',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
            [
                'group' => SettingGroupEnum::LEVEL->value,
                'sequence' => 15,
                'key' => 'level_15',
                'name' => 'Level 15',
                'description' => 'Nilai poin minimal untuk Level 15',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '0',
                'current_value' => '0', // dari poin
            ],
        ];

        foreach ($settings as $data) {
            Setting::firstOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
