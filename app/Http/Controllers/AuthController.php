<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class AuthController extends Controller
{


    public function __construct(protected FirebaseAuth $firebaseAuth) {}
    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login Logic
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // 1. Verify Password with Firebase
            $signInResult = $this->firebaseAuth->signInWithEmailAndPassword($request->email, $request->password);

            // 2. Get the UID from Firebase
            $uid = $signInResult->firebaseUserId();

            // 3. Find the local user in MariaDB
            $user = User::where('firebase_uid', $uid)->first();

            if (!$user) {
                // Fallback: Try to find by email if UID is missing (legacy users)
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    $user->update(['firebase_uid' => $uid]); // Sync them
                } else {
                    return back()->withErrors(['email' => 'Utilisateur introuvable dans la base locale.']);
                }
            }

            // 4. Log them in manually in Laravel
            Auth::login($user);
            $request->session()->regenerate();

            // 5. Redirect based on Role
            if ($user->role === 'admin') {
                return redirect()->route('students.index');
            }
            return redirect()->route('students.show', $user->student->id);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Mot de passe incorrect (Validé par Firebase).']);
        }
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
