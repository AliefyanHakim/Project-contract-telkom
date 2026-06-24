<?php

namespace App\Http\Controllers;

use App\Models\BasoFile;
use Illuminate\Support\Facades\Storage;

class BasoFileController extends Controller
{
    public function download(BasoFile $baso)
    {
        if (!Storage::exists($baso->file_path)) {
            abort(404);
        }

        return Storage::download(
            $baso->file_path,
            $baso->file_name
        );
    }
}