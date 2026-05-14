@if(empty($expedientes))
  <div class="empty-state">Sin expedientes para los filtros seleccionados.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Fecha</th><th>Descripción</th><th>Estado</th><th>Cliente presup.</th><th>Cliente asig.</th><th>Comercial</th><th>Provincia</th></tr></thead>
    <tbody>
      @foreach($expedientes as $row)
        @if(!is_null($row->CODIGO ?? null))
        <tr>
          <td><span class="font-mono text-xs font-semibold text-slate-700">{{ $row->CODIGO }}</span></td>
          <td class="text-xs">{{ $row->FECHA_ALTA ?? '—' }}</td>
          <td class="max-w-xs truncate text-sm font-medium">{{ $row->DESCRIPCION ?? '—' }}</td>
          <td><span class="badge {{ str_contains(strtolower($row->ESTADO ?? ''), 'abiert') ? 'badge-green' : (str_contains(strtolower($row->ESTADO ?? ''), 'cerr') ? 'badge-gray' : 'badge-yellow') }}">{{ $row->ESTADO ?? '—' }}</span></td>
          <td class="text-xs text-slate-600">{{ $row->DESCRIPCION_CLIENTE_PRESU ?? $row->CLIENTE_PRESU ?? '—' }}</td>
          <td class="text-xs text-slate-600">{{ $row->DESCRIPCION_CLIENTE_ASIG ?? $row->CLIENTE_ASIG ?? '—' }}</td>
          <td class="text-xs text-slate-500">{{ $row->NOMBRE_COMERCIAL ?? $row->COMERCIAL ?? '—' }}</td>
          <td class="text-xs text-slate-500">{{ $row->PROVINCIA ?? '—' }}</td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
@endif
