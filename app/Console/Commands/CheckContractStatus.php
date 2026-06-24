<?php

namespace App\Console\Commands;

use App\Mail\ContractStatusChangedMail;
use App\Models\Contract;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckContractStatus extends Command
{
    protected $signature = 'contracts:check-status';

    protected $description =
        'Check contract status and send notifications';

    public function handle()
    {
        $contracts = Contract::with('owner')->get();

        $setting = NotificationSetting::settings();

        foreach ($contracts as $contract) {

            $oldStatus = $contract->status;

            $changed = $contract->updateStatus();

            if (!$changed) {
                continue;
            }

            $newStatus = $contract->status;

            /*
            |--------------------------------------------------------------------------
            | Hanya kirim email untuk expiring dan followup
            |--------------------------------------------------------------------------
            */

            if (!in_array($newStatus, [
                'expiring',
                'followup'
            ])) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Ambil Recipient
            |--------------------------------------------------------------------------
            */

            $supportInputters = User::where(
                'role_id',
                User::ROLE_SUPPORT_INPUTTER
            )->pluck('email');

            $supportPaycalls = User::where(
                'role_id',
                User::ROLE_SUPPORT_PAYCALL
            )->pluck('email');

            $amEmail = $contract->owner?->email;

            $customerEmail = $contract->customer_email;

            $recipients = collect([
                $setting->manager_email,
                $amEmail,
                $customerEmail,
            ])
            ->merge($supportInputters)
            ->merge($supportPaycalls)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

            /*
            |--------------------------------------------------------------------------
            | Send Email
            |--------------------------------------------------------------------------
            */

            foreach ($recipients as $email) {

                Mail::to($email)
                    ->send(
                        new ContractStatusChangedMail(
                            $contract,
                            $oldStatus,
                            $newStatus
                        )
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Activity Log
            |--------------------------------------------------------------------------
            */

            ActivityLogger::log(
                'NOTIFICATION',
                'Contract '
                .$contract->contract_number
                .' changed from '
                .$oldStatus
                .' to '
                .$newStatus
            );
        }

        $this->info(
            'Contract status check completed.'
        );

        return Command::SUCCESS;
    }
}