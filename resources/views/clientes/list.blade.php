@php $totalImp = 0; @endphp

@if(empty($clientes))
  <div class="empty-state">Sin clientes para los filtros seleccionados.</div>
@else

@php foreach($clientes as $row) { if(!is_null($row->CODIGO ?? null)) $totalImp += $row->TIMPORTEBASE ?? 0; } @endphp

<div id="ct-clientes">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar clientes...">
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
          <th class="srt">Cliente <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Teléfono <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Categoría <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Importe <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($clientes as $row)
          @if(!is_null($row->CODIGO ?? null))
          <tr>
            <td class="font-mono text-xs text-slate-400">{{ $row->CODIGO }}</td>
            <td class="font-medium">
              <a href="{{ route('clientes.detalle', $row->CODIGO) }}" class="text-blue-600 hover:underline">{{ $row->DESCRIPCION ?? '—' }}</a>
            </td>
            <td class="text-xs text-slate-500">{{ $row->TELEFONOFIJO ?? '' }}{{ $row->TELEFONOFIJO && $row->TELEFONOMOVIL ? ' · ' : '' }}{{ $row->TELEFONOMOVIL ?? '' }}</td>
            <td class="text-xs"><span class="badge badge-blue">{{ $row->DESCRIPCIONCATEGORIA ?? '—' }}</span></td>
            <td class="td-right font-mono text-sm">{{ number_format($row->TIMPORTEBASE ?? 0, 2, ',', '.') }}€</td>
          </tr>
          @endif
        @endforeach
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3"></td>
          <td class="font-semibold text-xs text-slate-600 text-right pr-2">Total</td>
          <td class="td-right font-mono font-bold">{{ number_format($totalImp, 2, ',', '.') }}€</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-clientes');</script>

@endif
