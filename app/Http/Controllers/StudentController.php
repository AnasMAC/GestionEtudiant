<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * Admin: Sees all students.
     * Student: Redirected to their own profile.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            // If logged in as student, redirect to their specific profile
            return redirect()->route('students.show', Auth::user()->student->id);
        }

        $students = Student::with('user')->get(); // Get students with their user info
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource (Admin only).
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     * This creates BOTH a User and a Student record.
     */
    // Inject FirebaseAuth into the controller
    public function __construct(protected FirebaseAuth $firebaseAuth) {}
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        // 1. Validate all data (Personal + Academic)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'cne' => 'required|string|unique:students',
            'filiere' => 'required|string',
        ]);

        // 2. Create the User Login first
        try {
            // 1. Create User in Firebase
            $createdUser = $this->firebaseAuth->createUser([
                'email' => $request->email,
                'password' => $request->password,
                'displayName' => $request->name,
            ]);

            // 2. Create User in MariaDB (Linked via UID)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'firebase_uid' => $createdUser->uid, // <--- The Link!
                'role' => 'student',
                'password' => null, // We don't save passwords in MariaDB anymore!
            ]);

            // 3. Create Student Profile
            Student::create([
                'user_id' => $user->id,
                'cne' => $request->cne,
                'filiere' => $request->filiere,
            ]);

            return redirect()->route('students.index')->with('success', 'Étudiant créé (Firebase + MariaDB) !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur Firebase: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource (The Profile).
     */
    public function show(Student $student)
    {
        // Security: A student can only view THEIR OWN profile
        if (Auth::user()->role !== 'admin' && Auth::user()->student->id !== $student->id) {
            abort(403, 'Accès interdit');
        }

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource (Admin only).
     */
    public function edit(Student $student)
    {
        $this->authorizeAdmin();
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'cne' => 'required|string|unique:students,cne,' . $student->id,
            'filiere' => 'required|string',
        ]);

        // Update User Table
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Student Table
        $student->update([
            'cne' => $request->cne,
            'filiere' => $request->filiere,
        ]);

        return redirect()->route('students.index')->with('success', 'Étudiant mis à jour');
    }

    /**
     * Remove the specified resource from storage (Admin only).
     */
    public function destroy(Student $student)
    {
        $this->authorizeAdmin();

        // Deleting the User automatically deletes the Student (because of onDelete cascade in migration)
        $student->user->delete();

        return redirect()->route('students.index')->with('success', 'Étudiant supprimé');
    }

    /**
     * Helper to block non-admins
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Seul l\'administrateur peut effectuer cette action.');
        }
    }
}
