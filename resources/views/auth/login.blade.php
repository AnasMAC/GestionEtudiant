@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card mt-5">
            <div class="card-header text-center bg-light">
                <h4>Connexion</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    @if($errors->any())
                    <div class="text-danger mb-3">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection