<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Address;
use App\Models\Identification;


class EnsureAccountValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $zelle = Auth::user()->zelle;
        $phone_verify = Auth::user()->phone_verified_at;
        $email_verify = Auth::user()->email_verified_at;
        $address = Address::where('user', Auth::id())->first();
        $date_of_birth = Auth::user()->date_of_birth;

        $identity_verify = count(Identification::where('user', Auth::id())->get());

        
        if($zelle == null || $email_verify == null || $phone_verify == null
        || $identity_verify == 0 || $address == null || $date_of_birth == null) {
            return redirect('home');
        } 

        return $next($request);
    }
}
