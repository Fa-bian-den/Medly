@extends('layouts.guest')

@section('content')
<div class="setup-root">
  <div class="setup-card">
    <header class="setup-header">
      <h1 class="setup-title">Bienvenido a {{ config('app.name',) }}</h1>
      <p class="setup-sub">Completa tus datos para finalizar el registro y acceder al panel.</p>
    </header>

    @if($errors->any())
      <div class="alert alert-danger" role="alert">
        <ul>
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('profile.setup.store') }}" method="POST" enctype="multipart/form-data" class="setup-form" novalidate>
      @csrf

      <div class="form-grid">
        <div class="form-field">
          <label for="first_name">Nombre</label>
          <input id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required />
        </div>

        <div class="form-field">
          <label for="last_name">Apellido</label>
          <input id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required />
        </div>

        <div class="form-field">
          <label for="phone">Teléfono</label>
          <input id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" />
        </div>

        <div class="form-field">
          <label for="birthdate">Fecha de nacimiento</label>
          <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate', optional($user->profile)->birthdate) }}" />
        </div>

        <div class="form-field full">
          <label for="gender">Género</label>
          <select id="gender" name="gender">
            <option value="" {{ old('gender', optional($user->profile)->gender) === null ? 'selected' : '' }}>Seleccione</option>
            <option value="male" {{ old('gender', optional($user->profile)->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
            <option value="female" {{ old('gender', optional($user->profile)->gender) === 'female' ? 'selected' : '' }}>Femenino</option>
          </select>
        </div>

        <div class="form-field">
          <label for="avatar">Avatar</label>
          <input id="avatar" type="file" name="avatar" accept="image/*" />
        </div>

        <div class="form-field">
          <label for="idcard">ID (cédula)</label>
          <input id="idcard" name="idcard" value="{{ old('idcard', optional($user->profile)->idcard) }}" />
        </div>

        <div class="form-field">
          <label for="emergency_contact_name">Contacto de emergencia (nombre)</label>
          <input id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', optional($user->profile)->emergency_contact_name) }}" />
        </div>

        <div class="form-field">
          <label for="emergency_contact_phone">Contacto de emergencia (teléfono)</label>
          <input id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', optional($user->profile)->emergency_contact_phone) }}" />
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">Guardar y continuar</button>
      </div>
    </form>
  </div>
</div>
@endsection