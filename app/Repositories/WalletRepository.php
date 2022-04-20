<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\
{
    User,
    UserAccountDetail,
    Wallet,
    Transaction,
    Bank
};
use App\Traits\ResponseApi;
use App\Interfaces\IWalletInterface;
use Illuminate\Support\Facades\
{   DB,
    Mail
};
use Illuminate\Http\Request;
use App\Http\Requests\
{
    a,
};


class WalletRepository implements IWalletInterface
{
    use ResponseApi;

    public function getWallet()
    {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;
        $transactions = Wallet::find($wallet->id)->transactions;
        $account_detail = User::find($user->id)->account_detail;
        $bank = Bank::where('id', $account_detail->bank_id)->first();

        $wallet->account_number = $account_detail->account_number;
        $wallet->bank_name = $bank->name;
        $wallet->bank_code = $bank->code;
        $data = [
            $wallet, $transactions
        ];
        return $this->success('successful', $data);
    }

    public function getAllTransactions()
    {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;
        $transactions = Wallet::find($wallet->id)->transactions;
        return $this->success('successful', $transactions);
    }

    public function getDebitTransactions()
    {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;
        $transactions = DB::table('transactions')->where(['wallet_id' => $wallet->id, 'transaction_type' => 'Debit'])->get();
        return $this->success('successful', $transactions);
    }

    public function getCreditTransactions()
    {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;
        $transactions = DB::table('transactions')->where(['wallet_id' => $wallet->id, 'transaction_type' => 'Credit'])->get();
        return $this->success('successful', $transactions);
    }

    public function getTransactionsByType(Request $request){
        $user = auth()->user();
        $transaction_type = $request->type;
        $wallet = User::find($user->id)->wallet;
        $transactions = DB::table('transactions')->where(['wallet_id' => $wallet->id, 'transaction_type' => $transaction_type])->get();
        return $this->success('successful', $transactions);
    }

    public function deleteTransaction()
    {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;
        Transaction::where('id', $wallet->id)->delete();
        return $this->success("transactions has been deleted successfully", null);
    }

    public function deleteAllTransactions()
    {
        $user = auth()->user();
        $wallet = User::find($user->id)->wallet;
        $transactions = Wallet::find($wallet->id)->transactions;
        foreach($transactions as $array):
            $array->delete();
        endforeach;
        return $this->success("transactions has been deleted successfully", null);
    }

    public function saveWalletPin(Request $request)
    {
        $user = auth()->user();
        $wallet_pin = $request->wallet_pin;
        $wallet = User::find($user->id)->wallet;
        Wallet::where('id', $wallet->id)->update(['wallet_pin' => $wallet_pin]);
        return $this->success("Transaction pin has been saved successfully", null);
    }

    public function withdraw()
    {
        $user = auth()->user();
        try{
            $wallet = User::find($user->id)->wallet;

        }catch(\Exception $e){
            $error_message = $e->getMessage();
            return $this->error($error_message);
        }

        return $this->success($message, null);
    }

    public function deposit()
    {
        $user = auth()->user();
        try{
            $wallet = User::find($user->id)->wallet;
            
        }catch(\Exception $e){
            $error_message = $e->getMessage();
            return $this->error($error_message);
        }

        return $this->success($message, null);
    }

}