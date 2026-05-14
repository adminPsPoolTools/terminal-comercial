@if(empty($incidencias))
  <div class="empty-state">Sin incidencias registradas.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Línea</th>
        <th>Fecha</th>
        <th>Incidencia</th>
        <th>Resuelta</th>
        <th>Comunicada</th>
      </tr>
    </thead>
    <tbody>
      @foreach($incidencias as $row)
      <tr>
        <td class="font-mono text-xs text-slate-400">{{ $row->LINEA ?? '—' }}</td>
        <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
        <td class="text-sm max-w-sm truncate">{{ $row->INCIDENCIA ?? '—' }}</td>
        <td>
          <span class="badge {{ ($row->RESUELTA ?? '') === 'S' ? 'badge-green' : 'badge-yellow' }}">
            {{ ($row->RESUELTA ?? '') === 'S' ? 'Sí' : 'No' }}
          </span>
        </td>
        <td>
          <span class="badge {{ ($row->COMUNICADA ?? '') === 'S' ? 'badge-green' : 'badge-gray' }}">
            {{ ($row->COMUNICADA ?? '') === 'S' ? 'Sí' : 'No' }}
          </span>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
