@if(empty($solicitudes))
  <div class="empty-state">Sin solicitudes de presupuesto.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Código</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Descripción</th>
        <th>Carácter</th>
        <th>Presupuesto</th>
        <th>Categoría</th>
      </tr>
    </thead>
    <tbody>
      @foreach($solicitudes as $row)
      <tr>
        <td class="font-mono text-xs font-semibold">{{ $row->CODIGO ?? '—' }}</td>
        <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
        <td class="text-xs">{{ $row->HORA ?? '—' }}</td>
        <td class="text-sm max-w-xs truncate">{{ $row->DESCRIPCION ?? '—' }}</td>
        <td class="text-xs text-slate-500">{{ $row->DESC_CARACTER ?? '—' }}</td>
        <td class="font-mono text-xs">{{ $row->PRESUPUESTO ?? '—' }}</td>
        <td class="text-xs"><span class="badge badge-blue">{{ $row->DESCRIPCIONCATEGORIA ?? '—' }}</span></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
