<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\
{
    User,
    UserAccountDetail
};
use App\Traits\ResponseApi;
use App\Interfaces\IProfileInterface;
use Illuminate\Support\Facades\
{   DB,
    Mail
};
use Illuminate\Http\Request;
use App\Http\Requests\
{
    SaveAccountDetailsRequest,
    SaveNextOfKinDetailsRequest,
    DeleteUserAccountRequest
};


class ProfileRepository implements IProfileInterface
{
    use ResponseApi;

    public function saveAccountDetails(SaveAccountDetailsRequest $request)
    {
        $user = auth()->user();
        $check = UserAccountDetail::where('user_id', $user->id)->first();
        switch(is_null($check)):
            case(true):
                UserAccountDetail::create([
                    'user_id' => $user->id,
                    'bank_id' => $request->bank_id,
                    'account_number' => $request->account_number,
                ]);
                $message = 'Next Of Kin Details Submitted Successfully';
                return $this->success($message, null);
            break;
            default:
                UserAccountDetail::where('user_id', $user->id)->update([
                    'bank_id' => $request->bank_id,
                    'account_number' => $request->account_number,
                ]);
                $message = 'Next Of Kin Details updated Successfully';
                return $this->success($message, null);
        endswitch;
    }

    public function saveNextOfKinDetails(SaveNextOfKinDetailsRequest $request)
    {
        return auth()->user();
        try{
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
            ]);
            
        }catch(\Exception $e){
            $message = $e->getMessage();
            return $this->error($message);
        }
        $message = '';
        return $this->success($message, $user);
    }

    public function delete(DeleteUserAccountRequest $request)
    {
        $user = auth()->user();
        if(!password_verify($request->password, $user->password)):
            $message = "Wrong credentials";
            return $this->error($message, 400);
        endif;

        User::where('id', $user->id)->delete();
        $message = 'Account has been deleted successfully';
        return $this->success($message, null);
    }

}