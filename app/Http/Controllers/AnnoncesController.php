<?php

namespace App\Http\Controllers;

use App\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnoncesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:is-active');
    }


    protected function validator(Request $request) {
        return $request->validate([
            'content' => ['required', 'string'],
        ]);
    }

    public function store(Request $request) {
        $this->validator($request);
        $data = $request->only(['content']);
        $data = array_merge($data, ['user_id' => Auth::id()]);

        Annonce::create($data);
    }

    public function update(Request $request, Annonce $annonce) {
        $this->validator($request);
        $annonce->content = $request->get('content');
        $annonce->save();
    }

    public function destroy(Request $request, Annonce $annonce) {
        $annonce->delete();
    }

}
