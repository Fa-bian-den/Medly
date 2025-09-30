@extends('layouts.app')

@section('content')
<div class="py-6">
  <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-4">
      <h1 class="text-2xl font-bold">Editar paciente</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      @include('patients._form', ['action' => route('patients.update', $patient), 'method' => 'PUT', 'patient' => $patient])
    </div>
  </div>
</div>
@endsection