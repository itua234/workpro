<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\
{
    SaveAccountDetailsRequest,
    SaveNextOfKinDetailsRequest,
    DeleteUserAccountRequest
};

interface IProfileInterface
{
    public function saveAccountDetails(SaveAccountDetailsRequest $request);

    public function saveNextOfKinDetails(SaveNextOfKinDetailsRequest $request);

    public function delete(DeleteUserAccountRequest $request);
}