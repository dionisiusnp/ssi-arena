<?php

namespace App\Console\Commands;

use App\Enums\QuestEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetRewardLogs extends Command
{
    protected $signature = 'reset:reward-logs {--user_ids=}';
    protected $description = 'Truncate reward_logs table and set all plus-minus activity statuses to testing. Optionally reset only certain users.';

    public function handle()
    {
        $userIdsOption = $this->option('user_ids');
        $userIds = $userIdsOption
            ? array_map('intval', explode(',', $userIdsOption))
            : null;

        // Konfirmasi jika reset semua user
        if (!$userIds) {
            $confirm = false;

            if ($this->input->isInteractive()) {
                // Mode interaktif normal
                $confirm = $this->confirm('⚠️ Tidak ada user_ids diberikan. Ini akan mereset SEMUA user. Lanjutkan?');
            } else {
                // Fallback ke readline jika Artisan tidak interaktif
                $answer = readline("⚠️ Tidak ada user_ids diberikan. Ini akan mereset SEMUA user. Lanjutkan? (yes/no): ");
                $confirm = strtolower(trim($answer)) === 'yes';
            }

            if (!$confirm) {
                $this->info('Dibatalkan oleh pengguna.');
                return self::SUCCESS;
            }
        }

        try {
            DB::table('reward_logs')->truncate();
            $this->info('Table reward_logs has been cleared.');

            DB::statement('ALTER TABLE reward_logs AUTO_INCREMENT = 1');
            $this->info('Auto increment reward_logs has been reset.');

            $activitiesQuery = DB::table('activities')
                ->whereIn('status', [QuestEnum::PLUS->value, QuestEnum::MINUS->value]);

            if ($userIds) {
                $activitiesQuery->whereIn('claimed_by', $userIds);
                $this->info('Only resetting activities for users: ' . implode(', ', $userIds));
            } else {
                $this->info('Resetting activities for all users.');
            }

            $activitiesQuery->update(['status' => QuestEnum::TESTING->value]);
            $this->info('All plus-minus activities status have been set to testing.');

            $usersQuery = DB::table('users')->where('is_active', true);

            if ($userIds) {
                $usersQuery->whereIn('id', $userIds);
            }

            $usersQuery->update([
                'current_level' => 1,
                'current_point' => 0,
                'season_level' => 0,
                'season_point' => 0,
            ]);

            $this->info('User level and points have been reset.');
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
            return self::FAILURE;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        return self::SUCCESS;
    }
}
