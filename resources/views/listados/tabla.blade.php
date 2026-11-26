{{-- Tabla genérica para los listados de ListadosController::LISTADOS --}}
@php
  $fmt = function ($v, $tipo) {
      if ($v === null || $v === '') return '—';
      return match ($tipo) {
          'money' => number_format((float) $v, 2, ',', '.') . ' €',
          'int'   => number_format((float) $v, 0, ',', '.'),
          'date'  => ($t = strtotime((string) $v)) ? date('d/m/Y', $t) : $v,
          default => trim((string) $v),
      };
  };
  $totales = [];
  foreach ($columnas as [$campo, $etq, $tipo]) {
      if ($tipo === 'money') $totales[$campo] = array_sum(array_map(fn ($f) => (float) ($f->{$campo} ?? 0), $filas));
  }
@endphp

<p class="px-3 pt-2 text-xs text-slate-500">Periodo: {{ $periodo }}</p>

@if(!empty($resumen))
<div class="flex flex-wrap gap-2 p-3">
  @foreach($resumen as $etq => $valor)
    <div class="px-3 py-2 rounded-lg bg-slate-50 border border-slate-100 text-sm">
      <span class="text-slate-500">{{ $etq }}:</span> <strong>{{ $valor }}</strong>
    </div>
  @endforeach
</div>
@endif

@if($error)
  <div class="empty-state">No se pudo obtener el listado. Inténtalo de nuevo.</div>
@elseif(empty($filas))
  <div class="empty-state">Sin datos para el período seleccionado.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden p-1">
  <table class="crm-table">
    <thead>
      <tr>
        @foreach($columnas as [$campo, $etq, $tipo])
          <th class="{{ in_array($tipo, ['money','int']) ? 'td-right' : '' }}">{{ $etq }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($filas as $f)
      <tr>
        @foreach($columnas as [$campo, $etq, $tipo])
          @php $v = $f->{$campo} ?? null; @endphp
          @if($tipo === 'cliente' && $v !== null && $v !== '')
            <td class="font-mono text-xs"><a href="{{ route('clientes.detalle', trim((string) $v)) }}" class="text-blue-600 hover:underline">{{ trim((string) $v) }}</a></td>
          @else
            <td class="{{ in_array($tipo, ['money','int']) ? 'td-right font-mono text-sm' : 'text-sm' }}">{{ $fmt($v, $tipo) }}</td>
          @endif
        @endforeach
      </tr>
      @endforeach
    </tbody>
    @if(!empty($totales))
    <tfoot>
      <tr class="total-row">
        @foreach($columnas as $i => [$campo, $etq, $tipo])
          @if(isset($totales[$campo]))
            <td class="td-right font-mono font-bold">{{ $fmt($totales[$campo], 'money') }}</td>
          @else
            <td class="text-xs font-semibold text-slate-600">{{ $i === 0 ? 'Total (' . count($filas) . ')' : '' }}</td>
          @endif
        @endforeach
      </tr>
    </tfoot>
    @endif
  </table>
</div>
@endif
