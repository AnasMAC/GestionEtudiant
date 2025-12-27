@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3">

            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h4 class="fw-bold text-primary">Modifier l'étudiant</h4>
                <p class="text-muted small">Mettez à jour les informations académiques ou personnelles.</p>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Informations Personnelles</h6>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nom Complet</label>
                        <input type="text" name="name" class="form-control bg-light border-0" value="{{ $student->user->name }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Email (Login)</label>
                        <input type="email" name="email" class="form-control bg-light border-0" value="{{ $student->user->email }}" required>
                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Dossier Académique</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">CNE</label>
                            <input type="text" name="cne" class="form-control bg-light border-0" value="{{ $student->cne }}" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold">Filière</label>
                            <select name="filiere" class="form-select bg-light border-0">
                                <option value="GINF" {{ $student->filiere == 'GINF' ? 'selected' : '' }}>Génie Informatique</option>
                                <option value="GIND" {{ $student->filiere == 'GIND' ? 'selected' : '' }}>Génie Industriel</option>
                                <option value="GSTR" {{ $student->filiere == 'GSTR' ? 'selected' : '' }}>Télécoms</option>
                                <option value="AP" {{ $student->filiere == 'AP' ? 'selected' : '' }}>Année Préparatoire</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('students.index') }}" class="btn btn-light text-muted">Annuler</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold text-white shadow-sm">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection