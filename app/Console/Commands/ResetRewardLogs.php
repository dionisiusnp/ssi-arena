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
    protected $description = 'Truncate reward_logs table and set all plus-minus activity statuses to testing.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::table('reward_logs')->truncate();
            $this->info('Table reward_logs has been cleared.');

            DB::statement('ALTER TABLE reward_logs AUTO_INCREMENT = 1');
            $this->info('Auto increment reward_logs has been reset.');

            DB::table('activities')
                ->whereIn('status',[QuestEnum::PLUS->value, QuestEnum::MINUS->value])
                ->update(['status' => QuestEnum::TESTING->value]);
            $this->info('All plus-minus activities status have been set to testing.');

            DB::table('users')
                ->where('is_active', true)
                ->update([
                'current_level' => 1,
                'current_point' => 0,
                'season_level' => 0,
                'season_point' => 0,
            ]);
            $this->info('Reset level and point of all users.');
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
            return self::FAILURE;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        return self::SUCCESS;
    }
}
