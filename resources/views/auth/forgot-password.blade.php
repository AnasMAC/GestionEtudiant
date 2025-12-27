@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card login-card">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Réinitialisation</h3>
                    <p class="text-muted small">Entrez votre email pour recevoir le lien</p>
                </div>

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label text-muted small">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="exemple@ensat.ac.ma" required>
                    </div>

                    @if (session('success'))
                    <div class="alert alert-success py-2 text-center border-0 shadow-sm mb-3">
                        <small>{{ session('success') }}</small>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger py-2 text-center border-0 shadow-sm mb-3">
                        <small>{{ $errors->first() }}</small>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm">
                        Envoyer le lien
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="small text-decoration-none text-muted">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection