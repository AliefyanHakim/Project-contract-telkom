<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CustomerContractReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contract $contract;
    public int $daysRemaining;

    public function __construct(
        Contract $contract,
        int $daysRemaining
    ) {
        $this->contract = $contract;
        $this->daysRemaining = $daysRemaining;
    }

    public function build()
    {
        $subject = $this->daysRemaining <= 7
            ? 'Urgent Contract Expiration Reminder'
            : 'Contract Expiration Reminder';

        $mail = $this
            ->subject($subject)
            ->view(
                'emails.customer-contract-reminder'
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