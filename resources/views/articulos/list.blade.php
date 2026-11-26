@if(empty($articulos))
  <div class="empty-state">Sin artículos para la búsqueda indicada.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Descripción</th></tr></thead>
    <tbody>
      @foreach($articulos as $row)
        @if(!is_null($row->CODIGO ?? null))
        <tr>
          <td class="font-mono text-xs font-semibold text-blue-600">{{ $row->CODIGO }}</td>
          <td class="font-medium">{{ $row->DESCRIPCION ?? '—' }}</td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
@if($pagina > 1 || $hayMas)
<div class="flex items-center justify-between gap-3 p-3 border-t border-slate-100 text-sm">
  <button type="button" class="btn btn-secondary btn-sm" onclick="buscar({{ $pagina - 1 }})" @disabled($pagina <= 1)>← Anterior</button>
  <span class="text-slate-500">Página {{ $pagina }}</span>
  <button type="button" class="btn btn-secondary btn-sm" onclick="buscar({{ $pagina + 1 }})" @disabled(! $hayMas)>Siguiente →</button>
</div>
@endif
@endif
