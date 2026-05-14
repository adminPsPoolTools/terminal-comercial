@if(empty($direcciones))
  <div class="empty-state">Sin direcciones registradas.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Contacto</th>
        <th>Dirección</th>
        <th>CP</th>
        <th>Población</th>
        <th>Provincia</th>
        <th>Tel. fijo</th>
        <th>Tel. móvil</th>
        <th>Descripción</th>
      </tr>
    </thead>
    <tbody>
      @foreach($direcciones as $row)
      <tr>
        <td class="font-medium">{{ $row->NOMBRE ?? '—' }}</td>
        <td class="text-xs text-slate-500">{{ $row->CONTACTO ?? '—' }}</td>
        <td class="text-xs">{{ $row->DIRECCION ?? '—' }}</td>
        <td class="text-xs font-mono">{{ $row->CP ?? '—' }}</td>
        <td class="text-xs">{{ $row->POBLACION ?? '—' }}</td>
        <td class="text-xs text-slate-500">{{ $row->PROVINCIA ?? '—' }}</td>
        <td class="text-xs">{{ $row->TELEFONOFIJO ?? '—' }}</td>
        <td class="text-xs">{{ $row->TELEFONOMOVIL ?? '—' }}</td>
        <td class="text-xs text-slate-500 max-w-xs truncate">{{ $row->DESCRIPCION ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
