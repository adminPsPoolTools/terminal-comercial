<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'CRM Comercial') · Ps-pool</title>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            head: ['Outfit', 'ui-sans-serif', 'system-ui'],
            body: ['DM Sans', 'ui-sans-serif', 'system-ui'],
          },
          colors: {
            sidebar: {
              DEFAULT: '#0d1b2a',
              hover:   '#1b2e44',
              active:  '#1d4ed8',
              border:  'rgba(255,255,255,0.07)',
            }
          }
        }
      }
    }
  </script>

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  @stack('styles')
</head>

<body class="h-full bg-slate-100 text-slate-900 font-body antialiased">

{{-- ══════════════════════════════════════════════════════
     APP SHELL
══════════════════════════════════════════════════════ --}}
<div class="flex h-full">

  {{-- ══ SIDEBAR ════════════════════════════════════════ --}}
  <aside id="sidebar"
         class="fixed top-0 left-0 bottom-0 w-64 bg-[#0d1b2a] flex flex-col overflow-y-auto overflow-x-hidden z-50">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-white/[.07] shrink-0">
      <img src="{{ asset('jpg/logo_Gesplanet.jpg') }}" alt="Gesplanet"
           class="h-8 w-auto object-contain brightness-0 invert opacity-90"
           onerror="this.style.display='none'">
      <div>
        <div class="font-head font-bold text-sm text-slate-50 leading-tight">Ps-pool</div>
        <div class="text-[10px] text-slate-500 uppercase tracking-widest">CRM Comercial</div>
      </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">

      {{-- Sección Principal --}}
      <p class="px-3 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-600">Principal</p>

      @php
        $nav = [
          ['route'=>'agenda.index',      'label'=>'Agenda',         'icon'=>'calendar'],
          ['route'=>'clientes.index',    'label'=>'Clientes',       'icon'=>'users'],
          ['route'=>'articulos.index',   'label'=>'Artículos',      'icon'=>'package'],
          ['route'=>'presupuestos.index','label'=>'Presupuestos',   'icon'=>'file-text'],
          ['route'=>'expedientes.index', 'label'=>'Expedientes',    'icon'=>'folder'],
          ['route'=>'gastos.index',      'label'=>'Gastos',         'icon'=>'dollar'],
          ['route'=>'pedidos.index',     'label'=>'Pedidos',        'icon'=>'shopping-cart'],
        ];
      @endphp

      @foreach($nav as $item)
        <a href="{{ route($item['route']) }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium hover:text-white {{ request()->routeIs($item['route']) ? 'active' : '' }}"
           data-route="{{ $item['route'] }}">
          @include('partials.icon', ['name' => $item['icon']])
          {{ $item['label'] }}
          @if($item['route'] === 'agenda.index')
            <span id="badge-alarma"   class="ml-auto hidden text-[10px] font-bold bg-red-500 text-white rounded-full px-2 py-0.5">0</span>
            <span id="badge-recorda"  class="hidden text-[10px] font-bold bg-amber-400 text-slate-900 rounded-full px-2 py-0.5">0</span>
          @endif
        </a>
      @endforeach

      {{-- Sección Análisis --}}
      <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-600">Análisis</p>

      @php
        $navAnalisis = [
          ['route'=>'listados.index',    'label'=>'Listados',       'icon'=>'list'],
          ['route'=>'objetivos.index',   'label'=>'Objetivos',      'icon'=>'target'],
          ['route'=>'incidencias.index', 'label'=>'Incidencias SAT','icon'=>'alert-triangle'],
        ];
      @endphp

      @foreach($navAnalisis as $item)
        <a href="{{ route($item['route']) }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium hover:text-white {{ request()->routeIs($item['route']) ? 'active' : '' }}"
           data-route="{{ $item['route'] }}">
          @include('partials.icon', ['name' => $item['icon']])
          {{ $item['label'] }}
        </a>
      @endforeach

      {{-- Sección Otros --}}
      <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-600">Otros</p>

      <a href="{{ route('rrhh.index') }}"
         class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium hover:text-white {{ request()->routeIs('rrhh.index') ? 'active' : '' }}">
        @include('partials.icon', ['name' => 'briefcase'])
        Recursos Humanos
      </a>

      {{-- Agenda técnico (no protegida por auth, referencia fija) --}}
      <a href="{{ route('agenda.index') }}?tecnico=1"
         class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium hover:text-white">
        @include('partials.icon', ['name' => 'clock'])
        Agenda Técnico
        <span id="badge-alarma-t" class="ml-auto hidden text-[10px] font-bold bg-red-500 text-white rounded-full px-2 py-0.5">0</span>
      </a>

    </nav>

    {{-- User footer --}}
    <div class="shrink-0 p-3 border-t border-white/[.07]">
      <div class="flex items-center gap-3 px-2.5 py-2 rounded-lg bg-white/[.04]">
        <div id="user-avatar"
             class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-violet-600
                    flex items-center justify-center text-white text-xs font-bold font-head shrink-0">
          {{ strtoupper(substr(session('comercial_nombre', '?'), 0, 1)) }}
        </div>
        <div class="min-w-0">
          <div class="text-xs font-semibold text-slate-100 truncate">
            {{ session('comercial_nombre', 'No identificado') }}
          </div>
          <div class="text-[10px] text-slate-500">Comercial</div>
        </div>
        {{-- Logout --}}
        <form method="POST" action="{{ route('auth.logout') }}" class="ml-auto">
          @csrf
          <button type="submit" title="Cerrar sesión"
                  class="text-slate-600 hover:text-red-400 transition-colors">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </button>
        </form>
      </div>
    </div>

  </aside>

  {{-- Overlay móvil --}}
  <div id="sidebar-overlay" onclick="closeSidebar()"></div>

  {{-- ══ MAIN ══════════════════════════════════════════════ --}}
  <div id="main-content" class="flex-1 flex flex-col min-h-screen min-w-0 lg:ml-64">

    {{-- Topbar --}}
    <header class="sticky top-0 z-30 h-14 bg-white border-b border-slate-200 shadow-sm flex items-center gap-3 px-4 lg:px-6 shrink-0">

      {{-- Hamburger --}}
      <button id="hamburger" onclick="toggleSidebar()"
              class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
              aria-label="Abrir menú">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>

      {{-- Breadcrumb / Title --}}
      <div class="flex items-center gap-2 flex-1 min-w-0">
        <span class="font-head font-semibold text-slate-800 text-base truncate">
          @yield('page-title', 'Módulo Comercial')
        </span>
      </div>

      {{-- Right actions --}}
      <div class="flex items-center gap-3">
        <span class="hidden sm:block text-xs text-slate-400 font-medium">
          {{ session('comercial_nombre') }}
        </span>
      </div>
    </header>

    {{-- Content --}}
    <main id="content-area" class="flex-1">
      @yield('content')
    </main>

  </div>{{-- /main-content --}}

