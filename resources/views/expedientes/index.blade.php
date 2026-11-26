{{-- EXPEDIENTES INDEX --}}
@extends('layouts.app')
@section('title','Expedientes') @section('page-title','Expedientes')
@section('content')
<div class="space-y-4">
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha desde</label><input id="inp-fecha" type="text" class="form-input" value="{{ date('d/m/Y', strtotime('-1 month')) }}" placeholder="dd/mm/aaaa" style="min-width:120px"></div>
      <div class="filter-group w-full sm:flex-1" style="min-width:160px"><label class="form-label">Descripción</label><input id="inp-desc" type="text" class="form-input" placeholder="Buscar..."></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Estado</label>
        <select id="sel-estado" class="form-select" style="min-width:140px">
          <option value="0">Todos</option>
          @foreach($estados as $e)<option value="{{ $e->CODIGO ?? '' }}">{{ $e->DESCRIPCION ?? '' }}</option>@endforeach
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Provincia</label>
        <select id="sel-prov" class="form-select" style="min-width:130px">
          <option value="">Todas</option>
          @foreach($provincias as $p)<option value="{{ $p->CODIGO ?? '' }}">{{ $p->DESCRIPCION ?? $p->PROVINCIA ?? '' }}</option>@endforeach
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Población</label>
        <select id="sel-pob" class="form-select" style="min-width:130px">
          <option value="">Todas</option>
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Com. asignado</label>
        <select id="sel-com" class="form-select" style="min-width:140px">
          <option value="0">Todos</option>
          @foreach($vendedores as $v)<option value="{{ $v->CODIGO }}">{{ $v->NOMBRE }}</option>@endforeach
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Cliente presup.</label><input id="inp-cpresu" type="text" class="form-input" placeholder="Cliente..." style="min-width:120px"></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Cliente asig.</label><input id="inp-casig" type="text" class="form-input" placeholder="Cliente..." style="min-width:120px"></div>
      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">@include('partials.icon',['name'=>'refresh']) Refrescar</button>
      {{-- Alta de expedientes pendiente de migrar del sistema antiguo (no hay ruta ni endpoint todavía) --}}
      <button type="button" disabled title="Pendiente de migrar del sistema antiguo" class="btn btn-secondary shrink-0 self-end opacity-50 cursor-not-allowed">@include('partials.icon',['name'=>'plus']) Nueva entrada</button>
    </div>
  </div>
  <div id="div-exp" class="crm-card"><div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div></div>
</div>
@endsection
@push('scripts')
<script>
function buscar(){
  var btn=document.getElementById('btn-buscar'); btn.disabled=true;
  $('#div-exp').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');
  $.get('{{ route("expedientes.list") }}',{fecha_desde:$('#inp-fecha').val(),descripcion:$('#inp-desc').val(),estado:$('#sel-estado').val(),provincia:$('#sel-prov').val(),poblacion:$('#sel-pob').val(),com_asig:$('#sel-com').val(),cliente_presu:$('#inp-cpresu').val(),cliente_asign:$('#inp-casig').val()},function(html){$('#div-exp').html(html);btn.disabled=false;})
   .fail(function(){$('#div-exp').html('<div class="empty-state">Error al cargar expedientes.</div>');btn.disabled=false;});
}
$('#sel-prov').change(function(){
  $('#sel-pob').html('<option value="">Todas</option>');
  if(!$(this).val()) return;
  $.get('{{ route("presupuestos.poblaciones") }}',{provincia:$(this).val()},function(html){$('#sel-pob').html(html);});
});
$(function(){buscar();});
</script>
@endpush
