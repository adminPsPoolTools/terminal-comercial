@extends('layouts.app')
@section('title', 'Visita ' . $codigo)
@section('page-title', 'Visita comercial ' . $codigo)

@section('content')
<div class="space-y-4 max-w-4xl">

  {{-- Header --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Visita comercial · <span class="font-mono font-semibold text-slate-600">{{ $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900">
        {{ $visita->TIPO ?? '—' }}
        @if(!empty($visita->MOTIVO)) · {{ $visita->MOTIVO }} @endif
      </h1>
      <p class="text-xs text-slate-500 mt-1">
        Fecha: <strong>{{ $visita->FECHA ?? '—' }}</strong>
        @if(!empty($visita->HORA)) · Hora: <strong>{{ $visita->HORA }}</strong> @endif
        @if(!empty($visita->VENDEDOR)) · Vendedor: <strong>{{ $visita->VENDEDOR }}</strong> @endif
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('clientes.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

  {{-- Datos visita --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="crm-card p-4 info-card">
      <h3>Datos de la visita</h3>
      <dl class="dl-compact">
        @foreach([
          ['Tipo',      $visita->TIPO ?? ''],
          ['Motivo',    $visita->MOTIVO ?? ''],
          ['Contacto',  $visita->CONTACTO ?? ''],
          ['Proyecto',  $visita->PROYECTO ?? ''],
          ['Expediente',$visita->EXPEDIENTE ?? ''],
        ] as [$l,$v]) @if($v)
        <div class="dl-row"><dt>{{ $l }}</dt><dd>{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
    </div>

    <div class="crm-card p-4 info-card">
      <h3>Acciones y asuntos</h3>
      @if(!empty($acciones))
        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Acciones</p>
        <div class="flex flex-wrap gap-1 mb-3">
          @foreach($acciones as $a)
            <span class="badge badge-blue">{{ $a->DESCRIPCION_ACCION ?? $a->ACCION ?? '—' }}</span>
          @endforeach
        </div>
      @endif
      @if(!empty($asuntos))
        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Asuntos</p>
        <div class="flex flex-wrap gap-1">
          @foreach($asuntos as $s)
            <span class="badge badge-gray">{{ $s->DESCRIPCION_ASUNTO ?? $s->ASUNTO ?? '—' }}</span>
          @endforeach
        </div>
      @endif
      @if(empty($acciones) && empty($asuntos))
        <p class="text-xs text-slate-400">Sin acciones ni asuntos registrados.</p>
      @endif
    </div>
  </div>

  {{-- Comentarios --}}
  @php
    $comentarios = array_filter([
      'Comentario'        => $visita->COMENTARIO ?? '',
      'Comentario motivo' => $visita->COMENTARIO_MOTIVO ?? '',
    ]);
  @endphp
  @if(count($comentarios))
  <div class="crm-card p-4 info-card">
    <h3>Comentarios</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach($comentarios as $label => $texto)
      <div>
        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">{{ $label }}</p>
        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $texto }}</p>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection
