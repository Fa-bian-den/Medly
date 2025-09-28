@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Completa tu perfil</h2>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
  @endif

  <form action="{{ route('profile.setup.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="first_name">Nombre</label>
      <input id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="last_name">Apellido</label>
      <input id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="phone">Teléfono</label>
      <input id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
    </div>

    <div class="mb-3">
      <label for="birthdate">Fecha de nacimiento</label>
      <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate', optional($user->profile)->birthdate) }}" class="form-control">
    </div>

    <div class="mb-3">
      <label for="gender">Género</label>
      <select id="gender" name="gender" class="form-control">
        <option value="" {{ old('gender', optional($user->profile)->gender) === null ? 'selected' : '' }}>Seleccione</option>
        <option value="male" {{ old('gender', optional($user->profile)->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
        <option value="female" {{ old('gender', optional($user->profile)->gender) === 'female' ? 'selected' : '' }}>Femenino</option>
      </select>
    </div>
 
    <div class="mb-3">
      <label for="avatar">Avatar</label>
      <input id="avatar" type="file" name="avatar" accept="image/*" class="form-control">
    </div>

    <div class="mb-3">
      <label for="idcard">ID (cédula)</label>
      <input id="idcard" name="idcard" value="{{ old('idcard', optional($user->profile)->idcard) }}" class="form-control">
    </div>

    <div class="mb-3">
      <label for="emergency_contact_name">Contacto de emergencia (nombre)</label>
      <input id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', optional($user->profile)->emergency_contact_name) }}" class="form-control">
    </div>

    <div class="mb-3">
      <label for="emergency_contact_phone">Contacto de emergencia (teléfono)</label>
      <input id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', optional($user->profile)->emergency_contact_phone) }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Guardar y continuar</button>
  </form>
</div>
@endsection