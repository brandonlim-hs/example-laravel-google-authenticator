<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Google2FA;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Google2FAController extends Controller
{
    use RedirectsUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['login2FA', 'verify2FA']);
    }

    /**
     * Show QR code and secret for user to set up 2FA.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function activate2FA(Request $request)
    {
        // use existing secret key if exist (e.g., error during setup verification)
        // otherwise generate secret key
        $secret = $request->session()->get('google2fa_secret') ?? Google2FA::generateSecretKey();

        // store secret in the session only for the next request
        $request->session()->flash('google2fa_secret', $secret);

        // generate image for QR barcode
        $qrCode = Google2FA::getQRCodeInline(
            config('app.name'),
            $request->user()->email,
            $secret,
            200
        );

        return view('auth.google2fa.activate', [
            'qrCode' => $qrCode,
            'secret' => $secret
        ]);
    }

    /**
     * Verify the 2FA code and save secret.
     * Redirect to 2FA activate if not valid.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function assign2FA(Request $request)
    {
        $verificationCode = $request->get('one_time_password');
        $secret = $request->session()->get('google2fa_secret');
        if (!$secret || !Google2FA::verifyGoogle2FA($secret, $verificationCode)) {
            // store secret in the session only for the next request
            $request->session()->reflash();

            throw ValidationException::withMessages([
                'one_time_password' => [trans('auth.2fa.failed')],
            ]);
        }

        $user = $request->user();
        $user->google2fa_secret = $secret;
        $user->save();
        return view('auth.google2fa.activate');
    }

    /**
     * Deactivate 2FA.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deactivate2FA(Request $request)
    {
        $user = $request->user();

        //make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        return view('auth.google2fa.deactivate');
    }

    /**
     * Show the login with 2FA page.
     *
     * @return \Illuminate\Http\Response
     */
    public function login2FA()
    {
        return view('auth.google2fa.login');
    }

    /**
     * Verify the 2FA code.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function verify2FA(Request $request)
    {
        $userId = $request->session()->pull('user_id');
        $user = User::find($userId);
        $otp = $request->get('one_time_password');

        if (!$user || !Google2FA::verifyGoogle2FA($user->google2fa_secret, $otp)) {
            // store secret in the session only for the next request
            $request->session()->reflash();
            // re-flash request inputs
            $request->flash();

            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ])->redirectTo(route('login'));
        }

        Auth::loginUsingId($userId);
        return redirect()->intended($this->redirectPath());
    }
}
