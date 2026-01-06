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
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'id_token' => 'required',
        ]);

        try {
            // 1. Verify the ID Token sent from the frontend
            // The verifyIdToken method throws an exception if the token is invalid
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($request->id_token);

            $uid = $verifiedIdToken->claims()->get('sub');
            $email = $verifiedIdToken->claims()->get('email');

            // 2. Find the local user in MariaDB
            $user = User::where('firebase_uid', $uid)->first();

            if (!$user) {
                // Fallback: Check by email (if they registered manually before)
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Sync the UID so next time they are found immediately
                    $user->update(['firebase_uid' => $uid]);
                } else {
                    // OPTIONAL: If user doesn't exist locally, do you want to auto-register them?
                    // If not, return an error.
                    return back()->withErrors(['email' => 'No local account found for this Google user.']);

                    /* // OR Auto-register logic:
                    $user = User::create([
                        'name' => $verifiedIdToken->claims()->get('name'),
                        'email' => $email,
                        'firebase_uid' => $uid,
                        'password' => bcrypt(str_random(16)), // Random password
                        'role' => 'student' // Default role
                    ]);
                    */
                }
            }

            // 3. Log them in manually in Laravel
            Auth::login($user);
            $request->session()->regenerate();

            // 4. Redirect based on Role
            if ($user->role === 'admin') {
                return redirect()->route('students.index');
            }
            // Ensure the user has a student record, or handle the null case
            if ($user->student) {
                return redirect()->route('students.show', $user->student->id);
            }

            return redirect('/'); // Default fallback

        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            return back()->withErrors(['email' => 'Google login failed: Invalid Token.']);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'An error occurred during login.']);
        }
    }
}
