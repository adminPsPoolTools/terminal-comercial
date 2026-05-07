@extends('layouts.app')
@section('title','Recursos Humanos') @section('page-title','Recursos Humanos')
@section('content')
<div class="max-w-4xl mx-auto space-y-4">

  {{-- Header card --}}
  <div class="crm-card p-6">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center text-white text-xl font-head font-bold shrink-0">
        {{ strtoupper(substr($nombre ?? '?', 0, 2)) }}
      </div>
      <div>
        <h2 class="font-head font-bold text-lg text-slate-900">{{ $nombre ?? '—' }}</h2>
        <p class="text-slate-500 text-sm">Empleado · ID {{ $usuario }}</p>
      </div>
    </div>
  </div>

  {{-- Quick actions --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
    @php
      $acciones = [
        ['label'=>'Fichar entrada', 'color'=>'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon'=>'check'],
        ['label'=>'Fichar salida',  'color'=>'bg-red-50 text-red-700 border-red-200',             'icon'=>'x'],
        ['label'=>'Ver nóminas',    'color'=>'bg-blue-50 text-blue-700 border-blue-200',           'icon'=>'file-text'],
        ['label'=>'Solicitar día',  'color'=>'bg-amber-50 text-amber-700 border-amber-200',        'icon'=>'calendar'],
      ];
    @endphp
    @foreach($acciones as $a)
    <button class="flex flex-col items-center gap-2 p-4 rounded-xl border font-medium text-sm transition-all hover:shadow-md {{ $a['color'] }}">
      @include('partials.icon', ['name' => $a['icon']])
      {{ $a['label'] }}
    </button>
    @endforeach
  </div>

  {{-- Content loaded from WS --}}
  <div id="div-rrhh" class="crm-card">
    <div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm">
      <span class="spinner"></span> Cargando información...
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
$(function(){
  $.get('{{ config("crm.ws_rh") }}/inicio?usuario={{ $usuario }}', function(html){
    $('#div-rrhh').html(html);
  }).fail(function(){
    $('#div-rrhh').html('<div class="empty-state">No se pudo cargar el módulo de RRHH.</div>');
  });
});
</script>
@endpush
