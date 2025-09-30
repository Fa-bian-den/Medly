@extends('layouts.app')

@section('content')
<div class="py-6">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold">Pacientes</h1>
      <a href="{{ route('patients.create') }}" class="btn-primary">Nuevo paciente</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-4">
        @if($patients->isEmpty())
          <p>No hay pacientes.</p>
        @else
          <table class="table-auto w-full">
            <thead>
              <tr>
                <th class="text-left p-2">#</th>
                <th class="text-left p-2">Nombre</th>
                <th class="text-left p-2">Email</th>
                <th class="text-left p-2">Teléfono</th>
                <th class="text-left p-2">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($patients as $p)
                <tr class="border-t">
                  <td class="p-2">{{ $p->id }}</td>
                  <td class="p-2">{{ $p->first_name }} {{ $p->last_name }}</td>
                  <td class="p-2">{{ $p->email }}</td>
                  <td class="p-2">{{ $p->phone ?? '-' }}</td>
                  <td class="p-2">
                    <a href="{{ route('patients.show', $p) }}" class="text-sm">Ver</a>
                    <a href="{{ route('patients.edit', $p) }}" class="text-sm ml-2">Editar</a>
                    <form action="{{ route('patients.destroy', $p) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Eliminar paciente?');">
                      @csrf @method('DELETE')
                      <button type="submit" class="text-sm text-red-600">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="mt-4">
            {{ $patients->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection