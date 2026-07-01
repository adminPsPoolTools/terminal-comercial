@if(empty($articulos))
  <div class="empty-state">Sin artículos vendidos para el período seleccionado.</div>
@else
<div id="ct-articulos">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar artículos...">
    </div>
    <span class="tab-count text-xs text-slate-400"></span>
    <span class="text-xs text-slate-400">Desde: <strong>{{ $fechaDesde }}</strong></span>
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
          <th class="srt">Artículo <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Descripción <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Cant. <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Importe base <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($articulos as $row)
        <tr>
          <td class="font-mono text-xs font-semibold">
            <a href="{{ route('articulos.index') }}" class="text-blue-600 hover:underline" title="Ver artículo {{ $row->ARTICULO ?? '' }}">
              {{ $row->ARTICULO ?? '—' }}
            </a>
          </td>
          <td class="text-sm">{{ $row->DARTICULO ?? '—' }}</td>
          <td class="td-right text-xs font-mono">{{ number_format((float)($row->TCANTIDAD ?? 0), 2, ',', '.') }}</td>
          <td class="td-right text-xs font-mono font-semibold">{{ number_format((float)($row->TIMPORTEBASE ?? 0), 2, ',', '.') }} €</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-articulos');</script>
@endif
