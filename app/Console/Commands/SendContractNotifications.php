<?php

namespace App\Console\Commands;

use App\Mail\ContractStatusChangedMail;
use App\Models\Contract;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendContractNotifications extends Command
{
    protected $signature = 'contracts:notify';

    protected $description = 'Send contract expiration reminder notifications';

    public function handle()
    {
        $settings = NotificationSetting::with('user')
            ->whereHas('user', function ($query) {
                $query->whereIn('role_id', [
                    User::ROLE_ACCOUNT_MANAGER,
                    User::ROLE_SUPPORT_INPUTTER,
                ]);
            })
            ->get();

        $sentCount = 0;

        foreach ($settings as $setting) {
            if (!$setting->user || !$setting->manager_email) {
                continue;
            }

            $sentCount += $this->sendForSetting($setting);
        }

        $this->info('Contract reminders sent: ' . $sentCount);

        return Command::SUCCESS;
    }

    private function sendForSetting(NotificationSetting $setting): int
    {
        $user = $setting->user;
        $now = now();

        $shouldSendDaily = $now->format('H:i') === $setting->daily_schedule;
        $shouldSendWeekly = $this->shouldSendWeekly($setting->weekly_schedule);

        if (!$shouldSendDaily && !$shouldSendWeekly) {
            return 0;
        }

        $query = Contract::with([
            'owner',
            'services.service',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Email reminder scope
        |--------------------------------------------------------------------------
        | AM       → kontrak miliknya sendiri.
        | Inputter → kontrak yang dia input.
        | Manager dan Paycall tidak ikut reminder.
        */
        if ($user->isAccountManager()) {
            $query->where('owner_am_id', $user->id);
        }

        if ($user->isSupportInputter()) {
            $query->where('created_by', $user->id);
        }

        $sent = 0;

        foreach ($query->get() as $contract) {
            $daysRemaining = now()
                ->startOfDay()
                ->diffInDays($contract->end_date, false);

            if ($daysRemaining < 0 || $daysRemaining > 30) {
                continue;
            }

            $newStatus = $daysRemaining <= 7
                ? 'followup'
                : 'expiring';

            Mail::to($setting->manager_email)
                ->send(new ContractStatusChangedMail(
                    $contract,
                    $contract->status,
                    $newStatus
                ));

            ActivityLogger::log(
                'EMAIL_NOTIFICATION',
                'Sent reminder for contract ' . $contract->contract_number
            );

            $sent++;
        }

        return $sent;
    }

    private function shouldSendWeekly(string $schedule): bool
    {
        $now = now();

        return match ($schedule) {
            'monday_morning' => $now->isMonday() && $now->format('H:i') === '08:00',
            'monday_afternoon' => $now->isMonday() && $now->format('H:i') === '13:00',
            'friday_morning' => $now->isFriday() && $now->format('H:i') === '08:00',
            default => false,
        };
    }
}