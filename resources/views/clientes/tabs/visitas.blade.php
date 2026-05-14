@if(empty($visitas))
  <div class="empty-state">Sin visitas comerciales registradas.</div>
@else
<div id="ct-visitas">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar en visitas...">
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
          <th class="srt">Hora <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Tipo <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Motivo <span class="sa text-slate-300">↕</span></th>
          <th>Comentario</th>
          <th class="srt">Vendedor <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($visitas as $row)
        <tr>
          <td class="font-mono text-xs font-semibold">
            <a href="{{ route('visitas.detalle', $row->CODIGO ?? 0) }}" class="text-blue-600 hover:underline">{{ $row->CODIGO ?? '—' }}</a>
          </td>
          <td class="font-medium text-sm">{{ $row->FECHA ?? '—' }}</td>
          <td class="text-xs">{{ $row->HORA ?? '—' }}</td>
          <td class="text-xs"><span class="badge badge-blue">{{ $row->TIPO ?? '—' }}</span></td>
          <td class="text-xs text-slate-500">{{ $row->MOTIVO ?? '—' }}</td>
          <td class="text-xs text-slate-600 max-w-xs truncate">{{ $row->COMENTARIO ?? $row->COMENTARIO_MOTIVO ?? '—' }}</td>
          <td class="text-xs text-slate-500">{{ $row->VENDEDOR ?? '—' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-visitas');</script>
@endif
