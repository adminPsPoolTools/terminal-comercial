@extends('layouts.app')
@section('title', 'Agenda ' . $codigo)
@section('page-title', 'Agenda ' . $codigo)

@php
  $fecha = fn ($v) => ($v && ($t = strtotime((string) $v))) ? date('d/m/Y', $t) : '—';
  $hora  = fn ($v) => $v ? substr((string) $v, 0, 5) : '';
  $nombreUsuario = fn ($cod) => ($cod !== null && $cod !== '') ? ($usuarios[trim((string) $cod)] ?? $cod) : '—';
  $estadoActual  = $estadosAgenda[trim((string) ($agenda->ESTADO ?? ''))] ?? ($agenda->ESTADO ?? '');
  $expediente    = (int) ($agenda->EXPEDIENTE ?? 0);
@endphp

@section('content')
<div class="space-y-4 max-w-5xl">

  @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-green-700 text-sm font-medium">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-700 text-sm font-medium">{{ session('error') }}</div>
  @endif

  {{-- Cabecera --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Agenda · <span class="font-mono font-semibold text-slate-600">{{ $agenda->CODIGO ?? $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900 leading-tight">{{ $agenda->TITULO ?? '—' }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        Fecha: <strong>{{ $fecha($agenda->FECHA ?? null) }} {{ $hora($agenda->HORA ?? null) }}</strong>
        @if($estadoActual) · Estado: <span class="badge badge-blue">{{ $estadoActual }}</span> @endif
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('agenda.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- Datos --}}
    <div class="crm-card p-4 info-card">
      <h3>Datos</h3>
      <dl class="dl-compact">
        @foreach([
          ['Asignada a',   $nombreUsuario($agenda->USUARIO ?? null)],
          ['Creada por',   $nombreUsuario($agenda->USUARIO_ORIGEN ?? null)],
          ['Urgencia',     $caracteres[trim((string) ($agenda->CARACTER ?? ''))] ?? ''],
          ['Prioridad',    $agenda->ORDEN_PRIORIDAD ?? ''],
          ['Lugar',        $agenda->LUGAR ?? ''],
          ['Realizada',    ($agenda->REALIZADO ?? '') === 'S' ? 'Sí' : (($agenda->REALIZADO ?? '') === 'N' ? 'No' : '')],
          ['Alarma',       ($agenda->ALARMA ?? '') === 'S' ? 'Sí' : (($agenda->ALARMA ?? '') === 'N' ? 'No' : '')],
          ['Aviso el',     !empty($agenda->FECHA_ACTIVAR_ALARMA_AGENDA) ? $fecha($agenda->FECHA_ACTIVAR_ALARMA_AGENDA) : ''],
        ] as [$l,$v]) @if($v !== '' && $v !== null && $v !== '—')
        <div class="dl-row"><dt>{{ $l }}</dt><dd>{{ $v }}</dd></div>
        @endif @endforeach
        <div class="dl-row"><dt>Expediente</dt><dd>
          @if($expediente > 0)
            <a href="{{ route('expedientes.detalle', $expediente) }}" class="text-blue-600 hover:underline font-semibold">{{ $expediente }}</a>
          @else — @endif
        </dd></div>
      </dl>
    </div>

    {{-- Descripción --}}
    <div class="crm-card p-4 info-card">
      <h3>Descripción</h3>
      <p class="text-sm text-slate-700 whitespace-pre-line">{{ trim((string) ($agenda->DESCRIPCION ?? '')) ?: 'Sin descripción.' }}</p>
      @if(trim((string) ($agenda->AGENDA_COMENTARIO_INTERNO ?? '')) !== '')
        <h3 class="mt-4">Comentario interno</h3>
        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $agenda->AGENDA_COMENTARIO_INTERNO }}</p>
      @endif
    </div>
  </div>

  {{-- Cambiar estado --}}
  <div class="crm-card p-4">
    <h3 class="font-head font-semibold text-slate-800 mb-3">Cambiar estado</h3>
    @if(empty($estadosCambio))
      <p class="text-sm text-slate-500">No hay estados disponibles para tu usuario.</p>
    @else
    <form method="POST" action="{{ route('agenda.estado', $codigo) }}" class="space-y-3" id="form-estado">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
          <label class="form-label">Nuevo estado <span class="text-red-500">*</span></label>
          <select name="estado" id="sel-estado" class="form-select" required>
            <option value="">— Seleccionar —</option>
            @foreach($estadosCambio as $e)
              <option value="{{ $e->CODIGO }}"
                      data-usuario="{{ strtoupper(trim((string) ($e->REALIZAR_POR_USUARIO ?? ''))) === 'S' ? 1 : 0 }}"
                      data-fecha="{{ in_array((int) ($e->ESTADO_AGENDA_ASOCIADO ?? 0), $estadosConFecha, true) ? 1 : 0 }}"
                      @selected(old('estado') == $e->CODIGO)>{{ $e->DESCRIPCION }}</option>
            @endforeach
          </select>
        </div>
        <div id="grp-usuario" class="hidden">
          <label class="form-label">Debe realizarla <span class="text-red-500">*</span></label>
          <select name="usuario_realizar_por" class="form-select">
            <option value="">— Seleccionar —</option>
            @foreach($usuarios as $cod => $nombre)
              <option value="{{ $cod }}" @selected(old('usuario_realizar_por', trim((string) ($agenda->USUARIO ?? ''))) == $cod)>{{ $nombre }}</option>
            @endforeach
          </select>
        </div>
        <div id="grp-fecha" class="hidden">
          <label class="form-label">Fecha de aviso <span class="text-red-500">*</span></label>
          <input type="date" name="fecha_alarma" class="form-input" value="{{ old('fecha_alarma') }}" min="{{ date('Y-m-d') }}">
        </div>
      </div>
      <div>
        <label class="form-label">Comentario</label>
        <textarea name="comentario" rows="3" class="form-input" placeholder="Comentario del cambio de estado...">{{ old('comentario') }}</textarea>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="btn btn-primary">Guardar estado</button>
      </div>
    </form>
    @endif
  </div>

  {{-- Historial --}}
  <div class="crm-card overflow-hidden">
    <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Historial de estados</h3>
    @if(empty($historial))
      <div class="empty-state">Sin cambios de estado registrados.</div>
    @else
    <div class="table-wrapper">
      <table class="crm-table">
        <thead><tr><th>Fecha</th><th>Estado</th><th>Usuario</th><th>Realizar por</th><th>Comentario</th></tr></thead>
        <tbody>
          @foreach($historial as $h)
          <tr>
            <td class="text-xs whitespace-nowrap">{{ $fecha($h->FECHA_ALTA ?? null) }} {{ $hora($h->HORA_ALTA ?? null) }}</td>
            <td><span class="badge badge-blue">{{ $h->ESTADO_EXPEDIENTE ?? $h->ESTADO ?? '—' }}</span></td>
            <td class="text-xs">{{ $h->NOMBRE ?? '—' }}</td>
            <td class="text-xs text-slate-500">
              {{ $h->USUARIO_REALIZAR_POR ?? '—' }}
              @if(!empty($h->USUARIO_REALIZAR_POR_ANT) && ($h->USUARIO_REALIZAR_POR_ANT ?? '') !== ($h->USUARIO_REALIZAR_POR ?? ''))
                <span class="text-slate-400">(antes: {{ $h->USUARIO_REALIZAR_POR_ANT }})</span>
              @endif
            </td>
            <td class="text-sm whitespace-pre-line">{{ trim((string) ($h->COMENTARIO ?? '')) ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
// Muestra "Debe realizarla" y "Fecha de aviso" solo cuando el estado elegido los necesita
function actualizarCamposEstado() {
  var opt = $('#sel-estado option:selected');
  var usuario = opt.data('usuario') == 1, fecha = opt.data('fecha') == 1;
  $('#grp-usuario').toggleClass('hidden', !usuario).find('select').prop('required', usuario);
  $('#grp-fecha').toggleClass('hidden', !fecha).find('input').prop('required', fecha);
}
$('#sel-estado').on('change', actualizarCamposEstado);
$(actualizarCamposEstado);
</script>
@endpush
