<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConversationsController extends Controller
{
    public function index() {
        return view('auth.chat');
    }

    public function show() {
        return view('auth.chat');
    }
}
