<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MailRegistrationVerification extends Controller
{
    public function verify_user($user_id, $verify_token)
    {
        $userAccount = User::find($user_id);
        if ($userAccount) {
            $verification_token = hash('sha512', $userAccount->username . $userAccount->first_name);

            if ($verification_token != $verify_token) {
                return response()->json(['user verification failed : verification token is invalid']);
            }

            $userAccount->email_verified_at = Carbon::now();
            $userAccount->save();
            return redirect()->route('account-settings');
        }
        else {
            return response()->json(['user verification failed : id is invalid']);
        }

    }
}
