@extends('layouts.app')

@section('content')
<div class="container">
  <div class="card mx-auto" style="max-width:520px;">
    <div class="card-body text-center">
      <h2>Verifica tu correo</h2>
      @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">Se ha enviado un nuevo enlace de verificación.</div>
      @endif

      <p>Antes de continuar, comprueba tu correo y haz clic en el enlace que te enviamos. Si no recibiste el correo, solicita uno nuevo.</p>

      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">RESEND VERIFICATION EMAIL</button>
      </form>

      <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-link">Log Out</button>
      </form>
    </div>
  </div>
</div>
@endsection