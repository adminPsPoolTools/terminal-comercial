@extends('layouts.app')
@section('title', 'Pedido ' . $codigo)
@section('page-title', 'Pedido ' . ($cabecera->PED_CODIGO ?? $codigo))

@section('content')
<div class="space-y-4 max-w-5xl">

  {{-- Header --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Pedido · <span class="font-mono font-semibold text-slate-600">{{ $cabecera->PED_CODIGO ?? $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900 leading-tight">{{ $cabecera->TITULO ?? '—' }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        Fecha: <strong>{{ $cabecera->PED_FECHA ?? '—' }}</strong>
        @if(!empty($cabecera->FECHASERVIR)) · A servir: <strong>{{ $cabecera->FECHASERVIR }}</strong> @endif
        · Vendedor: <strong>{{ $cabecera->VENDEDOR ?? '—' }}</strong>
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('pedidos.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

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
          ['Total',      number_format((float)($cabecera->TOTAL??0),2,',','.').' €'],
          ['Estado',     $cabecera->DESCRIPCION_ESTADO ?? $cabecera->ESTADO ?? ''],
          ['Servido',    match($cabecera->SERVIDO??'') {'T'=>'Total','P'=>'Parcial','N'=>'Nada',default=>'—'}],
        ] as [$l,$v]) @if($v && $v !== '—')
        <div class="dl-row"><dt>{{ $l }}</dt><dd class="font-semibold">{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
      @if($cabecera->CLI_CODIGO ?? false)
        <div class="mt-3 pt-3 border-t border-slate-100">
          <a href="{{ route('clientes.detalle', $cabecera->CLI_CODIGO) }}" class="text-xs text-blue-600 hover:underline">Ver ficha del cliente →</a>
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
          <col style="width:100px">
          <col>
          <col style="width:70px">
          <col style="width:70px">
          <col style="width:90px">
          <col style="width:70px">
          <col style="width:100px">
        </colgroup>
        <thead><tr>
          <th>Artículo</th><th>Descripción</th>
          <th class="td-right">Cant.</th><th class="td-right">Servida</th>
          <th class="td-right">PVP</th><th class="td-right">Dto.%</th><th class="td-right">Importe</th>
        </tr></thead>
        <tbody>
          @foreach($lineas as $lin)
          <tr>
            <td class="font-mono text-xs font-semibold">{{ $lin->ARTICULO ?? '—' }}</td>
            <td class="text-xs text-slate-700" style="word-break:break-word; white-space:normal">{{ $lin->DESCRIPCION ?? '—' }}</td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->CANTIDAD??0),2,',','.') }}</td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->SERVIDA??0),2,',','.') }}</td>
            <td class="td-right text-xs font-mono">{{ number_format((float)($lin->PVP??0),2,',','.').' €' }}</td>
            <td class="td-right text-xs">{{ $lin->DTO ?? 0 }}%</td>
            <td class="td-right text-xs font-mono font-semibold">{{ number_format((float)($lin->IMPORTE??0),2,',','.').' €' }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="6" class="text-right text-xs text-slate-500 pr-4">Total</td>
            <td class="td-right font-mono font-bold text-sm">{{ number_format(array_sum(array_map(fn($l)=>(float)($l->IMPORTE??0),$lineas)),2,',','.').' €' }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
    @endif
  </div>

</div>
@endsection
