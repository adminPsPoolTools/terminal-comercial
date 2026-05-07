@extends('layouts.app')
@section('title', 'Recursos Humanos')
@section('page-title', 'Recursos Humanos')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
  <div class="w-full max-w-sm">

    <div class="crm-card overflow-hidden">

      {{-- Header --}}
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center">
        <img src="{{ asset('jpg/logo_gesplanet_recursos_humanos.png') }}" alt="RRHH"
             class="h-14 mx-auto object-contain mb-3"
             onerror="this.style.display='none'">
        <h2 class="font-head font-bold text-lg text-white">Recursos Humanos</h2>
        <p class="text-blue-200 text-sm mt-1">Acceso restringido</p>
      </div>

      {{-- Form --}}
      <div class="p-6 space-y-4">
        <div>
          <label class="form-label">Usuario</label>
          <input id="inp-user" type="text" class="form-input" placeholder="Código de acceso"
                 autocomplete="username">
        </div>
        <div>
          <label class="form-label">Contraseña</label>
          <input id="inp-pass" type="password" class="form-input" placeholder="••••••••"
                 autocomplete="current-password">
        </div>
        <div id="err-rrhh" class="hidden text-red-600 text-sm bg-red-50 rounded-lg px-3 py-2"></div>
        <button id="btn-entrar" onclick="loginRrhh()"
                class="btn w-full justify-center py-3 text-base font-semibold"
                style="background:#0099ff;color:white;border:none;">
          Entrar
        </button>
        <div class="text-center">
          <a href="{{ route('agenda.index') }}" class="text-sm text-slate-400 hover:text-slate-600 transition-colors">
            ← Volver al CRM
          </a>
        </div>
      </div>

    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
function loginRrhh() {
  var btn = document.getElementById('btn-entrar');
  var err = document.getElementById('err-rrhh');
  err.classList.add('hidden');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner mr-2" style="border-top-color:white"></span>Verificando...';

  $.post('{{ route("auth.login.rrhh") }}', {
    _token:   '{{ csrf_token() }}',
    comercial: $('#inp-user').val(),
    clave:     $('#inp-pass').val()
  })
  .done(function(r) {
    if (r.ok) { window.location.reload(); }
    else { err.textContent = r.mensaje; err.classList.remove('hidden'); btn.disabled = false; btn.innerHTML = 'Entrar'; }
  })
  .fail(function() { err.textContent = 'Error de conexión.'; err.classList.remove('hidden'); btn.disabled = false; btn.innerHTML = 'Entrar'; });
}
$('#inp-pass').keypress(function(e){ if(e.which===13) loginRrhh(); });
$('#inp-user').keypress(function(e){ if(e.which===13) loginRrhh(); });
document.getElementById('inp-user').focus();
</script>
@endpush
