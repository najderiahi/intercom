<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    protected function validator($request) {
        return $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);
    }

    protected function passwordValidator($request) {
        return $request->validate([
            'old_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function index() {

    }

    public function show(User $user) {
        return 'Working';
    }

    public function update(Request $request, User $user) {
        $this->validator($request);
        $data = $request->only(['first_name', 'last_name']);
        $path = $request->file('avatar')->store('public/avatars');

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->avatar = $path;
        $user->save();
    }

    public function updatePassword(Request $request, User $user) {
        $this->passwordValidator($request);

        if(Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();


        } else {
            return redirect()->back();
        }
    }

    public function destroy(Request $request, User $user) {
        $user->delete();
    }
}
