@if(empty($albaranes))
  <div class="empty-state">Sin albaranes para el período seleccionado.</div>
@else
<div class="px-4 pt-3 pb-1 flex justify-end">
  <span class="text-xs text-slate-400">Desde: <strong>{{ $fechaDesde }}</strong></span>
</div>
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Código</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Título</th>
        <th>Cliente</th>
        <th class="td-right">Base imp.</th>
        <th class="td-right">Total c/IVA</th>
      </tr>
    </thead>
    <tbody>
      @foreach($albaranes as $row)
      <tr>
        <td class="font-mono text-xs font-semibold">{{ $row->CODIGO ?? '—' }}</td>
        <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
        <td>
          @php
            $est = strtolower($row->ESTADO ?? '');
            $cls = str_contains($est,'factur') ? 'badge-green' : (str_contains($est,'pendi') ? 'badge-yellow' : 'badge-gray');
          @endphp
          <span class="badge {{ $cls }}">{{ $row->ESTADO ?? '—' }}</span>
        </td>
        <td class="text-sm max-w-xs truncate">{{ $row->TITULO ?? '—' }}</td>
        <td class="text-xs text-slate-500">{{ $row->DESCRIPCION_CLIENTE ?? '—' }}</td>
        <td class="td-right text-xs font-mono">{{ number_format((float)($row->BASEIMPONIBLE ?? 0), 2, ',', '.') }} €</td>
        <td class="td-right text-xs font-mono font-semibold">{{ number_format((float)($row->IMPORTECONIVA ?? 0), 2, ',', '.') }} €</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
