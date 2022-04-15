<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IAuthInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\resetPasswordRequest;


class AuthController extends Controller
{
    protected $authInterface;

    public function __construct(IAuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function store(RegisterUserRequest $request)
    {
        return $this->authInterface->register($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->authInterface->login($request);
    }

    public function logout()
    {
        return $this->authInterface->logout();
    }

    public function sendcode($id)
    {
        return $this->authInterface->sendverificationcode($id);
    }

    public function verifyUser($verification_code)
    {
        return $this->authInterface->verifyUser($verification_code);
    }

    public function resetPassword(resetPasswordRequest $request)
    {
        return $this->authInterface->resetPassword($request);
    }

    /*public function verifyResetPasswordToken(Request $request)
    {
        return $this->authInterface->verifyResetPasswordToken($request);
    }*/

}
