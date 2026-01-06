@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card login-card">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Connexion</h3>
                    <p class="text-muted small">Accédez à votre espace étudiant ou admin</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="exemple@ensat.ac.ma" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="••••••••" required>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger py-2 text-center border-0 shadow-sm mb-3">
                        <small>{{ $errors->first() }}</small>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm">
                        Se connecter
                    </button>
                </form>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1 text-muted">
                    <span class="mx-2 text-muted small">OU</span>
                    <hr class="flex-grow-1 text-muted">
                </div>

                <button type="button" id="google-login-btn" class="btn btn-white border w-100 btn-lg shadow-sm d-flex align-items-center justify-content-center">
                    <svg style="width: 20px; height: 20px; margin-right: 10px;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="text-muted fw-bold small">Google</span>
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Mot de passe oublié ?</a>
                </div>

            </div>
        </div>
    </div>
</div>

<form id="google-auth-form" action="{{ route('login.google') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id_token" id="id_token">
</form>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
  import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

  // TODO: Replace with your actual Firebase config from the Console
  const firebaseConfig = {
    apiKey: "AIzaSyCp2B890aVWX9L0o2O6hkYXV0ZPBaaYnr0",

    authDomain: "gestionetudiant-f5036.firebaseapp.com",

    projectId: "gestionetudiant-f5036",

    storageBucket: "gestionetudiant-f5036.firebasestorage.app",

    messagingSenderId: "208350647760",

    appId: "1:208350647760:web:0d7408a8b945fcd69bd5d5",

    measurementId: "G-M3WSL9EZEC"

    };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const provider = new GoogleAuthProvider();

  const googleBtn = document.getElementById('google-login-btn');

  googleBtn.addEventListener('click', async () => {
      try {
          const result = await signInWithPopup(auth, provider);
          const user = result.user;

          // Get the ID Token
          const idToken = await user.getIdToken();

          // Submit the hidden form
          document.getElementById('id_token').value = idToken;
          document.getElementById('google-auth-form').submit();

      } catch (error) {
          console.error("Google Sign-In Error:", error);
          alert("Erreur lors de la connexion Google: " + error.message);
      }
  });
</script>
@endsection
