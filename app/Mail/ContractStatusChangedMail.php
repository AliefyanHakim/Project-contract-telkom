<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ContractStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contract $contract;
    public string $oldStatus;
    public string $newStatus;

    public function __construct(
        Contract $contract,
        string $oldStatus,
        string $newStatus
    ) {
        $this->contract = $contract;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        $mail = $this
            ->subject(
                'Contract Status Update - '
                .$this->contract->contract_number
            )
            ->view(
                'emails.contract-status-changed'
            );

        /*
        |--------------------------------------------------------------------------
        | Attach Contract Files
        |--------------------------------------------------------------------------
        */

        foreach ($this->contract->files as $file) {

            if (!Storage::exists($file->file_path)) {
                continue;
            }

            $mail->attach(
                Storage::path($file->file_path),
                [
                    'as' => $file->file_name,
                ]
            );
        }

        return $mail;
    }
}