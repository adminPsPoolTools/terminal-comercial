@extends('layouts.app')
@section('title','Incidencias SAT') @section('page-title','Incidencias SAT')
@section('content')
<div class="space-y-4">
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Fecha desde</label><input id="inp-fecha" type="date" class="form-input" style="min-width:160px"></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Estado</label>
        <select id="sel-estado" class="form-select" style="min-width:150px">
          <option selected>Todos</option>
          @foreach($estados as $e)<option>{{ $e->ESTADO ?? '' }}</option>@endforeach
        </select></div>
      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">@include('partials.icon',['name'=>'refresh']) Refrescar</button>
    </div>
  </div>
  <div id="div-inc" class="crm-card"><div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div></div>
</div>
@endsection
@push('scripts')
<script>
function buscar(){var btn=document.getElementById('btn-buscar');btn.disabled=true;$('#div-inc').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');$.get('{{ route("incidencias.list") }}',{fecha_desde:$('#inp-fecha').val(),estado:$('#sel-estado').val()},function(html){$('#div-inc').html(html);btn.disabled=false;});}
$(function(){buscar();});
</script>
@endpush
