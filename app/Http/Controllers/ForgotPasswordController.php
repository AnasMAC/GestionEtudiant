<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __construct(protected FirebaseAuth $firebaseAuth) {}

    // 1. Show the Form (This was missing)
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Handle the Form Submission
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            // Firebase handles the email sending and the link!
            $this->firebaseAuth->sendPasswordResetLink($request->email);

            return back()->with('success', 'Un email de réinitialisation a été envoyé (vérifiez vos spams).');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Impossible d\'envoyer l\'email. Vérifiez l\'adresse.']);
        }
    }
}
