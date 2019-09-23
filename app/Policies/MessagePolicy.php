<?php

namespace App\Policies;

use App\Message;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function read(User $user, Message $message) {
        return (int) $user->id === (int) $message->receiver_id;
    }
}
