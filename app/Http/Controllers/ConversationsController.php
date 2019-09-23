<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{
    public function index() {
        $user = Auth::user();
        $message = Message::whereRaw("receiver_id", $user->id)->orWhere("sender_id", $user->id)->orderBy('created_at', 'DESC')->first();
        if($message) {
            if($message->sender_id == $user->id) {
                $user = $message->receiver;
            } else {
                $user = $message->sender;
            }
        }
        return redirect()->route('conversations.show', $user->id);
    }

    public function show() {
        return view('auth.chat');
    }
}
