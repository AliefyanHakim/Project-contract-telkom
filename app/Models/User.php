<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'role_id',
    'name',
    'email',
    'password',
    'status',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_MANAGER = 1;
    const ROLE_ACCOUNT_MANAGER = 2;
    const ROLE_SUPPORT_INPUTTER = 3;
    const ROLE_SUPPORT_PAYCALL = 4;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ownedContracts()
    {
        return $this->hasMany(Contract::class, 'owner_am_id');
    }

    public function createdContracts()
    {
        return $this->hasMany(Contract::class, 'created_by');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function notificationsList()
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function uploadedFiles()
    {
        return $this->hasMany(ContractFile::class, 'uploaded_by');
    }

    public function transferRequests()
    {
        return $this->hasMany(
            ContractTransferRequest::class,
            'requested_by'
        );
    }

    public function approvedTransfers()
    {
        return $this->hasMany(
            ContractTransferRequest::class,
            'approved_by'
        );
    }

    public function isManager(): bool
    {
        return $this->role_id === self::ROLE_MANAGER;
    }

    public function isAccountManager(): bool
    {
        return $this->role_id === self::ROLE_ACCOUNT_MANAGER;
    }

    public function isSupportInputter(): bool
    {
        return $this->role_id === self::ROLE_SUPPORT_INPUTTER;
    }

    public function isSupportPaycall(): bool
    {
        return $this->role_id === self::ROLE_SUPPORT_PAYCALL;
    }

    public function notificationSetting()
    {
    return $this->hasOne(
        NotificationSetting::class
    );
    }
}