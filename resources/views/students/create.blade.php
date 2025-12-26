@extends('layout')

@section('content')
<div class="card">
    <div class="card-header">Ajouter un Étudiant</div>
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nom Complet</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email (Login)</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mot de Passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>CNE</label>
                    <input type="text" name="cne" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Filière</label>
                    <select name="filiere" class="form-control">
                        <option value="GINF">Génie Informatique</option>
                        <option value="GIND">Génie Industriel</option>
                        <option value="GSTR">Télécoms</option>
                        <option value="AP">Année Préparatoire</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-success">Enregistrer</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection