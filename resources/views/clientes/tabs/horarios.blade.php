@if(empty($horarios))
  <div class="empty-state">Sin horarios registrados.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Descripción</th>
        <th>Desde día</th>
        <th>Hasta día</th>
      </tr>
    </thead>
    <tbody>
      @foreach($horarios as $row)
      <tr>
        <td class="text-sm font-medium">{{ $row->DESCRIPCION ?? '—' }}</td>
        <td class="text-xs">{{ $row->DESDE_DIA ?? '—' }}</td>
        <td class="text-xs">{{ $row->HASTA_DIA ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