</div>{{-- /shell --}}

{{-- ══ jQuery ══════════════════════════════════════════════════ --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>

{{-- ══ Global JS ═══════════════════════════════════════════════ --}}
<script>
// ── Sidebar mobile ──────────────────────────────────────────
function toggleSidebar() {
  var sb = document.getElementById('sidebar');
  var ov = document.getElementById('sidebar-overlay');
  var open = sb.classList.toggle('open');
  ov.classList.toggle('active', open);
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('active');
}
// close on Escape
document.addEventListener('keydown', function(e) { if(e.key==='Escape') closeSidebar(); });

// ── CSRF header for all AJAX ────────────────────────────────
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

// ── Alarm polling ───────────────────────────────────────────
@if(session()->has('comercial_id'))
function pollAlarmas() {
  $.getJSON('{{ route("auth.alarmas") }}', function(d) {
    var ba = $('#badge-alarma'), br = $('#badge-recorda');
    d.alarmas > 0       ? ba.text(d.alarmas).removeClass('hidden')       : ba.addClass('hidden');
    d.recordatorios > 0 ? br.text(d.recordatorios).removeClass('hidden') : br.addClass('hidden');
  });
  $.getJSON('{{ route("auth.alarmas.tecnico") }}', function(d) {
    var bt = $('#badge-alarma-t');
    d.alarmas > 0 ? bt.text(d.alarmas).removeClass('hidden') : bt.addClass('hidden');
  });
}
pollAlarmas();
setInterval(pollAlarmas, 15000);
@endif
</script>

@stack('scripts')

<script>
/* ── initCrmTable: search + sort + pagination for any tab table ── */
window.initCrmTable = function(cid) {
  var $c = $('#' + cid);
  if (!$c.length) return;
  var $tbody = $c.find('table tbody');
  var all = $tbody.find('tr').toArray();
  var filtered = all.slice();
  var page = 1, ps = 10, sc = -1, asc = true;
  var $search = $c.find('.tab-search');
  var $count  = $c.find('.tab-count');
  var $prev   = $c.find('.btn-tab-prev');
  var $next   = $c.find('.btn-tab-next');
  var $info   = $c.find('.tab-page-info');

  function cellTxt(r, i) {
    return $(r).find('td').eq(i).text().trim().toLowerCase();
  }
  function render() {
    var n = filtered.length;
    var pages = Math.max(1, Math.ceil(n / ps));
    if (page > pages) page = pages;
    $tbody.empty();
    filtered.slice((page-1)*ps, page*ps).forEach(function(r){ $tbody.append(r); });
    $count.text(n + (n===1?' registro':' registros'));
    $info.text(page + ' / ' + pages);
    $prev.prop('disabled', page <= 1);
    $next.prop('disabled', page >= pages);
    $c.find('th.srt').each(function(i){
      $(this).find('.sa').text(i===sc ? (asc?'↑':'↓') : '↕');
    });
  }
  $search.on('input', function(){
    var q = this.value.toLowerCase().trim();
    filtered = q ? all.filter(function(r){ return $(r).text().toLowerCase().indexOf(q)!==-1; }) : all.slice();
    page = 1; render();
  });
  $prev.on('click', function(){ if(page>1){page--;render();} });
  $next.on('click', function(){ page++;render(); });
  function parseDateVal(s) {
    var m = s.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
    if (m) return m[3] + m[2].padStart(2,'0') + m[1].padStart(2,'0');
    if (/^\d{4}-\d{2}-\d{2}/.test(s)) return s.substring(0, 10).replace(/-/g,'');
    return null;
  }
  $c.find('th.srt').each(function(i){
    $(this).css('cursor','pointer').on('click', function(){
      if(sc===i) asc=!asc; else{sc=i;asc=true;}
      filtered.sort(function(a,b){
        var ta=cellTxt(a,i), tb=cellTxt(b,i);
        var da=parseDateVal(ta), db=parseDateVal(tb);
        if(da&&db) return asc?da.localeCompare(db):db.localeCompare(da);
        var na=parseFloat(ta.replace(/\./g,'').replace(',','.')),
            nb=parseFloat(tb.replace(/\./g,'').replace(',','.'));
        if(!isNaN(na)&&!isNaN(nb)) return asc?na-nb:nb-na;
        return asc?ta.localeCompare(tb,'es'):tb.localeCompare(ta,'es');
      });
      page=1; render();
    });
  });
  render();
};
</script>

</body>
</html>
