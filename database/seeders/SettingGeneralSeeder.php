<?php

namespace Database\Seeders;

use App\Enums\FieldTypeEnum;
use App\Enums\SettingGroupEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'group' => SettingGroupEnum::GENERAL->value,
                'sequence' => 1,
                'key' => 'whatsapp_admin',
                'name' => 'WhatsApp Admin',
                'description' => 'Kontak yang bisa dihubungi oleh member untuk aduan.',
                'column_type' => FieldTypeEnum::TEXT->value,
                'default_value' => '+6285183009828',
                'current_value' => null,
            ],
            [
                'group' => SettingGroupEnum::GENERAL->value,
                'sequence' => 2,
                'key' => 'winner_counter',
                'name' => 'Jumlah Pemenang',
                'description' => 'Menentukan jumlah pemenang dari daftar peringkat.',
                'column_type' => FieldTypeEnum::NUMBER->value,
                'default_value' => 3,
                'current_value' => 0,
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
