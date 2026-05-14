@if(empty($llamadas))
  <div class="empty-state">Sin llamadas registradas.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Teléfono</th>
        <th>Comentario</th>
      </tr>
    </thead>
    <tbody>
      @foreach($llamadas as $row)
      <tr>
        <td class="font-medium text-sm">{{ $row->FECHA ?? '—' }}</td>
        <td class="text-xs">{{ $row->HORA ?? '—' }}</td>
        <td class="text-xs font-mono">{{ $row->TELEFONO ?? '—' }}</td>
        <td class="text-xs text-slate-600 max-w-sm truncate">{{ $row->COMENTARIO ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
