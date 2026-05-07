@if(empty($clientes))
  <div class="empty-state">Sin clientes para los filtros seleccionados.</div>
@else
@php $totalImp=0; $totalBen=0; $nBen=0; @endphp
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Cliente</th><th>Teléfono</th><th>Categoría</th><th class="td-right">Importe</th><th class="td-right">Beneficio</th></tr></thead>
    <tbody>
      @foreach($clientes as $row)
        @if(!is_null($row->CODIGO ?? null))
        @php
          $totalImp += $row->TIMPORTEBASE ?? 0;
          $ben = ($row->TIMPORTEBASE != 0) ? (($row->MARGEN*100)/$row->TIMPORTEBASE) : 0;
          if($ben) { $totalBen+=$ben; $nBen++; }
        @endphp
        <tr>
          <td class="font-mono text-xs text-slate-400">{{ $row->CODIGO }}</td>
          <td class="font-medium"><a href="/clientes/{{ $row->CODIGO }}" class="text-blue-600 hover:underline">{{ $row->DESCRIPCION ?? '—' }}</a></td>
          <td class="text-xs text-slate-500">{{ $row->TELEFONOFIJO ?? '' }}{{ $row->TELEFONOFIJO && $row->TELEFONOMOVIL ? ' · ' : '' }}{{ $row->TELEFONOMOVIL ?? '' }}</td>
          <td class="text-xs"><span class="badge badge-blue">{{ $row->DESCRIPCIONCATEGORIA ?? '—' }}</span></td>
          <td class="td-right font-mono text-sm">{{ number_format($row->TIMPORTEBASE ?? 0, 2, ',', '.') }}€</td>
          <td class="td-right font-mono text-sm">{{ round($ben, 2) }}%</td>
        </tr>
        @endif
      @endforeach
    </tbody>
    <tfoot><tr class="total-row"><td colspan="3"></td><td class="font-semibold text-xs text-slate-600 text-right pr-2">Total</td><td class="td-right font-mono font-bold">{{ number_format($totalImp,2,',','.') }}€</td><td class="td-right font-mono font-bold">{{ $nBen > 0 ? round($totalBen/$nBen,2) : 0 }}%</td></tr></tfoot>
  </table>
</div>
@endif
