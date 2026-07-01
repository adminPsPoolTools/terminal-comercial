@if(empty($gastos))
  <div class="empty-state">Sin gastos para los filtros seleccionados.</div>
@else
@php $total = 0; @endphp
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Fecha</th><th>Tipo</th><th>Comentario</th><th>Medio cobro</th><th>Pagado</th><th class="td-right">Importe</th></tr></thead>
    <tbody>
      @foreach($gastos as $row)
        @if(!is_null($row->CODIGO ?? null))
        @php $total += $row->IMPORTE ?? 0; @endphp
        <tr>
          <td class="font-mono text-xs">{{ $row->CODIGO }}</td>
          <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
          <td class="text-xs text-slate-500">{{ $row->GASTO ?? '—' }}</td>
          <td class="max-w-xs truncate text-sm">{{ $row->COMENTARIO ?? '—' }}</td>
          <td class="text-xs">{{ $row->MEDIOCOBRO ?? '—' }}</td>
          <td><span class="badge {{ ($row->PAGADO??'N')==='S' ? 'badge-green' : 'badge-yellow' }}">{{ ($row->PAGADO??'N')==='S' ? 'Sí' : 'No' }}</span></td>
          <td class="td-right font-mono text-sm font-semibold">{{ number_format($row->IMPORTE ?? 0, 2, ',', '.') }}€</td>
        </tr>
        @endif
      @endforeach
    </tbody>
    <tfoot><tr class="total-row"><td colspan="6" class="td-right font-semibold text-xs text-slate-600 pr-4">Total</td><td class="td-right font-mono font-bold">{{ number_format($total, 2, ',', '.') }}€</td></tr></tfoot>
  </table>
</div>
@endif
