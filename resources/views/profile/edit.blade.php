@extends('layouts.app')

@section('content')
<!-- Incluir CSS específico de perfil -->
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}" />

<div class="container perfil-root">
  <h1 class="perfil-title">Mi perfil</h1>

  @php
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $profile = $user->profile ?? null;
    $role = $user->getRoleNames()->first() ?? 'paciente';
    $doctor = $user->doctorProfile ?? null;
  @endphp

  <div class="card mb-4 perfil-card">
    <div class="card-body">
      <h5 class="card-title">Información básica</h5>

      <dl class="row perfil-dl">
        <dt class="col-sm-3">Nombre</dt>
        <dd class="col-sm-9">{{ $user->first_name }} {{ $user->last_name }}</dd>

        <dt class="col-sm-3">Email</dt>
        <dd class="col-sm-9">{{ $user->email }}</dd>

        <dt class="col-sm-3">Fecha de nacimiento</dt>
        <dd class="col-sm-9">{{ optional($profile)->birthdate ?? '—' }}</dd>

        <dt class="col-sm-3">Cédula / ID</dt>
        <dd class="col-sm-9">{{ optional($profile)->idcard ?? '—' }}</dd>

        <dt class="col-sm-3">Género</dt>
        <dd class="col-sm-9">{{ optional($profile)->gender ?? '—' }}</dd>

        <dt class="col-sm-3">Avatar</dt>
        <dd class="col-sm-9">
          @if($user->avatar)
            <img src="{{ asset('storage/'.$user->avatar) }}" alt="avatar" class="perfil-avatar">
          @else
            —
          @endif
        </dd>
      </dl>
    </div>
  </div>

  @if($role === 'doctor')
  <div class="card mb-4 perfil-card">
    <div class="card-body">
      <h5 class="card-title">Información profesional</h5>
      <dl class="row perfil-dl">
        <dt class="col-sm-3">Carnet MINSA</dt>
        <dd class="col-sm-9">{{ $user->carnet_minsa ?? '—' }}</dd>

        <dt class="col-sm-3">Centro propuesto</dt>
        <dd class="col-sm-9">{{ optional($doctor)->center_id_proposed ? 'Centro #'.optional($doctor)->center_id_proposed : '—' }}</dd>

        <dt class="col-sm-3">Detalles</dt>
        <dd class="col-sm-9">{{ optional($doctor)->professional_details ?? '—' }}</dd>

        <dt class="col-sm-3">Especialidades</dt>
        <dd class="col-sm-9">
          @if(optional($doctor)->specialities)
            {{ optional($doctor)->specialities->pluck('name')->join(', ') }}
          @else
            —
          @endif
        </dd>
      </dl>
    </div>
  </div>
  @endif

  <div class="card perfil-card">
    <div class="card-body">
      <h5 class="card-title">Editar contacto rápido</h5>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
          <label for="phone" class="form-label">Teléfono</label>
          <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                 value="{{ old('phone', optional($profile)->phone ?? $user->phone) }}">
          @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Guardar contacto</button>
      </form>
    </div>
  </div>
</div>
@endsection