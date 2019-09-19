<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            "content" => "required|string"
        ]);
    }

    public function store(Request $request, User $user)
    {
        $validator = $this->validator($request);
        if($validator->fails()) {
            return response()->json($validator->errors(), 402);
        }
        $sender = Auth::user();
        $data = ['content' => $request->get('content'), 'sender_id' => $sender->id, "receiver_id" => $user->id];
        $message = Message::create($data);
        return response()->json([], 200);
    }


}
