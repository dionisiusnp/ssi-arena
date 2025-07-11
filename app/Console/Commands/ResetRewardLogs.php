<?php

namespace App\Console\Commands;

use App\Enums\QuestEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetRewardLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:reward-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate reward_logs table and set all claimed activity statuses to "testing".';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table('reward_logs')->delete();
            $this->info('Table reward_logs has been cleared.');

            DB::statement('ALTER TABLE reward_logs AUTO_INCREMENT = 1');
            $this->info('Auto increment reward_logs has been reset.');

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::table('activities')
                ->where('status', QuestEnum::PLUS->value)
                ->update(['status' => QuestEnum::TESTING->value]);
            $this->info('All plus activities status have been set to "testing".');

        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
