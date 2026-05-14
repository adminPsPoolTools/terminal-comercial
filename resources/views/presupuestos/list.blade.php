@php $total = 0; $totalIva = 0; @endphp

@if(empty($presupuestos))
  <div class="empty-state">Sin presupuestos para los filtros seleccionados.</div>
@else
  <div class="table-wrapper rounded-xl overflow-hidden">
    <table class="crm-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Título</th>
          <th>Estado</th>
          <th>Comercial</th>
          <th class="td-right">Base Imp.</th>
          <th class="td-right">C/IVA</th>
        </tr>
      </thead>
      <tbody>
        @foreach($presupuestos as $row)
          @if(!is_null($row->CODIGO ?? null))
          @php
            $total    += $row->BASEIMPONIBLE ?? 0;
            $totalIva += $row->TOTAL ?? 0;
          @endphp
          <tr>
            <td class="font-mono text-xs">
              <a href="/presupuestos/{{ $row->CODIGO }}" class="text-blue-600 hover:underline font-semibold">
                {{ $row->CODIGO }}
              </a>
            </td>
            <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
            <td class="max-w-xs">
              <a href="/clientes/{{ $row->CLIENTE ?? '' }}" class="text-blue-600 hover:underline text-xs">
                {{ $row->DESCRIPCION_CLIENTE ?? $row->CLIENTE ?? '—' }}
              </a>
            </td>
            <td class="text-xs text-slate-600 max-w-xs truncate">{{ $row->TITULO ?? '—' }}</td>
            <td>
              @php
                $est = $row->DESCRIPCION_ESTADO ?? $row->ESTADO ?? '';
                $cls = match(true) {
                  str_contains(strtolower($est), 'acept') => 'badge-green',
                  str_contains(strtolower($est), 'rechaz') => 'badge-red',
                  str_contains(strtolower($est), 'espera') => 'badge-yellow',
                  default => 'badge-gray',
                };
              @endphp
              <span class="badge {{ $cls }}">{{ $est ?: '—' }}</span>
            </td>
            <td class="text-xs text-slate-500">{{ $row->USUARIO_ALTA ?? '—' }}</td>
            <td class="td-right font-mono text-sm">{{ number_format($row->BASEIMPONIBLE ?? 0, 2, ',', '.') }}€</td>
            <td class="td-right font-mono text-sm font-semibold">{{ number_format($row->TOTAL ?? 0, 2, ',', '.') }}€</td>
          </tr>
          @endif
        @endforeach
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="6" class="text-right font-semibold text-slate-600 text-xs uppercase tracking-wide pr-4">Total</td>
          <td class="td-right font-mono font-bold">{{ number_format($total, 2, ',', '.') }}€</td>
          <td class="td-right font-mono font-bold">{{ number_format($totalIva, 2, ',', '.') }}€</td>
        </tr>
      </tfoot>
    </table>
  </div>
@endif
