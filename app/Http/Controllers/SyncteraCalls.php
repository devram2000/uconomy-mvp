<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SyncteraCalls extends Controller
{
    public static function createPerson($first_name, $last_name, $dob, $email, $phone_number) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/persons', [
            'status' => 'PROSPECT',
            "is_customer" => true,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'dob' => $dob,
            'email' => $email,
            'phone_number' => $phone_number,

        ]);

        if($response->failed()) {
            dd($response->body());
        }

        return $response['id'];

    }
}
