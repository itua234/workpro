<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IWalletInterface;
use App\Http\Requests\
{
    a,
};

class WalletController extends Controller
{
    protected $walletInterface;

    public function __construct(IWalletInterface $walletInterface)
    {
        $this->walletInterface = $walletInterface;
    }

    public function getWallet()
    {
        return $this->walletInterface->getWallet();
    }
}
