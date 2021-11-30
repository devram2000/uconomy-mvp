<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'size:10', Rule::unique('users')->ignore($user->id)],
            'date_of_birth' => ['required','date', 'before:-18 years'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:4000'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        $user->forceFill([
            'name' => $input['name'],
        ])->save();

        if ($input['email'] !== $user->email) {
            if ($user instanceof MustVerifyEmail) {
                $this->updateVerifiedUser($user, $input);
            } else {
                $user->forceFill([
                    'email' => $input['email'],
                    'email_verified_at' => null,
                ])->save();
            }           
        } 

        if ($input['phone_number'] !== $user->phone_number) {
            $user->forceFill([
                'phone_number' => $input['phone_number'],
                'phone_verified_at' => null,
            ])->save();         
        } 

        if ($input['date_of_birth'] !== $user->date_of_birth) {
            $user->forceFill([
                'date_of_birth' => $input['date_of_birth'],
            ])->save();         
        } 
    }
    
    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
