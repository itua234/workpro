<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IProfileInterface;
use App\Http\Requests\
{
    SaveAccountDetailsRequest,
    SaveNextOfKinDetailsRequest,
    DeleteUserAccountRequest
};

class ProfileController extends Controller
{
    protected $profileInterface;

    public function __construct(IProfileInterface $profileInterface)
    {
        $this->profileInterface = $profileInterface;
    }

    public function saveAccountDetails(SaveAccountDetailsRequest $request)
    {
        return $this->profileInterface->saveAccountDetails($request);
    }

    public function saveNextOfKinDetails(SaveNextOfKinDetailsRequest $request)
    {
        return $this->profileInterface->saveNextOfKinDetails($request);
    }

    public function delete(DeleteUserAccountRequest $request)
    {
        return $this->profileInterface->delete($request);
    }
}
