<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function index(Request $request):View |RedirectResponse
    {
        if($request->user()->hasVerifiedEmail()){
            return redirect()->route('dashboard');
        }
        return view('auth.verify-email');
    }
    public function verify(EmailVerificationRequest $request):RedirectResponse
    {
        $request->fulfill();
        return redirect()->intended('dashboard');

    }
    public function resend(Request $request):RedirectResponse
    {
        if($request->user()->hasVerifiedEmail()){
            return redirect()->route('dashboard');
        }
        $request->user()->sendEmailVerificationNotification();
       return back()->with('status', 'verification-link-sent');

    }
}
