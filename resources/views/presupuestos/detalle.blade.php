@extends('layouts.app')
@section('title', 'Presupuesto ' . $codigo)
@section('page-title', 'Presupuesto ' . ($cabecera->PRE_CODIGO ?? $codigo))

@section('content')
<div class="space-y-4 max-w-5xl">

  @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-green-700 text-sm font-medium">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-700 text-sm font-medium">{{ session('error') }}</div>
  @endif

  {{-- Header --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Presupuesto · <span class="font-mono font-semibold text-slate-600">{{ $cabecera->PRE_CODIGO ?? $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900 leading-tight">{{ $cabecera->TITULO ?? '—' }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        Fecha: <strong>{{ $cabecera->FECHA ?? '—' }}</strong>
        · Vendedor: <strong>{{ $cabecera->VENDEDOR ?? '—' }}</strong>
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('presupuestos.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

  {{-- Info: cliente + totales --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="crm-card p-4 info-card">
      <h3>Cliente</h3>
      <dl class="dl-compact">
        @foreach([
          ['Código',   $cabecera->CLI_CODIGO ?? ''],
          ['Nombre',   $cabecera->CLI_DESCRIPCION ?? ''],
          ['CIF',      $cabecera->CLI_CIF ?? ''],
          ['Dirección',$cabecera->CLI_DIRECCION ?? ''],
          ['CP/Pobl.', trim(($cabecera->CLI_CP??'').' '.($cabecera->CLI_POBLACION??''))],
          ['Provincia',$cabecera->CLI_PROVINCIA ?? ''],
          ['Teléfono', $cabecera->CLI_TELEFONOFIJO ?? ''],
          ['Email',    $cabecera->CLI_CORREO ?? ''],
        ] as [$l,$v]) @if($v)
        <div class="dl-row"><dt>{{ $l }}</dt><dd>{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
    </div>

    <div class="crm-card p-4 info-card">
      <h3>Importes</h3>
      <dl class="dl-compact">
        @foreach([
          ['Base imp.',   number_format((float)($cabecera->BASEIMPONIBLE??0),2,',','.').' €'],
          ['% Dto.',      ($cabecera->PDTO??'') ? $cabecera->PDTO.'%' : ''],
          ['Importe dto.',($cabecera->IMPORTEDTO??0) ? number_format((float)$cabecera->IMPORTEDTO,2,',','.').' €' : ''],
          ['% IVA',       ($cabecera->IVA??'') ? $cabecera->IVA.'%' : ''],
          ['Total',       number_format((float)($cabecera->TOTAL??0),2,',','.').' €'],
        ] as [$l,$v]) @if($v)
        <div class="dl-row"><dt>{{ $l }}</dt><dd class="font-semibold">{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
      @if(!empty($cabecera->PEDIDO))
        <p class="text-xs text-slate-400 mt-3">Pedido destino:
          <a href="{{ route('pedidos.detalle', $cabecera->PEDIDO) }}" class="text-blue-600 hover:underline font-mono font-semibold">{{ $cabecera->PEDIDO }}</a>
        </p>
      @endif
      @if(!empty($cabecera->TIQUET))
        <p class="text-xs text-slate-400 mt-1">Albarán destino:
          <a href="{{ route('albaranes.detalle', $cabecera->TIQUET) }}" class="text-blue-600 hover:underline font-mono font-semibold">{{ $cabecera->TIQUET }}</a>
        </p>
      @endif
      @if($cabecera->CLIENTE ?? false)
        <div class="mt-3 pt-3 border-t border-slate-100">
          <a href="{{ route('clientes.detalle', $cabecera->CLI_CODIGO ?? $cabecera->CLIENTE) }}" class="text-xs text-blue-600 hover:underline">Ver ficha del cliente →</a>
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
    <div class="table-wrapper" style="overflow-x:hidden">
      <table class="crm-table" style="table-layout:fixed; width:100%">
        <colgroup>
          <col style="width:100px">{{-- Artículo --}}
          <col>{{-- Descripción: ocupa el resto --}}
          <col style="width:70px">{{-- Cant. --}}
          <col style="width:90px">{{-- PVP --}}
          <col style="width:70px">{{-- Dto. % --}}
          <col style="width:100px">{{-- Importe --}}
        </colgroup>
        <thead><tr>
          <th>Artículo</th><th>Descripción</th>
          <th class="td-right">Cant.</th><th class="td-right">PVP</th>
          <th class="td-right">Dto.%</th><th class="td-right">Importe</th>
        </tr></thead>
        <tbody>
          @foreach($lineas as $lin)
          <tr>
            <td class="font-mono text-xs font-semibold">{{ $lin->ARTICULO ?? '—' }}</td>
            <td class="text-xs text-slate-700" style="word-break:break-word; white-space:normal">
              {{ $lin->DESCRIPCION ?? '—' }}
              @if(!empty($lin->COMENTARIO))
                <p class="text-xs text-slate-400 mt-0.5">{{ $lin->COMENTARIO }}</p>
              @endif
            </td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->CANTIDAD??0),2,',','.') }}</td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->PVP??0),2,',','.').' €' }}</td>
            <td class="td-right text-xs">{{ $lin->DTO ?? 0 }}%</td>
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

  {{-- Actualizar estado y comentario --}}
  <div class="crm-card p-5">
    <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Actualizar presupuesto</h3>
    <form method="POST" action="{{ route('presupuestos.update', $codigo) }}" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="form-label">Estado</label>
          <select name="estado" class="form-select">
            <option value="">— Sin cambio —</option>
            @foreach($estados as $est)
              @php $selVal = $estadoAct->ESTADO ?? ''; @endphp
              <option value="{{ $est->CODIGO }}" {{ $selVal == $est->CODIGO ? 'selected' : '' }}>
                {{ $est->DESCRIPCION ?? $est->DESCRIPCION_ESTADO ?? $est->CODIGO }}
              </option>
            @endforeach
          </select>
          @if(!empty($estadoAct->ESTADO))
            <p class="text-xs text-slate-400 mt-1">Estado actual: <strong>{{ $estadoAct->ESTADO }}</strong></p>
          @endif
        </div>
        <div class="flex items-end">
          <button type="submit" class="btn btn-primary w-full sm:w-auto">Guardar cambios</button>
        </div>
      </div>
      <div>
        <label class="form-label">Comentario comercial</label>
        <textarea name="comentario" rows="4" class="form-input" placeholder="Comentario sobre el presupuesto...">{{ old('comentario', $estadoAct->COMENTARIO_COMERCIAL_PRESU ?? '') }}</textarea>
      </div>
    </form>
  </div>

</div>
@endsection
