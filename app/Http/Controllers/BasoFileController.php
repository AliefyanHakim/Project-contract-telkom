<?php

namespace App\Http\Controllers;

use App\Models\BasoFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BasoFileController extends Controller
{
    public function download(BasoFile $baso)
    {
        $user = Auth::user();
        $contract = $baso->contract;

        if (!$contract) {
            abort(404);
        }

        if ($user->isAccountManager() && $contract->owner_am_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file BASO ini.');
        }

        if (!Storage::exists($baso->file_path)) {
            abort(404);
        }

        return Storage::download(
            $baso->file_path,
            $baso->file_name
        );
    }
}