@extends('layouts.app')
@section('title','Clientes') @section('page-title','Clientes')
@section('content')
<div class="space-y-4">
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:flex-1" style="min-width:180px"><label class="form-label">Nombre / Código</label><input id="inp-busqueda" type="text" class="form-input" placeholder="Buscar cliente..."></div>
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Categoría</label>
        <select id="sel-cat" class="form-select" style="min-width:140px">
          <option value="">Todas</option>
          @foreach($categorias as $c)<option value="{{ $c->CODIGO }}">{{ $c->DESCRIPCIONCATEGORIA }}</option>@endforeach
        </select></div>
      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">@include('partials.icon',['name'=>'search']) Buscar</button>
      <button class="btn btn-secondary shrink-0 self-end">@include('partials.icon',['name'=>'plus']) Nuevo cliente</button>
    </div>
  </div>
  <div id="div-clientes" class="crm-card"><div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div></div>
</div>
@endsection
@push('scripts')
<script>
function buscar(){
  var btn=document.getElementById('btn-buscar'); btn.disabled=true;
  $('#div-clientes').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');
  $.get('{{ route("clientes.list") }}',{busqueda:$('#inp-busqueda').val(),categoria:$('#sel-cat').val()},function(html){$('#div-clientes').html(html);btn.disabled=false;})
   .fail(function(){$('#div-clientes').html('<div class="empty-state">Error al cargar clientes.</div>');btn.disabled=false;});
}
$('#inp-busqueda').keypress(function(e){if(e.which===13)buscar();});
$(function(){ buscar(); });
</script>
@endpush
