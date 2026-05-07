@if(empty($agendas) || (count($agendas) === 1 && is_null($agendas[0]->CODIGO ?? null)))
  <div class="empty-state">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-3 text-slate-300"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    Sin registros
  </div>
@else
  <div class="table-wrapper rounded-xl overflow-hidden">
    <table class="crm-table">
      <thead>
        <tr>
          <th>Cód.</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Título</th>
          <th>Usuario</th>
          <th>Estado</th>
          <th>F. Prog.</th>
          <th>Días</th>
          <th>Expediente</th>
        </tr>
      </thead>
      <tbody>
        @foreach($agendas as $row)
          @if(!is_null($row->CODIGO ?? null))
          <tr>
            <td class="font-mono text-xs text-slate-400">{{ $row->CODIGO }}</td>
            <td class="font-medium">{{ $row->FECHA ?? '—' }}</td>
            <td>{{ $row->HORA ?? '—' }}</td>
            <td class="max-w-xs truncate font-medium">
              <a href="/agenda/{{ $row->CODIGO }}" class="text-blue-600 hover:underline">
                {{ $row->TITULO ?? '—' }}
              </a>
            </td>
            <td class="text-slate-500 text-xs">{{ $row->USUARIO ?? '—' }}</td>
            <td>
              @php
                $estado = $row->ESTADO ?? '';
                $class = match(true) {
                  str_contains(strtolower($estado), 'realiz') => 'badge-green',
                  str_contains(strtolower($estado), 'pendi')  => 'badge-yellow',
                  str_contains(strtolower($estado), 'cancel') => 'badge-red',
                  default => 'badge-gray',
                };
              @endphp
              <span class="badge {{ $class }}">{{ $estado ?: '—' }}</span>
            </td>
            <td class="text-slate-500 text-xs">{{ $row->FECHA_PROG ?? '—' }}</td>
            <td class="td-right">
              @if(isset($row->CONTADOR_DIAS) && $row->CONTADOR_DIAS > 0)
                <span class="badge {{ $row->CONTADOR_DIAS > 30 ? 'badge-red' : 'badge-yellow' }}">
                  {{ $row->CONTADOR_DIAS }}d
                </span>
              @else
                <span class="text-slate-300">—</span>
              @endif
            </td>
            <td class="text-xs">
              @if(!empty($row->EXPEDIENTE))
                <span class="badge badge-blue">{{ $row->EXPEDIENTE }}</span>
              @else
                <span class="text-slate-300">—</span>
              @endif
            </td>
          </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
@endif
