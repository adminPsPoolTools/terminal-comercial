@extends('layouts.app')
@section('title','Objetivos') @section('page-title','Objetivos Comerciales')
@section('content')
@php
  $num = fn ($v, $d = 2) => number_format((float) ($v ?? 0), $d, ',', '.');
  $meses = [1=>'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
@endphp
<div class="space-y-4">

  <form method="GET" action="{{ route('objetivos.index') }}" class="crm-card p-4">
    <div class="flex flex-wrap gap-3 items-end">
      <div class="filter-group w-full sm:w-auto"><label class="form-label">Año</label>
        <select name="anio" class="form-select" style="min-width:110px" onchange="this.form.submit()">
          @for($a = (int) date('Y') + 1; $a >= (int) date('Y') - 5; $a--)
            <option value="{{ $a }}" @selected($a == $anio)>{{ $a }}</option>
          @endfor
        </select></div>
      @if($importeExtra !== null)
        <div class="text-sm text-slate-600 self-center">Comisión extra total: <strong>{{ $num($importeExtra) }} €</strong></div>
      @endif
    </div>
  </form>

  @if($error)
    <div class="crm-card"><div class="empty-state">No se pudieron cargar los objetivos. Inténtalo de nuevo.</div></div>
  @elseif(empty($resumen) && empty($mensual))
    <div class="crm-card"><div class="empty-state">Sin objetivos definidos para {{ $anio }}.</div></div>
  @else

  @if(!empty($resumen))
  <div class="crm-card overflow-hidden">
    <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Resumen {{ $anio }}</h3>
    <div class="table-wrapper">
      <table class="crm-table">
        <thead>
          <tr>
            <th>Comercial</th><th>Objetivo</th>
            <th class="td-right">Objetivo</th><th class="td-right">Vendido</th><th class="td-right">Diferencia</th>
            <th class="td-right">Incentivo</th><th class="td-right">Inc. extra</th><th>Progreso</th>
          </tr>
        </thead>
        <tbody>
          @foreach($resumen as $row)
            @php
              $obj = (float) ($row->OBJETIVO ?? 0);
              $pct = $obj > 0 ? round(((float) ($row->VENDIDOS ?? 0) / $obj) * 100, 1) : 0;
              $color = $pct >= 100 ? '#10b981' : ($pct >= 75 ? '#f59e0b' : '#ef4444');
            @endphp
            <tr>
              <td class="text-sm">{{ $row->NOMBRE ?? '—' }}</td>
              <td class="font-medium text-sm">{{ $row->OBJETIVO_DESCRIPCION ?? '—' }}</td>
              <td class="td-right font-mono">{{ $num($row->OBJETIVO) }}</td>
              <td class="td-right font-mono font-semibold">{{ $num($row->VENDIDOS) }}</td>
              <td class="td-right font-mono {{ (float) ($row->DIFERENCIA ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $num($row->DIFERENCIA) }}</td>
              <td class="td-right font-mono">{{ $num($row->INCENTIVO) }}</td>
              <td class="td-right font-mono">{{ $num($row->INCENTIVO_EXTRA) }}</td>
              <td style="min-width:140px">
                <div class="flex items-center gap-2">
                  <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="h-2 rounded-full" style="width:{{ min(100, $pct) }}%;background:{{ $color }}"></div>
                  </div>
                  <span class="font-mono text-xs font-bold" style="color:{{ $color }}">{{ $pct }}%</span>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

  @foreach($mensual as $objetivo => $filas)
  <div class="crm-card overflow-hidden">
    <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">{{ $objetivo }} · detalle mensual</h3>
    <div class="table-wrapper">
      <table class="crm-table">
        <thead>
          <tr>
            <th>Mes</th><th>Comercial</th>
            <th class="td-right">Objetivo</th><th class="td-right">Vendido</th><th class="td-right">Diferencia</th>
            <th class="td-right">% año ant.</th><th class="td-right">Incentivo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($filas as $row)
            @php $m = (int) ($row->MES_NOMBRE ?? 0); @endphp
            <tr>
              <td class="text-sm">{{ $meses[$m] ?? ($row->MES_NOMBRE ?? '—') }}</td>
              <td class="text-xs text-slate-500">{{ $row->NOMBRE ?? '—' }}</td>
              <td class="td-right font-mono">{{ $num($row->OBJETIVO) }}</td>
              <td class="td-right font-mono font-semibold">{{ $num($row->VENDIDOS) }}</td>
              <td class="td-right font-mono {{ (float) ($row->DIFERENCIA ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $num($row->DIFERENCIA) }}</td>
              <td class="td-right font-mono">{{ $num($row->PC_INC_ANYO_ANTERIOR) }}%</td>
              <td class="td-right font-mono">{{ $num($row->INCENTIVO) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endforeach

  @endif
</div>
@endsection
