<?php

namespace Database\Seeders;

use App\Enums\FieldTypeEnum;
use App\Enums\SettingGroupEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingPerkCustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'group' => SettingGroupEnum::PERKCUSTOM->value,
                'sequence' => 1,
                'key' => 'create_clan',
                'name' => 'Membuat Clan',
                'description' => 'Level minimal untuk membuat clan.',
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => '1',
                'current_value' => '10',
            ],
        ];

        foreach ($settings as $data) {
            Setting::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
