<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\
{
    LoginRequest,
    RegisterUserRequest,
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

    public function refresh(Request $request);

    public function sendverificationcode($id);

    public function verifyUser($verification_code);

    public function resetPassword(ResetPasswordRequest $request);

    public function verifyResetPasswordToken(VerifyResetPasswordTokenRequest $request);

    public function password_reset(PasswordResetRequest $request);

    public function change_password(ChangePasswordRequest $request);
}