<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Note;

class NoteController extends Controller
{
    //
    public function update(
    User $user,
    Note $note
)
{
    return $user->id === $note->user_id;
}
}
