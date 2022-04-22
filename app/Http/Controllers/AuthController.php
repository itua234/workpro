<?php

namespace App\Http\Controllers;

use App\Interfaces\IAuthInterface;
use Illuminate\Http\Request;
use App\Http\Requests\
{
    LoginRequest,
    RegisterUserRequest,
    VerifyAccountCodeRequest,
    ResetPasswordRequest,
    PasswordResetRequest,
    VerifyResetPasswordTokenRequest,
    ChangePasswordRequest
};


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

    public function refresh()
    {
        return $this->authInterface->refresh();
    }

    public function sendcode($email)
    {
        return $this->authInterface->sendverificationcode($email);
    }

    public function verifyUser(VerifyAccountCodeRequest $request)
    {
        return $this->authInterface->verifyUser($request);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->authInterface->resetPassword($request);
    }

    public function verifyResetPasswordToken(VerifyResetPasswordTokenRequest $request)
    {
        return $this->authInterface->verifyResetPasswordToken($request);
    }

    public function password_reset(PasswordResetRequest $request)
    {
        return $this->authInterface->password_reset($request);
    }

    public function change_password(ChangePasswordRequest $request)
    {
        return $this->authInterface->change_password($request);
    }

}
