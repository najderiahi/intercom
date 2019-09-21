<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    protected function validator($request)
    {
        return $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);
    }

    protected function passwordValidator($request, $user)
    {
        return $request->validate([
            'old_password' => ['required', 'string', 'min:8',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Your password doesn\'t match our records.');
                    }
                },
            ],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function index()
    {

    }

    public function edit(User $user)
    {
        return view('auth.edit', compact('user'));
    }

    public function show(User $user)
    {
        return view('auth.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->validator($request);
        $data = $request->only(['first_name', 'last_name']);
        $path = $user->avatar;
        if(!is_null($path))
            Storage::delete($path);

        if($request->hasFile('avatar'))
            $path = $request->file('avatar')->store('public/avatars');

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        if($request->hasFile('avatar'))
            $user->avatar = $path;
        $user->save();
        return redirect()->route('users.show', $user->id);
    }

    public function updatePassword(Request $request, User $user)
    {
        $this->passwordValidator($request, $user);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } else {
            return redirect()->back();
        }

        return redirect()->route('users.show', $user);
    }

    public function destroy(Request $request, User $user)
    {
        $user->delete();
    }

    public function showUpdatePasswordForm(User $user)
    {
        return view('auth.edit-password', compact('user'));
    }
}
