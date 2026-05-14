@if(empty($albaranes))
  <div class="empty-state">Sin albaranes para el período seleccionado.</div>
@else
<div id="ct-albaranes">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar albaranes...">
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
          <th class="srt">Código <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Fecha <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Estado <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Título <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Base imp. <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Total c/IVA <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($albaranes as $row)
        <tr>
          <td class="font-mono text-xs font-semibold">
            <a href="#" class="text-blue-600 hover:underline" title="Detalle albarán {{ $row->CODIGO ?? '' }}">
              {{ $row->CODIGO ?? '—' }}
            </a>
          </td>
          <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
          <td>
            @php
              $est = strtolower($row->ESTADO ?? '');
              $cls = str_contains($est,'factur') ? 'badge-green' : (str_contains($est,'pendi') ? 'badge-yellow' : 'badge-gray');
            @endphp
            <span class="badge {{ $cls }}">{{ $row->ESTADO ?? '—' }}</span>
          </td>
          <td class="text-sm max-w-xs truncate">{{ $row->TITULO ?? '—' }}</td>
          <td class="td-right text-xs font-mono">{{ number_format((float)($row->BASEIMPONIBLE ?? 0), 2, ',', '.') }} €</td>
          <td class="td-right text-xs font-mono font-semibold">{{ number_format((float)($row->IMPORTECONIVA ?? 0), 2, ',', '.') }} €</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-albaranes');</script>
@endif
