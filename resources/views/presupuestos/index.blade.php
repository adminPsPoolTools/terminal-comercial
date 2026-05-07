@extends('layouts.app')
@section('title', 'Presupuestos')
@section('page-title', 'Presupuestos')

@section('content')
<div class="space-y-4">

  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Fecha desde</label>
        <input id="inp-fecha" type="text" class="form-input" style="min-width:120px"
               value="{{ date('01/01/Y') }}" placeholder="dd/mm/aaaa">
      </div>

      <div class="filter-group w-full sm:flex-1" style="min-width:160px">
        <label class="form-label">Título</label>
        <input id="inp-titulo" type="text" class="form-input" placeholder="Buscar por título...">
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Estado</label>
        <select id="sel-estado" class="form-select" style="min-width:150px">
          <option value="">Todos</option>
          @foreach($estados as $e)
            <option value="{{ $e->CODIGO ?? '' }}">{{ $e->DESCRIPCION ?? '' }}</option>
          @endforeach
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Provincia</label>
        <select id="sel-provincia" class="form-select" style="min-width:140px">
          <option value="">Todas</option>
          @foreach($provincias as $p)
            <option value="{{ $p->PROVINCIA ?? $p->DESCRIPCION ?? '' }}">{{ $p->DESCRIPCION ?? $p->PROVINCIA ?? '' }}</option>
          @endforeach
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Población</label>
        <select id="sel-poblacion" class="form-select" style="min-width:140px">
          <option value="">Todas</option>
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Proyecto</label>
        <select id="sel-proyecto" class="form-select">
          <option value="0">Todos</option>
          <option value="1">PS-Pool</option>
          <option value="50">PS-Cover</option>
          <option value="51">PS-Water</option>
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Carácter</label>
        <select id="sel-caracter" class="form-select">
          <option value="0">Todos</option>
          <option value="3">24 Horas</option>
          <option value="2">48 Horas</option>
          <option value="8">Pedido</option>
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Orden</label>
        <div class="flex gap-1">
          <select id="sel-orden" class="form-select">
            <option value="codigo">Código</option>
            <option value="fecha">Fecha</option>
            <option value="cliente">Cliente</option>
            <option value="base_imp">Base Imp.</option>
          </select>
          <select id="sel-orden-ori" class="form-select" style="max-width:110px">
            <option value="desc" selected>Desc.</option>
            <option value="asc">Asc.</option>
          </select>
        </div>
      </div>

      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">
        @include('partials.icon',['name'=>'refresh']) Refrescar
      </button>

    </div>
  </div>

  <div id="div-presupuestos" class="crm-card">
    <div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm">
      <span class="spinner"></span> Cargando...
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
$('#sel-provincia').change(function() {
  $.get('{{ route("presupuestos.poblaciones") }}', { provincia: $(this).val() }, function(html) {
    $('#sel-poblacion').html(html);
  });
});

function buscar() {
  var btn = document.getElementById('btn-buscar'); btn.disabled = true;
  $('#div-presupuestos').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');
  $.get('{{ route("presupuestos.list") }}', {
    fecha_desde: $('#inp-fecha').val(), titulo: $('#inp-titulo').val(),
    estado: $('#sel-estado').val(), provincia: $('#sel-provincia').val(),
    poblacion: $('#sel-poblacion').val(), proyecto: $('#sel-proyecto').val(),
    caracter: $('#sel-caracter').val(), orden: $('#sel-orden').val(),
    orden_ori: $('#sel-orden-ori').val(),
  }, function(html) { $('#div-presupuestos').html(html); btn.disabled = false; });
}
$(function() { buscar(); });
</script>
@endpush
