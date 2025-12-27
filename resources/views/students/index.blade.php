@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Gestion des Étudiants</h2>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau
    </a>
</div>

<div class="table-container">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>CNE</th>
                <th>Étudiant</th>
                <th>Contact</th>
                <th>Filière</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td class="fw-bold text-primary">{{ $student->cne }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light text-primary d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px; font-weight:bold;">
                            {{ substr($student->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $student->user->name }}</div>
                            <small class="text-muted">Inscrit le {{ $student->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </td>
                <td>{{ $student->user->email }}</td>
                <td><span class="badge bg-info text-dark">{{ $student->filiere }}</span></td>
                <td class="text-end">
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-light btn-sm text-primary"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-light btn-sm text-warning"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-light btn-sm text-danger" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection