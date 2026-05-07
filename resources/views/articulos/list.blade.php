@if(empty($articulos))
  <div class="empty-state">Sin artículos para la búsqueda indicada.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Descripción</th><th>Familia</th><th class="td-right">Precio</th><th class="td-right">Stock</th></tr></thead>
    <tbody>
      @foreach($articulos as $row)
        @if(!is_null($row->CODIGO ?? null))
        <tr>
          <td class="font-mono text-xs font-semibold text-blue-600">{{ $row->CODIGO }}</td>
          <td class="font-medium">{{ $row->DESCRIPCION ?? '—' }}</td>
          <td class="text-xs text-slate-500">{{ $row->FAMILIA ?? '—' }}</td>
          <td class="td-right font-mono text-sm">{{ number_format($row->PRECIO ?? 0, 2, ',', '.') }}€</td>
          <td class="td-right font-mono text-sm">{{ $row->STOCK ?? '—' }}</td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
@endif
