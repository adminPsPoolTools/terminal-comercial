@extends('layouts.app')
@section('title', 'Solicitud ' . $codigo)
@section('page-title', 'Solicitud de presupuesto ' . $codigo)

@section('content')
<div class="space-y-4 max-w-4xl">

  {{-- Header --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Solicitud de presupuesto · <span class="font-mono font-semibold text-slate-600">{{ $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900 leading-tight">{{ $solicitud->DESCRIPCION ?? $solicitud->DESCRIPCION_DETALLADA ?? '—' }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        Fecha: <strong>{{ $solicitud->FECHA ?? '—' }}</strong>
        @if(!empty($solicitud->HORA)) · Hora: <strong>{{ $solicitud->HORA }}</strong> @endif
        @if(!empty($solicitud->DESC_CARACTER)) · Carácter: <strong>{{ $solicitud->DESC_CARACTER }}</strong> @endif
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('clientes.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

  {{-- Datos --}}
  <div class="crm-card p-4 info-card">
    <h3>Datos de la solicitud</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
      <dl class="dl-compact">
        @foreach([
          ['Código',     $solicitud->CODIGO ?? $codigo],
          ['Carácter',   $solicitud->DESC_CARACTER ?? ''],
          ['Categoría',  $solicitud->DESCRIPCIONCATEGORIA ?? ''],
          ['Presupuesto',$solicitud->PRESUPUESTO ?? ''],
          ['Comercial',  $solicitud->USUARIO_ALTA ?? ''],
        ] as [$l,$v]) @if($v)
        <div class="dl-row"><dt>{{ $l }}</dt>
          <dd>
            @if($l === 'Presupuesto' && !empty($v))
              <a href="{{ route('presupuestos.detalle', $v) }}" class="text-blue-600 hover:underline font-mono font-semibold">{{ $v }}</a>
            @else
              {{ $v }}
            @endif
          </dd>
        </div>
        @endif @endforeach
      </dl>
    </div>
  </div>

  {{-- Descripción detallada --}}
  @if(!empty($solicitud->DESCRIPCION_DETALLADA))
  <div class="crm-card p-4 info-card">
    <h3>Descripción detallada</h3>
    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $solicitud->DESCRIPCION_DETALLADA }}</p>
  </div>
  @endif

  {{-- Comentario / texto plantilla --}}
  @php
    $textos = array_filter([
      'Comentario'     => $solicitud->COMENTARIO ?? '',
      'Texto plantilla'=> $solicitud->TEXTO_PLANTILLA ?? '',
    ]);
  @endphp
  @if(count($textos))
  <div class="crm-card p-4 info-card">
    <h3>Comentarios</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach($textos as $label => $texto)
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
