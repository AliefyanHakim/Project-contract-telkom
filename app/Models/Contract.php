<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Contract extends Model
{
    protected $fillable = [
        'contract_number',
        'contract_name',

        'customer_id_number',

        'telkom_name',
        'telkom_position',
        'telkom_unit',

        'customer_address',
        'customer_npwp',

        'customer_pic_name',
        'customer_pic_position',
        'customer_phone',
        'customer_email',

        'signing_date',
        'signing_location',

        'owner_am_id',

        'start_date',
        'end_date',

        'generated_file',

        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_am_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function services()
    {
    return $this->hasMany(ContractService::class);
    }

    public function files()
    {
        return $this->hasMany(ContractFile::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function extensions()
    {
        return $this->hasMany(ContractExtension::class);
    }

    public function transferRequests()
    {
        return $this->hasMany(
            ContractTransferRequest::class
        );
    }

    public function transferHistory()
    {
        return $this->hasMany(
            ContractTransferHistory::class
        );
    }

    public function getCalculatedStatusAttribute()
    {
        if (!$this->end_date) {
            return 'active';
        }

        $days = Carbon::today()->diffInDays(
            Carbon::parse($this->end_date),
            false
        );

        if ($days < 0) {
            return 'expired';
        }

        if ($days <= 7) {
            return 'followup';
        }

        if ($days <= 30) {
            return 'expiring';
        }

        return 'active';
    }
    public function updateStatus()
    {
        $daysRemaining = now()
            ->startOfDay()
            ->diffInDays(
                $this->end_date,
                false
            );

        if ($daysRemaining < 0) {

            $this->status = 'expired';

        } elseif ($daysRemaining <= 7) {

            $this->status = 'followup';

        } elseif ($daysRemaining <= 30) {

            $this->status = 'expiring';

        } else {

            $this->status = 'active';
        }

        $this->save();
    }
}