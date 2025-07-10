<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateSettingByGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:update-column {group : Nama group setting} {column : Nama kolom yang ingin diubah} {value : Nilai baru untuk kolom tersebut}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ubah nilai data pada kolom setting berdasarkan grup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $group = $this->argument('group');
        $column = $this->argument('column');
        $value = $this->argument('value');

        if (!Schema::hasColumn('settings', $column)) {
            $this->error("Kolom '{$column}' tidak ditemukan di tabel settings.");
            return 1;
        }

        $affected = DB::table('settings')
            ->where('group', $group)
            ->update([$column => $value]);

        $this->info("{$affected} baris berhasil diperbarui. Kolom '{$column}' di group '{$group}' sekarang bernilai '{$value}'.");
        return 0;
    }
}
