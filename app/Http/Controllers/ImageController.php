<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function destroy(Request $request) {
        if($request->url) {
            if (Storage::disk('local')->exists($request->url))
            {
                Storage::delete($request->url);
            }
            $user = User::where('avatar', $request->url)->first();
            if($user) {
                $user->avatar = null;
                $user->save();
            }
            return response()->json(['data' => 'Deleted']);
        }
        return response()->json(['data' => 'ok']);
    }
}
