<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Acceso · CRM Ps-pool</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="h-full bg-gradient-to-br from-slate-900 via-[#0d1b2a] to-slate-800 flex items-center justify-center p-4">

  <div class="w-full max-w-sm">

    {{-- Logo --}}
    <div class="text-center mb-8">
      <img src="{{ asset('jpg/logo_Gesplanet.jpg') }}" alt="Gesplanet"
        class="h-12 mx-auto object-contain brightness-0 invert mb-4" onerror="this.style.display='none'">
      <h1 class="font-head font-bold text-2xl text-white">CRM Comercial</h1>
      <p class="text-slate-400 text-sm mt-1">Ps-pool</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

      {{-- Tabs --}}
      <div class="flex border-b border-slate-100">
        <button id="tab-comercial" onclick="switchTab('comercial')"
          class="flex-1 py-3 text-sm font-semibold text-blue-600 border-b-2 border-blue-600 transition-colors">
          Comercial
        </button>
        <button id="tab-rrhh" onclick="switchTab('rrhh')"
          class="flex-1 py-3 text-sm font-semibold text-slate-400 border-b-2 border-transparent transition-colors hover:text-slate-600">
          Recursos Humanos
        </button>
      </div>

      {{-- Comercial form --}}
      <div id="form-comercial" class="p-8">
        <div class="space-y-4">
          <div>
            <label class="form-label">Usuario</label>
            <input id="inp-comercial" type="text" class="form-input" placeholder="Código comercial"
              autocomplete="username">
          </div>
          <div>
            <label class="form-label">Contraseña</label>
            <input id="inp-password" type="password" class="form-input" placeholder="••••••••"
              autocomplete="current-password">
          </div>
          <div id="error-comercial" class="hidden text-red-600 text-sm bg-red-50 rounded-lg px-3 py-2"></div>
          <button id="btn-login" onclick="loginComercial()"
            class="btn btn-primary w-full justify-center text-base py-3">
            Entrar
          </button>
        </div>
      </div>

      {{-- RRHH form --}}
      <div id="form-rrhh" class="p-8 hidden">
        <div class="flex justify-center mb-5">
          <img src="{{ asset('jpg/logo_gesplanet_recursos_humanos.png') }}" alt="RRHH" class="h-14 object-contain"
            onerror="this.style.display='none'">
        </div>
        <div class="space-y-4">
          <div>
            <label class="form-label">Usuario</label>
            <input id="inp-rrhh-user" type="text" class="form-input" placeholder="Código de acceso"
              autocomplete="username">
          </div>
          <div>
            <label class="form-label">Contraseña</label>
            <input id="inp-rrhh-pass" type="password" class="form-input" placeholder="••••••••"
              autocomplete="current-password">
          </div>
          <div id="error-rrhh" class="hidden text-red-600 text-sm bg-red-50 rounded-lg px-3 py-2"></div>
          <button id="btn-rrhh" onclick="loginRrhh()" class="btn w-full justify-center text-base py-3"
            style="background:#0099ff;color:white;">
            Entrar
          </button>
        </div>
      </div>

    </div>

    <p class="text-center text-slate-500 text-xs mt-6">
      <a href="https://www.info-ges.com" target="_blank" class="hover:text-slate-300 transition-colors">
        By info-ges.com
      </a>
    </p>

  </div>

  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script>
    function switchTab(tab) {
  var isC = tab === 'comercial';
  document.getElementById('form-comercial').classList.toggle('hidden', !isC);
  document.getElementById('form-rrhh').classList.toggle('hidden', isC);
  document.getElementById('tab-comercial').classList.toggle('text-blue-600', isC);
  document.getElementById('tab-comercial').classList.toggle('border-blue-600', isC);
  document.getElementById('tab-comercial').classList.toggle('text-slate-400', !isC);
  document.getElementById('tab-comercial').classList.toggle('border-transparent', !isC);
  document.getElementById('tab-rrhh').classList.toggle('text-blue-600', !isC);
  document.getElementById('tab-rrhh').classList.toggle('border-blue-600', !isC);
  document.getElementById('tab-rrhh').classList.toggle('text-slate-400', isC);
  document.getElementById('tab-rrhh').classList.toggle('border-transparent', isC);
  setTimeout(function() {
    document.getElementById(isC ? 'inp-comercial' : 'inp-rrhh-user').focus();
  }, 50);
}

function setLoading(btn, loading) {
  btn.disabled = loading;
  btn.innerHTML = loading
    ? '<span class="spinner mr-2"></span>Verificando...'
    : btn.dataset.orig;
}

function loginComercial() {
  var btn = document.getElementById('btn-login');
  if (!btn.dataset.orig) btn.dataset.orig = btn.innerHTML;
  var err = document.getElementById('error-comercial');
  err.classList.add('hidden');
  setLoading(btn, true);

  $.post('{{ route("auth.login") }}', {
    _token:   '{{ csrf_token() }}',
    comercial: $('#inp-comercial').val(),
    clave:     $('#inp-password').val()
  })
  .done(function(r) {
    if (r.ok) { window.location = '{{ route("agenda.index") }}'; }
    else { err.textContent = r.mensaje; err.classList.remove('hidden'); setLoading(btn, false); }
  })
  .fail(function() { err.textContent = 'Error de conexión.'; err.classList.remove('hidden'); setLoading(btn, false); });
}

function loginRrhh() {
  var btn = document.getElementById('btn-rrhh');
  if (!btn.dataset.orig) btn.dataset.orig = btn.innerHTML;
  var err = document.getElementById('error-rrhh');
  err.classList.add('hidden');
  setLoading(btn, true);

  $.post('{{ route("auth.login.rrhh") }}', {
    _token:   '{{ csrf_token() }}',
    comercial: $('#inp-rrhh-user').val(),
    clave:     $('#inp-rrhh-pass').val()
  })
  .done(function(r) {
    if (r.ok) { window.location = '{{ route("rrhh.index") }}'; }
    else { err.textContent = r.mensaje; err.classList.remove('hidden'); setLoading(btn, false); }
  })
  .fail(function() { err.textContent = 'Error de conexión.'; err.classList.remove('hidden'); setLoading(btn, false); });
}

// Enter key
['inp-password','inp-comercial'].forEach(function(id) {
  document.getElementById(id).addEventListener('keypress', function(e) { if(e.key==='Enter') loginComercial(); });
});
['inp-rrhh-user','inp-rrhh-pass'].forEach(function(id) {
  document.getElementById(id).addEventListener('keypress', function(e) { if(e.key==='Enter') loginRrhh(); });
});
document.getElementById('inp-comercial').focus();
  </script>
</body>

</html>