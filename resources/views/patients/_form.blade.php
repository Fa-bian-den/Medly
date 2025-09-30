@php
  $values = old() ?: (isset($patient) ? $patient->toArray() : []);
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
  @csrf
  @if(in_array($method, ['PUT','PATCH'])) @method($method) @endif

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label for="first_name" class="block font-semibold">Nombre</label>
      <input id="first_name" name="first_name" value="{{ $values['first_name'] ?? '' }}" class="input" required />
      @error('first_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="last_name" class="block font-semibold">Apellido</label>
      <input id="last_name" name="last_name" value="{{ $values['last_name'] ?? '' }}" class="input" required />
      @error('last_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="email" class="block font-semibold">Email</label>
      <input id="email" name="email" type="email" value="{{ $values['email'] ?? '' }}" class="input" required />
      @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="phone" class="block font-semibold">Teléfono</label>
      <input id="phone" name="phone" value="{{ $values['phone'] ?? '' }}" class="input" />
      @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="birthdate" class="block font-semibold">Fecha de nacimiento</label>
      <input id="birthdate" type="date" name="birthdate" value="{{ $values['birthdate'] ?? '' }}" class="input" />
      @error('birthdate') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="gender" class="block font-semibold">Género</label>
      <select id="gender" name="gender" class="input">
        <option value="">Seleccione</option>
        <option value="male" {{ ( $values['gender'] ?? '' ) === 'male' ? 'selected' : '' }}>Masculino</option>
        <option value="female" {{ ( $values['gender'] ?? '' ) === 'female' ? 'selected' : '' }}>Femenino</option>
        <option value="other" {{ ( $values['gender'] ?? '' ) === 'other' ? 'selected' : '' }}>Otro</option>
      </select>
      @error('gender') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="avatar" class="block font-semibold">Avatar</label>
      <input id="avatar" type="file" name="avatar" class="input" />
      @error('avatar') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="idcard" class="block font-semibold">ID / Cédula</label>
      <input id="idcard" name="idcard" value="{{ $values['idcard'] ?? '' }}" class="input" />
      @error('idcard') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="mt-4 flex justify-end">
    <a href="{{ route('patients.index') }}" class="btn-secondary mr-2">Cancelar</a>
    <button type="submit" class="btn-primary">{{ in_array($method, ['PUT','PATCH']) ? 'Actualizar' : 'Crear' }}</button>
  </div>
</form>