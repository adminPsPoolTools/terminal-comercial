@if(empty($visitas))
  <div class="empty-state">Sin visitas registradas.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Código</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Tipo</th>
        <th>Motivo</th>
        <th>Comentario</th>
        <th>Vendedor</th>
      </tr>
    </thead>
    <tbody>
      @foreach($visitas as $row)
      <tr>
        <td class="font-mono text-xs text-slate-400">{{ $row->CODIGO ?? '—' }}</td>
        <td class="font-medium text-sm">{{ $row->FECHA ?? '—' }}</td>
        <td class="text-xs">{{ $row->HORA ?? '—' }}</td>
        <td class="text-xs"><span class="badge badge-blue">{{ $row->TIPO ?? '—' }}</span></td>
        <td class="text-xs text-slate-500">{{ $row->MOTIVO ?? '—' }}</td>
        <td class="text-xs text-slate-600 max-w-xs truncate">{{ $row->COMENTARIO ?? $row->COMENTARIO_MOTIVO ?? '—' }}</td>
        <td class="text-xs text-slate-500">{{ $row->VENDEDOR ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
