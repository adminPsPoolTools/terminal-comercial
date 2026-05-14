@extends('layouts.app')
@section('title', 'Albarán ' . $codigo)
@section('page-title', 'Albarán ' . ($cabecera->CODIGO_TIQUET ?? $codigo))

@section('content')
<div class="space-y-4 max-w-5xl">

  {{-- Header --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Albarán · <span class="font-mono font-semibold text-slate-600">{{ $cabecera->CODIGO_TIQUET ?? $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900 leading-tight">{{ $cabecera->TITULO ?? '—' }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        Fecha: <strong>{{ $cabecera->FECHA ?? '—' }}</strong>
        @if(!empty($cabecera->ESTADO)) · Estado: <span class="badge badge-blue text-xs">{{ $cabecera->ESTADO }}</span> @endif
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('clientes.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="crm-card p-4 info-card">
      <h3>Cliente</h3>
      <dl class="dl-compact">
        @foreach([
          ['Código',   $cabecera->CODIGO_CLIENTE ?? ''],
          ['Nombre',   $cabecera->DESCRIPCION_CLIENTE ?? ''],
          ['CIF',      $cabecera->CIF_CLIENTE ?? ''],
          ['Dirección',$cabecera->DIRECCION_CLIENTE ?? ''],
          ['CP/Pobl.', trim(($cabecera->CP_CLIENTE??'').' '.($cabecera->POBLACION_CLIENTE??''))],
          ['Provincia',$cabecera->PROVINCIA_CLIENTE ?? ''],
          ['Teléfono', $cabecera->TELEFONOFIJO_CLIENTE ?? ''],
          ['Email',    $cabecera->CORREO_CLIENTE ?? ''],
        ] as [$l,$v]) @if($v)
        <div class="dl-row"><dt>{{ $l }}</dt><dd>{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
    </div>

    <div class="crm-card p-4 info-card">
      <h3>Importes</h3>
      <dl class="dl-compact">
        @foreach([
          ['Base imp.',  number_format((float)($cabecera->BASEIMPONIBLE??0),2,',','.').' €'],
          ['Total c/IVA',number_format((float)($cabecera->IMPORTECONIVA??0),2,',','.').' €'],
        ] as [$l,$v]) @if($v)
        <div class="dl-row"><dt>{{ $l }}</dt><dd class="font-semibold">{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
      @if(!empty($cabecera->PRESUPUESTO))
        <p class="text-xs text-slate-400 mt-3">Presupuesto origen:
          <a href="{{ route('presupuestos.detalle', $cabecera->PRESUPUESTO) }}" class="text-blue-600 hover:underline font-mono font-semibold">{{ $cabecera->PRESUPUESTO }}</a>
        </p>
      @endif
      @if($cabecera->CODIGO_CLIENTE ?? false)
        <div class="mt-3 pt-3 border-t border-slate-100">
          <a href="{{ route('clientes.detalle', $cabecera->CODIGO_CLIENTE) }}" class="text-xs text-blue-600 hover:underline">Ver ficha del cliente →</a>
        </div>
      @endif
    </div>
  </div>

  {{-- Líneas --}}
  <div class="crm-card overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
      <h3 class="font-head font-semibold text-sm text-slate-800">Líneas</h3>
      <span class="text-xs text-slate-400">{{ count($lineas) }} artículo(s)</span>
    </div>
    @if(empty($lineas))
      <div class="empty-state">Sin líneas.</div>
    @else
    <div class="table-wrapper">
      <table class="crm-table">
        <thead><tr>
          <th>Artículo</th><th>Descripción</th>
          <th class="td-right">Cant.</th><th class="td-right">PVP</th>
          <th class="td-right">Dto. %</th><th class="td-right">Importe</th>
        </tr></thead>
        <tbody>
          @foreach($lineas as $lin)
          <tr>
            <td class="font-mono text-xs font-semibold">{{ $lin->ARTICULO ?? '—' }}</td>
            <td class="text-sm">
              {{ $lin->DESCRIPCION ?? '—' }}
              @if(!empty($lin->COMENTARIO))
                <p class="text-xs text-slate-400 mt-0.5">{{ $lin->COMENTARIO }}</p>
              @endif
            </td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->CANTIDAD??0),2,',','.') }}</td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->PVP??0),2,',','.').' €' }}</td>
            <td class="td-right text-xs">{{ $lin->DTO ?? 0 }} %</td>
            <td class="td-right text-xs font-mono font-semibold">{{ number_format((float)($lin->IMPORTE??0),2,',','.').' €' }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="5" class="text-right text-xs text-slate-500 pr-4">Total</td>
            <td class="td-right font-mono font-bold text-sm">{{ number_format(array_sum(array_map(fn($l)=>(float)($l->IMPORTE??0),$lineas)),2,',','.').' €' }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
    @endif
  </div>

</div>
@endsection
