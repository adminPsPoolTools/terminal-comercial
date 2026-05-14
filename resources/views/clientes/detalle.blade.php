@extends('layouts.app')
@section('title', 'Ficha Cliente')
@section('page-title', $cliente->DESCRIPCION ?? 'Cliente')

@section('content')
<div class="space-y-5 max-w-6xl">

  {{-- Header card --}}
  <div class="crm-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-head font-bold shrink-0">
        {{ strtoupper(substr($cliente->DESCRIPCION ?? '?', 0, 2)) }}
      </div>
      <div class="flex-1 min-w-0">
        <h1 class="font-head font-bold text-xl text-slate-900 leading-tight">
          {{ $cliente->DESCRIPCION ?? '—' }}
        </h1>
        <p class="text-slate-400 text-sm mt-0.5">
          Código: <span class="font-mono font-semibold text-slate-600">{{ $cliente->CODIGO ?? $codigo }}</span>
          @if($cliente->DESCRIPCIONCATEGORIA ?? false)
            · <span class="badge badge-blue">{{ $cliente->DESCRIPCIONCATEGORIA }}</span>
          @endif
          @if(($cliente->BLOQUEADO ?? '') === 'S')
            · <span class="badge badge-red">Bloqueado</span>
          @endif
        </p>
        @if(!empty($cliente->COMENTARIO_ALERTA_COMERCIAL))
          <div class="mt-2 flex items-start gap-2 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" class="shrink-0 mt-0.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <p class="text-red-700 text-sm font-medium">{{ $cliente->COMENTARIO_ALERTA_COMERCIAL }}</p>
          </div>
        @endif
      </div>
      <div class="flex gap-2 shrink-0">
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
      </div>
    </div>
  </div>

  {{-- Info grid --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Datos contacto --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100 uppercase tracking-wide">Contacto</h3>
      <dl class="space-y-2.5 text-sm">
        @foreach([
          ['Dirección',  $cliente->DIRECCION ?? ''],
          ['CP / Pobl.', trim(($cliente->CP ?? '').' '.($cliente->POBLACION ?? ''))],
          ['Comarca',    $cliente->COMARCA ?? ''],
          ['Provincia',  $cliente->PROVINCIA ?? ''],
          ['País',       $cliente->PAIS ?? ''],
          ['Tel. fijo',  $cliente->TELEFONOFIJO ?? ''],
          ['Tel. móvil', $cliente->TELEFONOMOVIL ?? ''],
          ['Fax',        $cliente->FAX ?? ''],
          ['Email',      $cliente->CORREO ?? ''],
          ['Web',        $cliente->WEB ?? ''],
        ] as [$label, $value])
          @if($value)
          <div class="flex gap-3">
            <dt class="w-28 shrink-0 text-slate-400 font-medium">{{ $label }}</dt>
            <dd class="text-slate-700 break-all">
              @if($label === 'Email') <a href="mailto:{{ $value }}" class="text-blue-600 hover:underline">{{ $value }}</a>
              @elseif($label === 'Web') <a href="{{ $value }}" target="_blank" class="text-blue-600 hover:underline">{{ $value }}</a>
              @else {{ $value }}
              @endif
            </dd>
          </div>
          @endif
        @endforeach
      </dl>
    </div>

    {{-- Datos comerciales --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100 uppercase tracking-wide">Comercial</h3>
      <dl class="space-y-2.5 text-sm">
        @foreach([
          ['Vendedor',   $cliente->USUARIO_ALTA ?? ''],
          ['Categoría',  $cliente->DESCRIPCIONCATEGORIA ?? ''],
          ['Tipo',       $cliente->DESCRIPCIONTIPO ?? ''],
          ['CIF / NIF',  $cliente->CIF ?? ''],
          ['Riesgo',     $cliente->RIESGO ?? ''],
          ['F. alta',    $cliente->FECHA_ALTA ?? ''],
          ['Bloqueado',  ($cliente->BLOQUEADO ?? '') === 'S' ? 'Sí' : ''],
        ] as [$label, $value])
          @if($value)
          <div class="flex gap-3">
            <dt class="w-28 shrink-0 text-slate-400 font-medium">{{ $label }}</dt>
            <dd class="text-slate-700">{{ $value }}</dd>
          </div>
          @endif
        @endforeach
      </dl>
    </div>

    {{-- Datos fiscales --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100 uppercase tracking-wide">Fiscal / Financiero</h3>
      <dl class="space-y-2.5 text-sm">
        @foreach([
          ['Forma pago', $cliente->FORMADEPAGO ?? ''],
          ['Banco',      $cliente->BANCO ?? ''],
          ['Tarifa',     $cliente->TARIFA ?? ''],
          ['Dto. gral.', $cliente->DTO_GENERAL ?? ''],
          ['Impuesto',   $cliente->IMPUESTO ?? ''],
          ['Agencia T.', $cliente->AGENCIAT ?? ''],
          ['No crédito', ($cliente->NO_CREDITO ?? '') === 'S' ? 'Sí' : ''],
        ] as [$label, $value])
          @if($value)
          <div class="flex gap-3">
            <dt class="w-28 shrink-0 text-slate-400 font-medium">{{ $label }}</dt>
            <dd class="text-slate-700">{{ $value }}</dd>
          </div>
          @endif
        @endforeach
      </dl>
    </div>

    {{-- Envío --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100 uppercase tracking-wide">Envío mercancía</h3>
      <dl class="space-y-2.5 text-sm">
        @foreach([
          ['Dirección',  $cliente->DIRECCIONENVIOMERCANCIA ?? ''],
          ['CP / Pobl.', trim(($cliente->CPENVIOMERCANCIA ?? '').' '.($cliente->POBLACIONENVIOMERCANCIA ?? ''))],
          ['Provincia',  $cliente->PROVINCIAENVIOMERCANCIA ?? ''],
          ['Teléfono',   $cliente->TELEFONOFIJOENVIOMERCANCIA ?? ''],
        ] as [$label, $value])
          @if($value)
          <div class="flex gap-3">
            <dt class="w-28 shrink-0 text-slate-400 font-medium">{{ $label }}</dt>
            <dd class="text-slate-700">{{ $value }}</dd>
          </div>
          @endif
        @endforeach
      </dl>
    </div>

  </div>

  {{-- Comentarios --}}
  @php
    $comentarios = array_filter([
      'Comentario'           => $cliente->COMENTARIO ?? '',
      'Interno'              => $cliente->COMENTARIO_INTERNO_CLIENTE ?? '',
      'Comercial'            => $cliente->COMENTARIO_COMERCIAL_CLIENTE ?? '',
      'Horario'              => $cliente->HORARIO ?? '',
      'Riesgo'               => $cliente->COMENTARIO_RIESGO ?? '',
      'Bloqueado'            => $cliente->COMENTARIO_BLOQUEADO_CLIENTE ?? '',
    ]);
  @endphp
  @if(count($comentarios))
  <div class="crm-card p-5">
    <h3 class="font-head font-semibold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100 uppercase tracking-wide">Comentarios</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
      @foreach($comentarios as $label => $texto)
      <div>
        <p class="text-slate-400 font-medium text-xs uppercase tracking-wide mb-1">{{ $label }}</p>
        <p class="text-slate-700 whitespace-pre-line">{{ $texto }}</p>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Tabs --}}
  <div class="crm-card overflow-hidden">
    <div class="flex border-b border-slate-100 overflow-x-auto" id="tabs-nav">
      @foreach([
        ['id'=>'tab-agenda',     'label'=>'Agenda'],
        ['id'=>'tab-visitas',    'label'=>'Visitas'],
        ['id'=>'tab-presup',     'label'=>'Presupuestos'],
        ['id'=>'tab-solicitudes','label'=>'Sol. Presup.'],
        ['id'=>'tab-pedidos',    'label'=>'Pedidos'],
        ['id'=>'tab-albaranes',  'label'=>'Albaranes'],
        ['id'=>'tab-expediente', 'label'=>'Expedientes'],
        ['id'=>'tab-incidencias','label'=>'Incidencias'],
        ['id'=>'tab-ventas',     'label'=>'Ventas SGFA'],
        ['id'=>'tab-articulos',  'label'=>'Artículos'],
        ['id'=>'tab-llamadas',   'label'=>'Llamadas'],
        ['id'=>'tab-gastos',     'label'=>'Gastos'],
        ['id'=>'tab-contactos',  'label'=>'Contactos'],
        ['id'=>'tab-direcciones','label'=>'Direcciones'],
        ['id'=>'tab-horarios',   'label'=>'Horarios'],
      ] as $i => $tab)
      <button class="tab-btn shrink-0 px-4 py-3 text-sm font-medium transition-colors border-b-2
                     {{ $i === 0 ? 'text-blue-600 border-blue-600' : 'text-slate-400 border-transparent hover:text-slate-600' }}"
              data-tab="{{ $tab['id'] }}"
              onclick="switchTab(this)">
        {{ $tab['label'] }}
      </button>
      @endforeach
    </div>
    <div id="tab-content" class="p-1 min-h-[200px]">
      <div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm">
        <span class="spinner"></span> Cargando...
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
var clienteCodigo = '{{ $cliente->CODIGO ?? $codigo }}';
var comercial = {{ session('comercial_id', 0) }};
var baseUrl = '{{ rtrim(url('/'), '/') }}';

function tabUrl(tab) {
  var hoy = new Date();
  var inicioAnio = hoy.getFullYear() + '-01-01';
  var urls = {
    'tab-agenda':      baseUrl+'/agenda/list?codigo_cliente='+clienteCodigo+'&comercial='+comercial,
    'tab-visitas':     baseUrl+'/clientes/'+clienteCodigo+'/visitas',
    'tab-presup':      baseUrl+'/presupuestos/list?cliente='+clienteCodigo,
    'tab-solicitudes': baseUrl+'/clientes/'+clienteCodigo+'/solicitudes',
    'tab-pedidos':     baseUrl+'/pedidos/list?cliente='+clienteCodigo,
    'tab-albaranes':   baseUrl+'/clientes/'+clienteCodigo+'/albaranes?fecha_desde='+inicioAnio,
    'tab-expediente':  baseUrl+'/expedientes/list?cliente_asign='+clienteCodigo,
    'tab-incidencias': baseUrl+'/clientes/'+clienteCodigo+'/incidencias',
    'tab-ventas':      baseUrl+'/clientes/'+clienteCodigo+'/ventas-sgfa?fecha_desde='+inicioAnio,
    'tab-articulos':   baseUrl+'/clientes/'+clienteCodigo+'/articulos-vendidos?fecha_desde='+inicioAnio,
    'tab-llamadas':    baseUrl+'/clientes/'+clienteCodigo+'/llamadas',
    'tab-gastos':      baseUrl+'/gastos/list?cliente='+clienteCodigo,
    'tab-contactos':   baseUrl+'/clientes/'+clienteCodigo+'/contactos',
    'tab-direcciones': baseUrl+'/clientes/'+clienteCodigo+'/direcciones',
    'tab-horarios':    baseUrl+'/clientes/'+clienteCodigo+'/horarios',
  };
  return urls[tab] || null;
}

function switchTab(btn) {
  document.querySelectorAll('.tab-btn').forEach(function(b) {
    b.classList.remove('text-blue-600','border-blue-600');
    b.classList.add('text-slate-400','border-transparent');
  });
  btn.classList.add('text-blue-600','border-blue-600');
  btn.classList.remove('text-slate-400','border-transparent');

  var url = tabUrl(btn.dataset.tab);
  if (!url) return;

  $('#tab-content').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');
  $.get(url, function(html) { $('#tab-content').html(html); })
   .fail(function() { $('#tab-content').html('<div class="empty-state">No disponible.</div>'); });
}

$(function() {
  var firstTab = document.querySelector('.tab-btn');
  if (firstTab) switchTab(firstTab);
});
</script>
@endpush
