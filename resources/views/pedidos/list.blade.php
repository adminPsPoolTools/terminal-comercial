@if(empty($pedidos))
  <div class="empty-state">Sin pedidos para los filtros seleccionados.</div>
@else
<div id="ct-pedidos">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar en pedidos...">
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
          <th class="srt">Servido <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right">Base Imp. <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($pedidos as $row)
          @if(!is_null($row->CODIGO ?? null))
          <tr>
            <td class="font-mono text-xs font-semibold">
              <a href="{{ route('pedidos.detalle', $row->CODIGO) }}" class="text-blue-600 hover:underline">
                {{ $row->CODIGO }}
              </a>
            </td>
            <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
            <td class="text-xs">
              <a href="{{ route('clientes.detalle', $row->CLIENTE ?? 0) }}" class="text-blue-600 hover:underline">
                {{ $row->DESCRIPCION_CLIENTE ?? $row->CLIENTE ?? '—' }}
              </a>
            </td>
            <td class="max-w-xs truncate text-sm">{{ $row->TITULO ?? '—' }}</td>
            <td><span class="badge badge-blue">{{ $row->DESCRIPCION_ESTADO ?? $row->ESTADO ?? '—' }}</span></td>
            <td>
              <span class="badge {{ ($row->SERVIDO??'')==='T' ? 'badge-green' : (($row->SERVIDO??'')==='N' ? 'badge-red' : 'badge-yellow') }}">
                {{ match($row->SERVIDO??'') {'T'=>'Total','N'=>'Nada','P'=>'Parcial',default=>'—'} }}
              </span>
            </td>
            <td class="td-right font-mono text-sm font-semibold">{{ number_format($row->BASEIMPONIBLE ?? 0, 2, ',', '.') }}€</td>
          </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-pedidos');</script>
@endif
