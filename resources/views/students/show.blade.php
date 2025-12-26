@extends('layout')

@section('content')
<div class="card mx-auto" style="max-width: 600px;">
    <div class="card-header bg-info text-white">
        <h3>Mon Profil Étudiant</h3>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Nom :</strong> {{ $student->user->name }}</li>
            <li class="list-group-item"><strong>Email :</strong> {{ $student->user->email }}</li>
            <li class="list-group-item"><strong>CNE :</strong> {{ $student->cne }}</li>
            <li class="list-group-item"><strong>Filière :</strong> {{ $student->filiere }}</li>
            <li class="list-group-item"><strong>Date d'inscription :</strong> {{ $student->created_at->format('d/m/Y') }}</li>
        </ul>

        @if(Auth::user()->role === 'admin')
        <div class="mt-3">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
        @endif
    </div>
</div>
@endsection