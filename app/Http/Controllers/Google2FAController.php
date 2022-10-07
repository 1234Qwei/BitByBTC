<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;
use App\Models\User;
use Cache;

class Google2FAController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        $google2fa = new Google2FA();
        // generate new secret
        $secret = $google2fa->generateSecretKey();

        // get user
        $user = $request->user();

        // encrypt and then save secret
        $user->g2f_temp = Crypt::encrypt($secret);
        $user->save();

        // generate image for QR barcode
        $imageDataUri = $google2fa->getQRCodeUrl(
            $request->getHttpHost(),
            $user->email,
            $secret
        );


        return view('auth/2fa/enableTwoFactor', [
            'image' => $imageDataUri,
            'secret' => $secret,
            'data' => ['pageName' => 'Security']
        ]);
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        // make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        return view('2fa/disableTwoFactor');
    }

    public function g2fotpcheckenable(Request $request)
    {
        $loggedInUser = auth()->user();
        $key    = $loggedInUser->id . ':' . $request->totp;
        $secret = Crypt::decrypt($loggedInUser->g2f_temp);
        $google2fa = new Google2FA();
        $verify = $google2fa->verifyKey($secret, $request->totp);
        if (!Cache::has($key)) {
            if ($verify == true) {
                Cache::add($key, true, 4);
                $user = User::findOrFail($loggedInUser->id);
                $user->google2fa_secret = $user->g2f_temp;
                $user->g2f_temp = NULL;
                $user->save();

                $status = 200;
                $message = "Google two factor authentication enabled successfully...";
            } else {
                $status = 400;
                $message = "Please check the otp, and try again...";
            }
        } else {
            $status = 500;
            $message = "Used token,Cannot reuse token...";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
}
