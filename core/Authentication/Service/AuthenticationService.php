<?php

namespace Authentication\Service;

use App\Models\User;
use Authentication\Dto\LoginDto;
use Authentication\Exception\InvalidEmailOrPasswordException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthenticationService
{

    /**
     * @throws InvalidEmailOrPasswordException
     */
    public function authenticate(LoginDto $loginDto): void
    {
        $user = User::where('email', $loginDto->email)->first();

        if (!$user || !Hash::check($loginDto->password, $user->password)) {
            throw new InvalidEmailOrPasswordException();
        }

        Auth::login($user);

        request()->session()->regenerate();
    }

}
