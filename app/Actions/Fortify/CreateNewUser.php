<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SyncteraCalls;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'size:10', 'unique:users'],
            'date_of_birth' => ['required','date', 'before:-18 years'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();



        $person_id = SyncteraCalls::createPerson($input['first_name'], $input['last_name'], $input['date_of_birth'], $input['email'], "+1" . $input['phone_number']);

        return User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'name' => $input['first_name'] . " " . $input['last_name'],
            'email' => $input['email'],
            'phone_number' => $input['phone_number'],
            'date_of_birth' => $input['date_of_birth'],
            'password' => Hash::make($input['password']),
            'synctera_id' => $person_id,
            'limit' => 250,
            'terms' => date_format(date_create(), 'Y-m-d H:i:s'),
        ]);
    }
}
