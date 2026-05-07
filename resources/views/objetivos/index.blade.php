@extends('layouts.app')
@section('title','Objetivos') @section('page-title','Objetivos Comerciales')
@section('content')
<div class="space-y-4">

  @if(empty($objetivos))
    <div class="crm-card"><div class="empty-state">Sin datos de objetivos disponibles.</div></div>
  @else
  <div class="crm-card overflow-hidden">
    <div class="table-wrapper">
      <table class="crm-table">
        <thead>
          <tr>
            <th>Comercial</th>
            <th>Producto</th>
            <th class="td-right">Objetivo</th>
            <th class="td-right">Realizado</th>
            <th class="td-right">% Cumpl.</th>
            <th>Progreso</th>
          </tr>
        </thead>
        <tbody>
          @foreach($objetivos as $row)
            @if(!is_null($row->CODIGO ?? null))
            @php
              $pct = $row->OBJETIVO > 0 ? min(100, round(($row->REALIZADO / $row->OBJETIVO) * 100, 1)) : 0;
              $color = $pct >= 100 ? '#10b981' : ($pct >= 75 ? '#f59e0b' : '#ef4444');
            @endphp
            <tr>
              <td class="font-medium text-sm">{{ $row->NOMBRE_COMERCIAL ?? $row->COMERCIAL ?? '—' }}</td>
              <td class="text-xs text-slate-500">{{ $row->DESCRIPCION_PRODUCTO ?? $row->PRODUCTO ?? '—' }}</td>
              <td class="td-right font-mono">{{ number_format($row->OBJETIVO ?? 0, 0, ',', '.') }}</td>
              <td class="td-right font-mono font-semibold">{{ number_format($row->REALIZADO ?? 0, 0, ',', '.') }}</td>
              <td class="td-right">
                <span class="font-mono font-bold" style="color:{{ $color }}">{{ $pct }}%</span>
              </td>
              <td style="min-width:120px">
                <div class="w-full bg-slate-100 rounded-full h-2">
                  <div class="h-2 rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                </div>
              </td>
            </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

</div>
@endsection
