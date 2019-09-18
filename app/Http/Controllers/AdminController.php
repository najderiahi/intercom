<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function setUserActiveness(Request $request, User $user) {
        $user->active = $request->active;
        $user->save();
    }

    public function activationValidator() {
        return [
            'active' => 'required|boolean'
        ];
    }
}
