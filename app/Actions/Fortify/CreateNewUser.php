<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], [
            'name.required' => 'Please enter your name',
            'name.string' => 'Name must be a valid string',
            'name.max' => 'Name must not be larger than 255 chars',
            'email.required' => 'Please enter your email address',
            'email.string' => 'Email Address must be a valid string',
            'email.email' => 'Email Address must be a valid email address',
            'email.max' => 'Email Address must not be larger than 255 chars',
            'password.required' => 'Please enter your password',
            'password.string' => 'Password must be a valid string'
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
