@extends('layouts.app')
@section('title','Listados') @section('page-title','Listados e Informes')
@section('content')
<div class="space-y-4">

  {{-- Date range global --}}
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha desde</label><input id="inp-desde" type="text" class="form-input" value="{{ date('01/m/Y', strtotime('first day of this month')) }}" style="min-width:120px"></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha hasta</label><input id="inp-hasta" type="text" class="form-input" value="{{ date('t/m/Y') }}" style="min-width:120px"></div>
    </div>
  </div>

  {{-- Listado cards grid --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    @php
      $listados = [
        // 'route' => null: listado del sistema antiguo aún sin endpoint/ruta migrada
        ['id'=>'ventas-prov', 'label'=>'Ventas clientes por provincias', 'icon'=>'list', 'route'=>'listados.ventas-clientes'],
        ['id'=>'ventas-secc', 'label'=>'Ventas artículos por secciones', 'icon'=>'package', 'route'=>null],
        ['id'=>'clientes-nuevos', 'label'=>'Clientes nuevos', 'icon'=>'users', 'route'=>null],
        ['id'=>'ventas-prov2', 'label'=>'Ventas por provincias', 'icon'=>'target', 'route'=>null],
        ['id'=>'ranking-ventas', 'label'=>'Ranking ventas', 'icon'=>'dollar', 'route'=>null],
        ['id'=>'ranking-visitas', 'label'=>'Ranking visitas comerciales', 'icon'=>'briefcase', 'route'=>null],
        ['id'=>'control-visitas', 'label'=>'Control de visitas', 'icon'=>'calendar', 'route'=>null],
        ['id'=>'facturas', 'label'=>'Facturas clientes', 'icon'=>'file-text', 'route'=>null],
        ['id'=>'clientes-list', 'label'=>'Listado clientes', 'icon'=>'users', 'route'=>'listados.clientes'],
      ];
    @endphp

    @foreach($listados as $l)
    <div class="crm-card p-5 {{ $l['route'] ? 'hover:shadow-md transition-shadow' : 'opacity-60' }}">
      <div class="flex items-start gap-3 mb-3">
        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
          @include('partials.icon', ['name' => $l['icon']])
        </div>
        <h3 class="font-head font-semibold text-sm text-slate-800 leading-snug flex-1">{{ $l['label'] }}</h3>
      </div>
      @if($l['route'])
      <div class="flex gap-2">
        <button onclick="cargarListado('{{ route($l['route']) }}', 'mensual', this)"
                class="btn btn-secondary btn-sm flex-1">Mensual</button>
        <button onclick="cargarListado('{{ route($l['route']) }}', 'anual', this)"
                class="btn btn-secondary btn-sm flex-1">Anual</button>
      </div>
      @else
      <p class="text-xs text-slate-400">Pendiente de migrar del sistema antiguo.</p>
      @endif
    </div>
    @endforeach

  </div>

  {{-- Results area --}}
  <div id="div-listado" class="crm-card hidden">
    <div class="flex items-center justify-between p-4 border-b border-slate-100">
      <h3 id="listado-title" class="font-head font-semibold text-slate-800"></h3>
      <button onclick="$('#div-listado').addClass('hidden')" class="text-slate-400 hover:text-slate-600">
        @include('partials.icon', ['name'=>'x'])
      </button>
    </div>
    <div id="div-listado-content" class="p-1"></div>
  </div>

</div>
@endsection

@push('scripts')
<script>
function cargarListado(url, tipo, btn) {
  $('#div-listado').removeClass('hidden');
  $('#listado-title').text($(btn).closest('.crm-card').find('h3').text() + ' · ' + tipo);
  $('#div-listado-content').html('<div class="flex items-center justify-center gap-3 py-10 text-slate-400 text-sm"><span class="spinner"></span> Cargando ' + tipo + '...</div>');
  document.getElementById('div-listado').scrollIntoView({behavior:'smooth'});
  $.get(url, { fecha_desde: $('#inp-desde').val(), fecha_hasta: $('#inp-hasta').val(), tipo: tipo },
        function(html) { $('#div-listado-content').html(html); })
   .fail(function() { $('#div-listado-content').html('<div class="empty-state">Error al cargar el listado.</div>'); });
}
</script>
@endpush
