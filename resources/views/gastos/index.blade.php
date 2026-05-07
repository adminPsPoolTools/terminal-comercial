@extends('layouts.app')
@section('title','Gastos') @section('page-title','Gastos')
@section('content')
<div class="space-y-4">
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha desde</label><input id="inp-desde" type="date" class="form-input" style="min-width:140px"></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha hasta</label><input id="inp-hasta" type="date" class="form-input" style="min-width:140px"></div>
      <div class="filter-group w-full sm:flex-1" style="min-width:160px"><label class="form-label">Comentario</label><input id="inp-comentario" type="text" class="form-input" placeholder="Buscar..."></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Tipo de gasto</label>
        <select id="sel-tipo" class="form-select" style="min-width:150px">
          <option value="0">Todos</option>
          @foreach($tiposGasto as $t)<option value="{{ $t->CODIGO }}">{{ $t->DESCRIPCION }}</option>@endforeach
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Medio de cobro</label>
        <select id="sel-medio" class="form-select" style="min-width:150px">
          <option value="0">Todos</option>
          @foreach($mediosCobro as $m)<option value="{{ $m->CODIGO }}">{{ $m->DESCRIPCION }}</option>@endforeach
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Pagado</label>
        <select id="sel-pagado" class="form-select">
          <option value="0">Todos</option>
          <option value="S">Sí</option>
          <option value="N">No</option>
        </select></div>
      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">@include('partials.icon',['name'=>'refresh']) Refrescar</button>
      <button class="btn btn-secondary shrink-0 self-end">@include('partials.icon',['name'=>'plus']) Nuevo gasto</button>
    </div>
  </div>
  <div id="div-gastos" class="crm-card"><div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div></div>
</div>
@endsection
@push('scripts')
<script>
function buscar(){
  var btn=document.getElementById('btn-buscar'); btn.disabled=true;
  $('#div-gastos').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');
  $.get('{{ route("gastos.list") }}',{fecha_desde:$('#inp-desde').val(),fecha_hasta:$('#inp-hasta').val(),comentario:$('#inp-comentario').val(),tipo_gasto:$('#sel-tipo').val(),medio_cobro:$('#sel-medio').val(),pagado:$('#sel-pagado').val()},function(html){$('#div-gastos').html(html);btn.disabled=false;});
}
$(function(){buscar();});
</script>
@endpush
