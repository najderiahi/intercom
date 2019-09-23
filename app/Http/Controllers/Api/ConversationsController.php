<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Repository\Interfaces\ConversationRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConversationsController extends Controller
{

    /**
     * @var ConversationRepositoryInterface
     */
    private $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function index(Request $request)
    {

        $conversations = $this->conversationRepository->getConversations($request->user()->id);
        $unread = $this->conversationRepository->unreadCount($request->user()->id);
        foreach($conversations as $conversation) {
            if(isset($unread[$conversation->id])) {
                $conversation->unread = $unread[$conversation->id];
            } else {
                $conversation->unread = 0;
            }
        }
        return response()->json([
            "conversations" => $conversations
        ]);
    }

    public function show(Request $request, User $user)
    {
        $messagesQuery = $this->conversationRepository->getMessagesFor($request->user()->id, $user->id);
        $count = null;
        if ($request->get('before')) {
            $messagesQuery = $messagesQuery->where("created_at", "<", $request->get('before'));
        } else {
            $count = $messagesQuery->count();
        }
        $update = false;
        $messages = $messagesQuery->limit(10)->get();
        foreach($messages as $message) {
            if($message->read_at == null && $message->receiver_id === $request->user()->id) {
                $message->read_at = Carbon::now();
                if($update === false) {
                    $this->conversationRepository->readAllFrom($message->sender_id, $message->receiver_id);
                }
                $update = true;
                break;
            }
        }
        return response()->json([
            "messages" => array_reverse($messages->toArray()),
            "count" => $request->get('before') ? '' : $messagesQuery->count()
        ]);
    }

    public function store(MessageRequest $request, User $user)
    {
        $message = $this->conversationRepository->createMessage($request->get('content'), $request->user()->id, $user->id);
        broadcast(new NewMessage($message));
        return response()->json(['message' => $message]);
    }
}
