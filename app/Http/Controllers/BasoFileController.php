<?php

namespace App\Http\Controllers;

use App\Models\BasoFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BasoFileController extends Controller
{
    private function ensureBasoAccess(BasoFile $baso): void
    {
        $user = Auth::user();
        $contract = $baso->contract;

        if (!$contract) {
            abort(404);
        }

        if ($user->isAccountManager() && $contract->owner_am_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file BASO ini.');
        }
    }

    public function view(BasoFile $baso)
    {
        $this->ensureBasoAccess($baso);

        if (!Storage::exists($baso->file_path)) {
            abort(404, 'File not found');
        }

        return response()->file(
            Storage::path($baso->file_path),
            [
                'Content-Disposition' => 'inline; filename="' . $baso->file_name . '"',
            ]
        );
    }

    public function download(BasoFile $baso)
    {
        $this->ensureBasoAccess($baso);

        if (!Storage::exists($baso->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::download(
            $baso->file_path,
            $baso->file_name
        );
    }
}