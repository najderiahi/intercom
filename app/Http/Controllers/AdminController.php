<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function setUserActiveness(Request $request, User $user) {
        $this->activationValidator();
        $user->active = $request->active;
        $user->save();

        return response()->json(['data' => "Supprimer avec succès"]);
    }

    public function activationValidator() {
        return [
            'active' => 'required|boolean'
        ];
    }

    public function dashboard() {
        return view('admin.dashboard');
    }
}
