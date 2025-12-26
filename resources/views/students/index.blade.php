@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Liste des Étudiants</h1>
    <a href="{{ route('students.create') }}" class="btn btn-success">+ Nouvel Étudiant</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>CNE</th>
            <th>Nom Complet</th>
            <th>Email</th>
            <th>Filière</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->cne }}</td>
            <td>{{ $student->user->name }}</td>
            <td>{{ $student->user->email }}</td>
            <td>{{ $student->filiere }}</td>
            <td>
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm text-white">Voir</a>

                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Modifier</a>

                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection