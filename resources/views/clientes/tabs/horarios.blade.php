@if(empty($horarios))
  <div class="empty-state">Sin horarios registrados.</div>
@else
<div id="ct-horarios">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar horarios...">
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
          <th class="srt">Descripción <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Desde día <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Hasta día <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($horarios as $row)
        <tr>
          <td class="text-sm font-medium">{{ $row->DESCRIPCION ?? '—' }}</td>
          <td class="text-xs">{{ $row->DESDE_DIA ?? '—' }}</td>
          <td class="text-xs">{{ $row->HASTA_DIA ?? '—' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-horarios');</script>
@endif
