<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api");
        $this->middleware("can:is-active");
    }

    public function read(Request $request, Message $message) {
        $message->update(['read_at' => Carbon::now()]);
        return ['success' => 1];
    }


}
