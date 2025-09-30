@extends('layouts.app')

@section('content')
<div class="py-6">
  <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Paciente #{{ $patient->id }}</h2>

      <dl class="grid grid-cols-2 gap-4">
        <div>
          <dt class="font-semibold">Nombre</dt>
          <dd>{{ $patient->first_name }} {{ $patient->last_name }}</dd>
        </div>

        <div>
          <dt class="font-semibold">Email</dt>
          <dd>{{ $patient->email }}</dd>
        </div>

        <div>
          <dt class="font-semibold">Teléfono</dt>
          <dd>{{ $patient->phone ?? '-' }}</dd>
        </div>

        <div>
          <dt class="font-semibold">Fecha de nacimiento</dt>
          <dd>{{ optional($patient->birthdate)->format('Y-m-d') ?? '-' }}</dd>
        </div>

        <div>
          <dt class="font-semibold">Género</dt>
          <dd>{{ $patient->gender ?? '-' }}</dd>
        </div>

        <div>
          <dt class="font-semibold">ID</dt>
          <dd>{{ $patient->idcard ?? '-' }}</dd>
        </div>
      </dl>

      <div class="mt-6">
        <a href="{{ route('patients.edit', $patient) }}" class="btn-primary">Editar</a>
        <a href="{{ route('patients.index') }}" class="btn-secondary ml-2">Volver</a>
      </div>
    </div>
  </div>
</div>
@endsection