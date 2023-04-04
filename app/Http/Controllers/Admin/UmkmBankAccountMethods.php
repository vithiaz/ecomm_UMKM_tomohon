<?php

namespace App\Http\Controllers\Admin;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UmkmBankAccountMethods extends Controller
{
    public function confirm_request($account_number, $id) {
        $bank_account = BankAccount::where([
            ['account_number', '=', $account_number],
            ['id', '=', $id],
        ])->get()->first();

        if ($bank_account == null) {
            return abort(404);
        }

        $bank_account->status = 'acc';
        $bank_account->save();

        return redirect()->route('admin.umkm-account-verification', ['status' => 'acc'])->with('message', 'Aktivasi rekening berhasil');
    }

    public function reject_request($account_number, $id) {
        $bank_account = BankAccount::where([
            ['account_number', '=', $account_number],
            ['id', '=', $id],
        ])->get()->first();

        if ($bank_account == null) {
            return abort(404);
        }

        $bank_account->status = 'rejected';
        $bank_account->save();

        return redirect()->route('admin.umkm-account-verification', ['status' => 'acc'])->with('message', 'Aktivasi rekening ditolak');
    }

    public function revoke_request($account_number, $id) {
        $bank_account = BankAccount::where([
            ['account_number', '=', $account_number],
            ['id', '=', $id],
        ])->get()->first();

        if ($bank_account == null) {
            return abort(404);
        }

        $bank_account->status = 'revoked';
        $bank_account->save();

        return redirect()->route('admin.umkm-account-verification', ['status' => 'revoked'])->with('message', 'Rekening dinonaktifkan');
    }
}
