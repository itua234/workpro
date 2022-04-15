<?php

namespace App\Interfaces;


use App\Http\Requests\
{
    LoginRequest,
    RegisterUserRequest,
    resetPasswordRequest
};

interface IAuthInterface
{
    public function register(RegisterUserRequest $request);

    public function login(LoginRequest $request);

    public function logout();

    public function refresh();

    public function resetPassword(resetPasswordRequest $request);

    public function sendverificationcode($id);

    public function verifyUser($verification_code);

    //public function verifyResetPasswordToken();
}