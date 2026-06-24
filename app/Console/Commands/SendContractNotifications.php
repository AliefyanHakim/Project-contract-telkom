<?php

namespace App\Console\Commands;

use App\Mail\ContractStatusChangedMail;
use App\Mail\CustomerContractReminderMail;
use App\Models\Contract;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendContractNotifications extends Command
{
    protected $signature = 'contracts:notify';

    protected $description =
        'Send contract expiration notifications';

    public function handle()
    {
        $settings = NotificationSetting::settings();

        $now = now();

        /*
        |--------------------------------------------------------------------------
        | DAILY FOLLOWUP (<= 7 HARI)
        |--------------------------------------------------------------------------
        */

        if (
            $now->format('H:i')
            === $settings->daily_schedule
        ) {
            $this->sendFollowupNotifications();
        }

        /*
        |--------------------------------------------------------------------------
        | WEEKLY EXPIRING (8-30 HARI)
        |--------------------------------------------------------------------------
        */

        if (
            $this->shouldSendWeekly(
                $settings->weekly_schedule
            )
        ) {
            $this->sendExpiringNotifications();
        }

        return self::SUCCESS;
    }

    private function shouldSendWeekly(
        string $schedule
    ): bool {

        $now = now();

        return match ($schedule) {

            'monday_morning' =>
                $now->isMonday()
                && $now->format('H:i') === '08:00',

            'monday_afternoon' =>
                $now->isMonday()
                && $now->format('H:i') === '13:00',

            'friday_morning' =>
                $now->isFriday()
                && $now->format('H:i') === '08:00',

            default => false,
        };
    }

    private function sendFollowupNotifications()
    {
        $contracts = Contract::with([
            'owner',
            'files'
        ])->get();

        foreach ($contracts as $contract) {

            $daysRemaining =
                now()->startOfDay()
                ->diffInDays(
                    $contract->end_date,
                    false
                );

            if (
                $daysRemaining < 0 ||
                $daysRemaining > 7
            ) {
                continue;
            }

            $this->sendEmails(
                $contract,
                $daysRemaining
            );
        }
    }

    private function sendExpiringNotifications()
    {
        $contracts = Contract::with([
            'owner',
            'files'
        ])->get();

        foreach ($contracts as $contract) {

            $daysRemaining =
                now()->startOfDay()
                ->diffInDays(
                    $contract->end_date,
                    false
                );

            if (
                $daysRemaining < 8 ||
                $daysRemaining > 30
            ) {
                continue;
            }

            $this->sendEmails(
                $contract,
                $daysRemaining
            );
        }
    }

    private function sendEmails(
        Contract $contract,
        int $daysRemaining
    ) {

        $supportInputters = User::where(
            'role_id',
            User::ROLE_SUPPORT_INPUTTER
        )->pluck('email');

        $supportPaycalls = User::where(
            'role_id',
            User::ROLE_SUPPORT_PAYCALL
        )->pluck('email');

        $setting =
            NotificationSetting::settings();

        $internalRecipients = collect([
            $setting->manager_email,
            $contract->owner?->email,
        ])
        ->merge($supportInputters)
        ->merge($supportPaycalls)
        ->filter()
        ->unique();

        /*
        |--------------------------------------------------------------------------
        | Internal Email
        |--------------------------------------------------------------------------
        */

        foreach ($internalRecipients as $email) {

            Mail::to($email)->queue(
                new ContractStatusChangedMail(
                    $contract,
                    'active',
                    $daysRemaining <= 7
                        ? 'followup'
                        : 'expiring'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Email
        |--------------------------------------------------------------------------
        */

        if ($contract->customer_email) {

            Mail::to(
                $contract->customer_email
            )->queue(
                new CustomerContractReminderMail(
                    $contract,
                    $daysRemaining
                )
            );
        }

        ActivityLogger::log(
            'NOTIFICATION',
            'Sent reminder for contract '
            .$contract->contract_number
        );
    }
}