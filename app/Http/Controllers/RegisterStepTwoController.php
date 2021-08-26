<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterStepTwoController extends Controller
{
    public function create() {
        return view('auth.register-step2');
    }

    public function update(Request $request) {
        auth()->user()->update([
            'name' => $request['name'],
            'password' => Hash::make($request['password'])
        ]);

        return redirect()->route('dashboard');
    }
}
