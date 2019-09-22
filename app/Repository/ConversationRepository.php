<?php

namespace App\Repository;

use App\Message;
use App\Repository\Interfaces\ConversationRepositoryInterface;
use App\User;
use Carbon\Carbon;

class ConversationRepository implements ConversationRepositoryInterface
{


    public function getConversations($userId)
    {
        return User::select('first_name', 'last_name', 'avatar', 'id')->where('active', 1)->get();
    }

    public function getMessagesFor($senderId, $receiverId)
    {
        return Message::Query()->whereRaw("((receiver_id = $receiverId and sender_id = $senderId) or (sender_id = $receiverId and receiver_id = $senderId))")
            ->orderBy('created_at', 'DESC');
    }

    public function createMessage($content, $senderId, $receiverId) {
        return Message::create(["content" => $content, "sender_id" => $senderId, "receiver_id" => $receiverId]);
    }

    public function readAllFrom($sender_id, $receiver_id)
    {
        Message::where('receiver_id', $receiver_id)->where('sender_id', $sender_id)->update(['read_at' => Carbon::now()]);
    }

    public function unreadCount($id)
    {
        return Message::Query()
            ->where('receiver_id', $id)
            ->groupBy('sender_id')
            ->selectRaw('sender_id, COUNT(id) as count')
            ->whereRaw('read_at IS NULL')
            ->get()
            ->pluck("count", "sender_id");
    }
}
