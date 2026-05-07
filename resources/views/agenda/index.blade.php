@extends('layouts.app')

@section('title', 'Agenda')
@section('page-title', 'Agenda')

@section('content')
<div class="space-y-4">

  {{-- Filter bar --}}
  <div class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Fecha desde</label>
        <input id="inp-fecha" type="text" class="form-input" placeholder="dd/mm/aaaa"
               value="{{ date('01/01/Y') }}" style="min-width:120px">
      </div>

      <div class="filter-group w-full sm:w-auto sm:flex-1" style="min-width:160px">
        <label class="form-label">Título / descripción</label>
        <input id="inp-titulo" type="text" class="form-input" placeholder="Buscar...">
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Alarma</label>
        <select id="sel-alarma" class="form-select" style="min-width:130px">
          <option value="">Todos</option>
          <option value="S" selected>Con alarma</option>
          <option value="N">Sin alarma</option>
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Recordatorio</label>
        <select id="sel-recordatorio" class="form-select" style="min-width:150px">
          <option value="" selected>Todos</option>
          <option value="S">Con recordatorio</option>
          <option value="N">Sin recordatorio</option>
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Realizado</label>
        <select id="sel-realizado" class="form-select" style="min-width:140px">
          <option value="N" selected>No realizados</option>
          <option value="">Todos</option>
          <option value="S">Realizados</option>
        </select>
      </div>

      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Estado / Expediente</label>
        <select id="sel-estado" class="form-select" style="min-width:160px">
          <option value="">Todos</option>
          @foreach($estados as $e)
            <option value="{{ $e->ESTADO_AGENDA_ASOCIADO ?? '' }}">{{ $e->DESCRIPCION ?? '' }}</option>
          @endforeach
        </select>
      </div>

      @if(in_array(session('comercial_id'), config('crm.admin_comerciales')))
      <div class="filter-group w-full sm:w-auto">
        <label class="form-label">Usuario</label>
        <select id="sel-usuario" class="form-select" style="min-width:160px">
          @foreach($usuarios as $u)
            <option value="{{ $u->CODIGO }}"
              {{ $u->CODIGO == session('comercial_id') ? 'selected' : '' }}>
              {{ strtoupper($u->NOMBRE ?? '') }}
            </option>
          @endforeach
        </select>
      </div>
      @endif

      <button id="btn-buscar" onclick="buscarAgenda()"
              class="btn btn-primary shrink-0 self-end">
        @include('partials.icon', ['name'=>'refresh'])
        Refrescar
      </button>

    </div>
  </div>

  {{-- Results --}}
  <div id="div-agenda" class="crm-card">
    <div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm">
      <span class="spinner"></span> Cargando...
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
var _com = {{ session('comercial_id') }};

function buscarAgenda() {
  var btn = document.getElementById('btn-buscar');
  btn.disabled = true;
  $('#div-agenda').html('<div class="flex items-center justify-center gap-3 py-16 text-slate-400 text-sm"><span class="spinner"></span> Cargando...</div>');

  $.get('{{ route("agenda.list") }}', {
    fecha_desde:   $('#inp-fecha').val(),
    titulo:        $('#inp-titulo').val(),
    con_alarma:    $('#sel-alarma').val(),
    recordatorio:  $('#sel-recordatorio').val(),
    realizado:     $('#sel-realizado').val(),
    estado:        $('#sel-estado').val(),
    usuario:       $('#sel-usuario').val() || _com,
  }, function(html) {
    $('#div-agenda').html(html);
    btn.disabled = false;
  });
}

$(function() { buscarAgenda(); });
</script>
@endpush
