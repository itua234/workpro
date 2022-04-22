<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\
{   User,
    Wallet
};
use App\Traits\ResponseApi;

use App\Mail\
{
    verifyAccountMail,
    ForgetPasswordMail
};
use App\Interfaces\IAuthInterface;
use Illuminate\Support\Facades\{
    DB,
    Mail,
    Hash
};
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

class AuthRepository implements IAuthInterface
{
    use ResponseApi;

    public function register(RegisterUserRequest $request)
    {
        try{
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password
            ]);
            $wallet = Wallet::create([
                'user_id' => $user->id
            ]);

            $fullname = $user->firstname." ".$user->lastname;
            $code = mt_rand(1000, 9999);
            $expiry_time = Carbon::now()->addMinutes(6);
            DB::table('user_verification')->insert(['email' => $user->email, 'code' => $code, 'expiry_time' => $expiry_time]);

            $data = ['name' => $fullname, 'code' => $code];
            //Mail::to($user->email)->send(new verifyAccountMail($data));
        }catch(\Exception $e){
            $message = $e->getMessage();
            return $this->error($message);
        }

        $message = 'Thanks for signing up! Please check your email to complete your registration.';
        return $this->success($message, $user, 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where("email", $request->email)->first();
        
        if(!$user || !password_verify($request->password, $user->password)):
            $message = "Wrong credentials";
            return $this->error($message, 400);
        elseif($user->is_verified !== 1):
            $message = "Email address not verified, please verify your email before you can login";
            return $this->error($message, 401);
        endif;

        $token = $user->createToken("workpro")->plainTextToken;
        $user->token = $token;
        $message = 'Login successfully';
        return $this->success($message, $user);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success("user logout", null);
    }

    public function refresh()
    {
        $user = auth()->user();

        $user->tokens()->delete();

        $token = $user->createToken("workpro")->plainTextToken;

        return $this->success("token refreshed successfully", $token);
    }

    public function sendverificationcode($email)
    {
        $user = User::where('email', $email)->first();

        try{
            $fullname = $user->firstname." ".$user->lastname;
            $code = mt_rand(1000, 9999);
            $expiry_time = Carbon::now()->addMinutes(6);
            DB::table('user_verification')->update(['code' => $code, 'expiry_time' => $expiry_time]);

            $data = ['name' => $fullname, 'code' => $code];
            //Mail::to($user->email)->send(new verifyAccountMail($data));
        }catch(\Exception $e){
            $message = $e->getMessage();
            return $this->error($message);
        }

        $message = 'A new verification code has been sent to your email.';
        return $this->success($message, null);
    }

    public function verifyUser(VerifyAccountCodeRequest $request)
    {
        $check = DB::table('user_verification')->where(['email' => $request->email, 'code' => $request->code])->first();
        $current_time = Carbon::now();
        try{
            switch(is_null($check)):
                case(false):
                    if($check->expiry_time < $current_time):
                        $message = 'Verification code is expired';
                    else:
                        $user = User::where('email', $check->email)->first();
                        User::where('id', $user->id)->update(['is_verified' => 1, 'email_verified_at' => $current_time]);
                        DB::table('user_verification')->where('code', $request->code)->delete();

                        $message = 'Your email address is verified successfully.';
                        return $this->success($message, null);
                    endif;
                break;
                default:
                    $message = "Verification code is invalid.";
            endswitch;
        }catch(\Exception $e){
            $error_message = $e->getMessage();
            return $this->error($error_message);
        }

        return $this->error($message, 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $check = DB::table('password_resets')->where('email', $request->email)->first();

        $fullname = $user->firstname." ".$user->lastname;
        $reset_token = mt_rand(1000, 9999);
        $timestamp = Carbon::now()->addMinutes(6);
        $data = ['name' => $fullname, 'token' => $reset_token];

        try{
            switch(is_null($check)):
                case(false):
                    if($check->created_at > Carbon::now()):
                        $message = 'Your token has already been sent.';
                        return $this->error($message, 400);
                    else:
                        DB::table('password_resets')->update(['token' => $reset_token, 'created_at' => $timestamp]);
                        //Mail::to($request->email)->send(new ForgetPasswordMail($data));
                        $message = 'A new reset email has been sent! Please check your email.';
                    endif;
                break;
            default:
                DB::table('password_resets')->insert(['email' => $request->email, 'token' => $reset_token, 'created_at' => $timestamp]);
                //Mail::to($request->email)->send(new ForgetPasswordMail($data));
                $message = 'A reset email has been sent! Please check your email.';
            endswitch;
        }catch(\Exception $e){
            $error_message = $e->getMessage();
            return $this->error($error_message);
        }

        return $this->success($message, null);
    }

    public function verifyResetPasswordToken(VerifyResetPasswordTokenRequest $request)
    {
        $check = DB::table('password_resets')->where(['token' => $request->token, 'email' => $request->email])->first();
        $timestamp = Carbon::now();
        if(!is_null($check)):
            if($check->created_at > $timestamp):
                $message = 'Your token verification was successful.';
                return $this->success($message, $check);
            else:
                $message = "Password reset token is expired.";
            endif;
        else:
            $message = "Invalid data.";
        endif;

        return $this->error($message, 400);
    }

    public function password_reset(PasswordResetRequest $request)
    {   
        try{
            $user = User::where('email', $request->email)->first();
            $user->password = $request->password;
            $user->save();
            DB::table('password_resets')->where(['email' => $request->email])->delete();
            $message = 'Your password has been changed!';
        }catch(\Exception $e){
            $error_message = $e->getMessage();
            return $this->error($error_message);
        }

        return $this->success($message, null);
    }

    public function change_password(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        try{
            if((Hash::check($request->old_password, $user->password)) == false):
                $message = "Check your old password.";
            elseif((Hash::check($request->new_password, $user->password)) == true):
                $message = "Please enter a password which is not similar to your current password.";
            else:
                $user->password = $request->new_password;
                $user->save();
                $message = "Your password has been changed successfully";
                return $this->success($message, null);
            endif;
        }catch(\Exception $e){
            $error_message = $e->getMessage();
            return $this->error($error_message);
        }
        
        return $this->error($message, 400);
    }
}