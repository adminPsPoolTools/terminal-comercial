@if(empty($contactos))
  <div class="empty-state">Sin contactos registrados.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Cargo</th>
        <th>Tel. fijo</th>
        <th>Tel. móvil</th>
        <th>Fax</th>
        <th>Email</th>
        <th>Comentario</th>
        <th>Por defecto</th>
      </tr>
    </thead>
    <tbody>
      @foreach($contactos as $row)
      <tr>
        <td class="font-medium">{{ $row->NOMBRE ?? '—' }}</td>
        <td class="text-xs text-slate-500">{{ $row->CARGO ?? '—' }}</td>
        <td class="text-xs">{{ $row->TELEFONOFIJO ?? '—' }}</td>
        <td class="text-xs">{{ $row->TELEFONOMOVIL ?? '—' }}</td>
        <td class="text-xs text-slate-400">{{ $row->FAX ?? '—' }}</td>
        <td class="text-xs">
          @if(!empty($row->CORREO))
            <a href="mailto:{{ $row->CORREO }}" class="text-blue-600 hover:underline">{{ $row->CORREO }}</a>
          @else —
          @endif
        </td>
        <td class="text-xs text-slate-500 max-w-xs truncate">{{ $row->COMENTARIO_CONTACTO ?? '—' }}</td>
        <td class="text-center">
          @if(($row->CONTACTO_POR_DEFECTO ?? '') === 'S')
            <span class="badge badge-green">Sí</span>
          @else
            <span class="text-slate-300">—</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
