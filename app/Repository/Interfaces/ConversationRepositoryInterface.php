<?php

namespace App\Repository\Interfaces;

interface ConversationRepositoryInterface {
    public function getConversations($userId);

    public function readAllFrom($sender_id, $receiver_id);

    public function unreadCount($id);
}
