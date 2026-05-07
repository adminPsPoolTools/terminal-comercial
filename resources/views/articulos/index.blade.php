@extends('layouts.app')
@section('title','Artículos') @section('page-title','Artículos')
@section('content')
<div class="space-y-4">
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:flex-1" style="min-width:200px"><label class="form-label">Buscar artículo</label><input id="inp-busq" type="text" class="form-input" placeholder="Código, descripción..."></div>
      <button id="btn-buscar" onclick="buscar()" class="btn btn-primary shrink-0 self-end">@include('partials.icon',['name'=>'search']) Buscar</button>
    </div>
  </div>
  <div id="div-articulos" class="crm-card"><div class="empty-state">Introduce un término de búsqueda.</div></div>
</div>
@endsection
@push('scripts')
<script>
function buscar(){if(!$('#inp-busq').val()){alert('Introduce al menos un término');return;}var btn=document.getElementById('btn-buscar');btn.disabled=true;$('#div-articulos').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');$.get('{{ route("articulos.list") }}',{busqueda:$('#inp-busq').val()},function(html){$('#div-articulos').html(html);btn.disabled=false;});}
$('#inp-busq').keypress(function(e){if(e.which===13)buscar();});
</script>
@endpush
