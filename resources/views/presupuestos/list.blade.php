@php
$total = 0;
$totalIva = 0;
$hideCliente = $hideCliente ?? false;
foreach($presupuestos as $row) {
if(!is_null($row->CODIGO ?? null)) {
$total += $row->BASE_IMP ?? $row->BASEIMPONIBLE ?? 0;
$totalIva += $row->IMP_C_IVA ?? $row->TOTAL ?? 0;
}
}
@endphp

@if(empty($presupuestos))
<div class="empty-state">Sin presupuestos para los filtros seleccionados.</div>
@else

<div id="ct-presup">
  <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100 bg-white">
    <div class="relative">
      <svg class="absolute left-2.5 top-2 w-4 h-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.35-4.35" />
      </svg>
      <input type="text" class="tab-search form-input pl-8 py-1.5 text-xs w-52" placeholder="Buscar en presupuestos...">
    </div>
    <span class="tab-count text-xs text-slate-400"></span>
    <div class="ml-auto flex items-center gap-2">
      <button class="btn-tab-prev btn btn-sm btn-secondary" disabled>‹ Ant.</button>
      <span class="tab-page-info text-xs text-slate-500 w-14 text-center font-medium"></span>
      <button class="btn-tab-next btn btn-sm btn-secondary">Sig. ›</button>
    </div>
  </div>
  <div class="table-wrapper" style="overflow-x:auto; -webkit-overflow-scrolling:touch">
    <table class="crm-table" style="min-width:640px">
      <thead>
        <tr>
          <th class="srt whitespace-nowrap" style="min-width:80px">Código <span class="sa text-slate-300">↕</span></th>
          <th class="srt whitespace-nowrap" style="min-width:85px">Fecha <span class="sa text-slate-300">↕</span></th>
          @if(!$hideCliente)
          <th class="srt" style="min-width:140px">Cliente <span class="sa text-slate-300">↕</span></th>
          @endif
          <th class="srt" style="min-width:160px; max-width:260px">Título <span class="sa text-slate-300">↕</span></th>
          <th class="srt whitespace-nowrap" style="min-width:100px">Estado <span class="sa text-slate-300">↕</span></th>
          <th class="srt" style="min-width:80px">Comercial <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right whitespace-nowrap" style="min-width:90px">Base Imp. <span class="sa text-slate-300">↕</span></th>
          <th class="srt td-right whitespace-nowrap" style="min-width:90px">C/IVA <span class="sa text-slate-300">↕</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($presupuestos as $row)
        @if(!is_null($row->CODIGO ?? null))
        <tr>
          <td class="font-mono text-xs font-semibold whitespace-nowrap">
            <a href="{{ route('presupuestos.detalle', $row->CODIGO) }}" class="text-blue-600 hover:underline">
              {{ $row->CODIGO }}
            </a>
          </td>
          <td class="text-xs whitespace-nowrap">{{ $row->FECHA ?? '—' }}</td>
          @if(!$hideCliente)
          <td class="text-xs" style="white-space:normal; word-break:break-word; max-width:160px">
            <a href="{{ route('clientes.detalle', $row->CLIENTE ?? 0) }}" class="text-blue-600 hover:underline">
              {{ $row->DESCRIPCION_CLIENTE ?? $row->CLIENTE ?? '—' }}
            </a>
          </td>
          @endif
          <td class="text-xs text-slate-600" style="white-space:normal; word-break:break-word; max-width:260px">{{ $row->TITULO ?? '—' }}</td>
          <td class="presup-estado whitespace-nowrap" data-codigo="{{ $row->CODIGO }}">
            <span class="text-slate-300 text-xs">·</span>
          </td>
          <td class="text-xs text-slate-500" style="white-space:normal; word-break:break-word; max-width:100px">{{ $row->NOMBRE_VENDEDOR ?? $row->VENDEDOR ?? $row->USUARIO_ALTA ?? '—' }}</td>
          <td class="td-right font-mono text-sm whitespace-nowrap">{{ number_format((float)($row->BASE_IMP ?? $row->BASEIMPONIBLE ?? 0), 2, ',', '.') }}€</td>
          <td class="td-right font-mono text-sm font-semibold whitespace-nowrap">{{ number_format((float)($row->IMP_C_IVA ?? $row->TOTAL ?? 0), 2, ',', '.') }}€</td>
        </tr>
        @endif
        @endforeach
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="{{ $hideCliente ? 5 : 6 }}"
            class="text-right font-semibold text-slate-600 text-xs uppercase tracking-wide pr-4">Total general</td>
          <td class="td-right font-mono font-bold">{{ number_format($total, 2, ',', '.') }}€</td>
          <td class="td-right font-mono font-bold">{{ number_format($totalIva, 2, ',', '.') }}€</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script>
var _estadoCells = {};
document.querySelectorAll('#ct-presup .presup-estado').forEach(function(td) {
  if (td.dataset.codigo) _estadoCells[td.dataset.codigo] = td;
});

window.initCrmTable && window.initCrmTable('ct-presup');

(function() {
  var codigos = Object.keys(_estadoCells);
  if (!codigos.length) return;
  var base = '{{ rtrim(url("/"), "/") }}';
  $.get(base + '/presupuestos/estados/batch', { codigos: codigos.join(',') }, function(data) {
    Object.keys(data).forEach(function(cod) {
      if (_estadoCells[cod]) _estadoCells[cod].innerHTML = data[cod];
    });
  });
})();
</script>

@endif