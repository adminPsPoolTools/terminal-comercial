@extends('layouts.app')
@section('title','Pedidos') @section('page-title','Pedidos')
@section('content')
<div class="space-y-4">
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha desde</label><input id="inp-fecha" type="text" class="form-input" value="{{ date('01/01/Y') }}" style="min-width:120px"></div>
      <div class="filter-group w-full sm:flex-1" style="min-width:160px"><label class="form-label">Título</label><input id="inp-titulo" type="text" class="form-input" placeholder="Buscar..."></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Estado</label>
        <select id="sel-estado" class="form-select" style="min-width:140px">
          <option value="">Todos</option>
          @foreach($estados as $e)<option value="{{ $e->CODIGO ?? $e->DESCRIPCION ?? '' }}">{{ $e->DESCRIPCION ?? '' }}</option>@endforeach
        </select></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Servido</label>
        <select id="sel-servido" class="form-select" style="min-width:170px">
          <option value="">Todos</option>
          <option value="1">Servido nada + parcial</option>
          <option value="2">Servido nada</option>
          <option value="3">Servido parcial</option>
          <option value="4">Servido total</option>
        </select></div>
      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">@include('partials.icon',['name'=>'refresh']) Refrescar</button>
    </div>
  </div>
  <div id="div-pedidos" class="crm-card"><div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div></div>
</div>
@endsection
@push('scripts')
<script>
function buscar(){var btn=document.getElementById('btn-buscar');btn.disabled=true;$('#div-pedidos').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');$.get('{{ route("pedidos.list") }}',{fecha_desde:$('#inp-fecha').val(),titulo:$('#inp-titulo').val(),estado:$('#sel-estado').val(),estado_servido:$('#sel-servido').val()},function(html){$('#div-pedidos').html(html);btn.disabled=false;});}
$(function(){buscar();});
</script>
@endpush
