<?php

namespace App\Interfaces;

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

interface IAuthInterface
{
    public function register(RegisterUserRequest $request);

    public function login(LoginRequest $request);

    public function logout();

    public function refresh();

    public function sendverificationcode($email);

    public function verifyUser(VerifyAccountCodeRequest $request);

    public function resetPassword(ResetPasswordRequest $request);

    public function verifyResetPasswordToken(VerifyResetPasswordTokenRequest $request);

    public function password_reset(PasswordResetRequest $request);

    public function change_password(ChangePasswordRequest $request);
}