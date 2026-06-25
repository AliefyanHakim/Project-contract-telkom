<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;

class CheckContractStatus extends Command
{
    protected $signature = 'contracts:check-status';

    protected $description = 'Check and update contract status';

    public function handle()
    {
        $contracts = Contract::query()->get();

        $changedCount = 0;

        foreach ($contracts as $contract) {
            $oldStatus = $contract->status;

            $changed = $contract->updateStatus();

            if (!$changed) {
                continue;
            }

            $changedCount++;

            ActivityLogger::log(
                'CONTRACT_STATUS',
                'Contract ' . $contract->contract_number . ' changed from ' . $oldStatus . ' to ' . $contract->status
            );
        }

        $this->info('Contract status check completed. Updated: ' . $changedCount);

        return Command::SUCCESS;
    }
}