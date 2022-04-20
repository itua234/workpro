<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Wallet; 
use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class ClientWalletController extends Controller
{
    public function getWallet(Request $request){
        //$client = auth()->user();
        $client = $request->id;
        $wallet = Client::find($client)->wallet;
        $transactions = Wallet::find($wallet->id)->transactions;
        return response()->json([
            'success'=> true,
            'wallet' => $wallet,
            'transactions' => $transactions
        ],200);
    }

    public function deleteTransaction(Request $request){
        //$client = auth()->user();
        $client = $request->id;
        $wallet = Client::find($client)->wallet;
        Transaction::where('id', $wallet->id)->delete();
        return response()->json([
            'success'=> true,
            'message' => "transactions has been deleted successfully"
        ]);
    }

    public function deleteAllTransactions(Request $request){
        //$client = auth()->user();
        $client = $request->id;
        $wallet = Client::find($client)->wallet;
        $transactions = Wallet::find($wallet->id)->transactions;
        foreach($transactions as $array):
            $array->delete();
        endforeach;
        return response()->json([
            'success' => true,
            'message' => 'transactions has been deleted successfully'
        ]);
    }

    public function getDebitTransactions(Request $request){
        //$client = auth()->user();
        $client = $request->id;
        $wallet = Client::find($client)->wallet;
        //$transactions = Wallet::find($wallet->id)->transactions;
        $transactions = DB::table('transactions')->where(['wallet_id' => $wallet->id, 'transaction_type' => 'Debit'])->get();
        return response()->json([
            'success'=> true,
            'transactions' => $transactions
        ],200);
    }

    public function getCreditTransactions(Request $request){
        //$client = auth()->user();
        $client = $request->id;
        $wallet = Client::find($client)->wallet;
        //$transactions = Wallet::find($wallet->id)->transactions;
        $transactions = DB::table('transactions')->where(['wallet_id' => $wallet->id, 'transaction_type' => 'Credit'])->get();
        return response()->json([
            'success'=> true,
            'transactions' => $transactions
        ], 200);
    }

    public function getTransactionsByType(Request $request){
        //$client = auth()->user();
        $client = $request->id;
        $transaction_type = $request->type;
        $wallet = Client::find($client)->wallet;
        //$transactions = Wallet::find($wallet->id)->transactions;
        $transactions = DB::table('transactions')->where(['wallet_id' => $wallet->id, 'transaction_type' => $transaction_type])->get();
        return response()->json([
            'success'=> true,
            'transactions' => $transactions
        ], 200);
    }

    public function saveTransactionPin(Request $request){
        $client = auth()->user();
        $transaction_pin = $request->transaction_pin;
        $rules = array(
            'transaction_pin' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        switch(true):
            case($validator->fails()):
                return response()->json([
                    'success'=> false, 
                    'errors' => $validator->getMessageBag()->toArray()
                ], 422);
            break;
            default:
                $wallet = Client::find($client->id)->wallet;
                Wallet::where('id', $wallet->id)->update(['transaction_pin' => $transaction_pin]);
                return response()->json([
                    'success'=> true,
                    'message' => 'Transaction pin has been saved successfully'
                ], 200);
        endswitch;
    }

    public function sendVerificationCodeForAccount(Request $request){
        $client = auth()->user();
        $verification_code = $request->verification_code;
        $fullname = $client->firstname." ".$client->lastname;
        try{
            $data = ['name' => $fullname, 'code' => $verification_code];
            Mail::to($client->email)
                ->send(new verifyMail($data));
        }catch(\Exception $e){
            //Return with error
            $error_message = $e->getMessage();
            return response()->json([
                'success' => false, 
                'error' => $error_message
            ],401);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent'
        ],200);
    }

    public function saveAccountDetails(Request $request){
        $client = auth()->user();
        $account_number = $request->account_number;
        $bank_name = $request->bank_name;
        $bank_code = $request->bank_code;
        $wallet = Client::find($client->id)->wallet;
        Wallet::where('id', $wallet->id)->update([
            'account_number' => $account_number,
            'bank_name' => $bank_name,
            'bank_code' => $bank_code
        ]);
        return response()->json([
            'success'=> true,
            'message' => 'Account details has been saved successfully'
        ], 200);
    }
}
