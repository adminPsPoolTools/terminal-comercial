@if(empty($incidencias))
  <div class="empty-state">Sin incidencias para los filtros seleccionados.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Descripción</th><th>Estado</th><th>Técnico</th></tr></thead>
    <tbody>
      @foreach($incidencias as $row)
        @if(!is_null($row->CODIGO ?? null))
        <tr>
          <td class="font-mono text-xs font-semibold">{{ $row->CODIGO }}</td>
          <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
          <td class="text-xs text-blue-600"><a href="{{ route('clientes.detalle', $row->C_CODIGO ?? 0) }}" class="hover:underline">{{ $row->CLIENTE ?? '—' }}</a></td>
          <td class="max-w-xs truncate text-sm">{{ $row->ARTICULO ?? '—' }}</td>
          <td><span class="badge {{ str_contains(strtolower($row->ESTADO??''),'abierta')?'badge-orange':(str_contains(strtolower($row->ESTADO??''),'cerrada')?'badge-green':'badge-gray') }}">{{ $row->ESTADO ?? '—' }}</span></td>
          <td class="text-xs text-slate-500">{{ $row->TECNICO ?? '—' }}</td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
@endif
