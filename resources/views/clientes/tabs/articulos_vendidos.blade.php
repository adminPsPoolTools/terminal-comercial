@if(empty($articulos))
  <div class="empty-state">Sin artículos vendidos para el período seleccionado.</div>
@else
<div class="px-4 pt-3 pb-1 flex justify-end">
  <span class="text-xs text-slate-400">Desde: <strong>{{ $fechaDesde }}</strong></span>
</div>
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Artículo</th>
        <th>Descripción</th>
        <th class="td-right">Cant. total</th>
        <th class="td-right">Importe base</th>
      </tr>
    </thead>
    <tbody>
      @foreach($articulos as $row)
      <tr>
        <td class="font-mono text-xs font-semibold">{{ $row->ARTICULO ?? '—' }}</td>
        <td class="text-sm">{{ $row->DARTICULO ?? '—' }}</td>
        <td class="td-right text-xs font-mono">{{ number_format((float)($row->TCANTIDAD ?? 0), 2, ',', '.') }}</td>
        <td class="td-right text-xs font-mono font-semibold">{{ number_format((float)($row->TIMPORTEBASE ?? 0), 2, ',', '.') }} €</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-semibold bg-slate-50">
        <td colspan="2" class="text-right text-xs text-slate-500 pr-4">Total</td>
        <td class="td-right text-xs font-mono">{{ number_format(array_sum(array_column(array_map(fn($r) => ['t' => (float)($r->TCANTIDAD ?? 0)], $articulos), 't')), 2, ',', '.') }}</td>
        <td class="td-right text-xs font-mono">{{ number_format(array_sum(array_column(array_map(fn($r) => ['t' => (float)($r->TIMPORTEBASE ?? 0)], $articulos), 't')), 2, ',', '.') }} €</td>
      </tr>
    </tfoot>
  </table>
</div>
@endif
