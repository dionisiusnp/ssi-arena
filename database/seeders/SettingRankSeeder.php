<?php

namespace Database\Seeders;

use App\Enums\FieldTypeEnum;
use App\Enums\SettingGroupEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 1,
                'key' => 'rank_1',
                'name' => 'Rank 1',
                'description' => 'Nilai poin minimal untuk Rank 1',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 2,
                'key' => 'rank_2',
                'name' => 'Rank 2',
                'description' => 'Nilai poin minimal untuk Rank 2',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 3,
                'key' => 'rank_3',
                'name' => 'Rank 3',
                'description' => 'Nilai poin minimal untuk Rank 3',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 4,
                'key' => 'rank_4',
                'name' => 'Rank 4',
                'description' => 'Nilai poin minimal untuk Rank 4',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 5,
                'key' => 'rank_5',
                'name' => 'Rank 5',
                'description' => 'Nilai poin minimal untuk Rank 5',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 6,
                'key' => 'rank_6',
                'name' => 'Rank 6',
                'description' => 'Nilai poin minimal untuk Rank 6',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 7,
                'key' => 'rank_7',
                'name' => 'Rank 7',
                'description' => 'Nilai poin minimal untuk Rank 7',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 8,
                'key' => 'rank_8',
                'name' => 'Rank 8',
                'description' => 'Nilai poin minimal untuk Rank 8',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 9,
                'key' => 'rank_9',
                'name' => 'Rank 9',
                'description' => 'Nilai poin minimal untuk Rank 9',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
            ],
            [
                'group' => SettingGroupEnum::RANKED->value,
                'sequence' => 10,
                'key' => 'rank_10',
                'name' => 'Rank 10',
                'description' => 'Nilai poin minimal untuk Rank 10',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari poin
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
