@if(empty($contactos))
  <div class="empty-state">Sin contactos registrados.</div>
@else
<div id="ct-contactos">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar contactos...">
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
          <th class="srt">Nombre <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Cargo <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Tel. fijo <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Tel. móvil <span class="sa text-slate-300">↕</span></th>
          <th class="srt">Email <span class="sa text-slate-300">↕</span></th>
          <th>Comentario</th>
          <th class="td-center">Por defecto</th>
        </tr>
      </thead>
      <tbody>
        @foreach($contactos as $row)
        <tr>
          <td class="font-medium">{{ $row->NOMBRE ?? '—' }}</td>
          <td class="text-xs text-slate-500">{{ $row->CARGO ?? '—' }}</td>
          <td class="text-xs">{{ $row->TELEFONOFIJO ?? '—' }}</td>
          <td class="text-xs">{{ $row->TELEFONOMOVIL ?? '—' }}</td>
          <td class="text-xs">
            @if(!empty($row->CORREO))
              <a href="mailto:{{ $row->CORREO }}" class="text-blue-600 hover:underline">{{ $row->CORREO }}</a>
            @else —
            @endif
          </td>
          <td class="text-xs text-slate-500 max-w-xs truncate">{{ $row->COMENTARIO_CONTACTO ?? '—' }}</td>
          <td class="td-center">
            @if(($row->CONTACTO_POR_DEFECTO ?? '') === 'S')
              <span class="badge badge-green">Sí</span>
            @else
              <span class="text-slate-300">—</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<script>window.initCrmTable && window.initCrmTable('ct-contactos');</script>
@endif
