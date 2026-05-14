@php $total = 0; $totalIva = 0; @endphp

@if(empty($presupuestos))
  <div class="empty-state">Sin presupuestos para los filtros seleccionados.</div>
@else

@php foreach($presupuestos as $row) { if(!is_null($row->CODIGO??null)){ $total += $row->BASEIMPONIBLE??0; $totalIva += $row->TOTAL??0; } } @endphp

<div id="ct-presup">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar en presupuestos...">
    </div>
    <span class="tab-count text-xs text-slate-400"></span>
    <div class="ml-auto flex items-center gap-2">
      <button class="btn-tab-prev btn btn-sm btn-secondary" disabled>‹ Ant.</button>
      <span class="tab-page-info text-xs text-slate-500 w-14 text-center font-medium"></span>
      <button class="btn-tab-next btn btn-sm btn-secondary">Sig. ›</button>
    </div>
  </div>
  <div class="table-wrapper">
    <table class="crm-table">
      <thead>
        <tr>
          <th class="srt">Código <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Fecha <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Cliente <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Título <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Estado <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Comercial <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Base Imp. <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">C/IVA <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($presupuestos as $row)
          @if(!is_null($row->CODIGO ?? null))
          <tr>
            <td class="font-mono text-xs font-semibold">
              <a href="{{ route('presupuestos.detalle', $row->CODIGO) }}" class="text-blue-600 hover:underline">
                {{ $row->CODIGO }}
              </a>
            </td>
            <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
            <td class="max-w-xs">
              <a href="{{ route('clientes.detalle', $row->CLIENTE ?? 0) }}" class="text-blue-600 hover:underline text-xs">
                {{ $row->DESCRIPCION_CLIENTE ?? $row->CLIENTE ?? '—' }}
              </a>
            </td>
            <td class="text-xs text-slate-600 max-w-xs truncate">{{ $row->TITULO ?? '—' }}</td>
            <td>
              @php
                $est = $row->DESCRIPCION_ESTADO ?? $row->ESTADO ?? '';
                $cls = match(true) {
                  str_contains(strtolower($est), 'acept')  => 'badge-green',
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
          <td colspan="6" class="text-right font-semibold text-slate-600 text-xs uppercase tracking-wide pr-4">Total general</td>
          <td class="td-right font-mono font-bold">{{ number_format($total, 2, ',', '.') }}€</td>
          <td class="td-right font-mono font-bold">{{ number_format($totalIva, 2, ',', '.') }}€</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-presup');</script>

@endif
