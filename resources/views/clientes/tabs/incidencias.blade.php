@if(empty($incidencias))
  <div class="empty-state">Sin incidencias registradas.</div>
@else
<div id="ct-incidencias">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar incidencias...">
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
          <th class="srt">Línea <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Fecha <span class="sa text-slate-300">↕</span></th>
          <th>Incidencia</th>
          <th class="srt">Resuelta <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Comunicada <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($incidencias as $row)
        <tr>
          <td class="font-mono text-xs font-semibold">
            <a href="#" class="text-blue-600 hover:underline" title="Detalle incidencia {{ $row->LINEA ?? '' }}">
              {{ $row->LINEA ?? '—' }}
            </a>
          </td>
          <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
          <td class="text-sm max-w-sm truncate">{{ $row->INCIDENCIA ?? '—' }}</td>
          <td>
            <span class="badge {{ ($row->RESUELTA ?? '') === 'S' ? 'badge-green' : 'badge-yellow' }}">
              {{ ($row->RESUELTA ?? '') === 'S' ? 'Sí' : 'No' }}
            </span>
          </td>
          <td>
            <span class="badge {{ ($row->COMUNICADA ?? '') === 'S' ? 'badge-green' : 'badge-gray' }}">
              {{ ($row->COMUNICADA ?? '') === 'S' ? 'Sí' : 'No' }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-incidencias');</script>
@endif
