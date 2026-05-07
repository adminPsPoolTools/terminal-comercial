@if(empty($pedidos))
  <div class="empty-state">Sin pedidos para los filtros seleccionados.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden">
  <table class="crm-table">
    <thead><tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Título</th><th>Estado</th><th>Servido</th><th class="td-right">Base Imp.</th></tr></thead>
    <tbody>
      @foreach($pedidos as $row)
        @if(!is_null($row->CODIGO ?? null))
        <tr>
          <td><a href="/pedidos/{{ $row->CODIGO }}" class="text-blue-600 hover:underline font-mono text-xs font-semibold">{{ $row->CODIGO }}</a></td>
          <td class="text-xs">{{ $row->FECHA ?? '—' }}</td>
          <td class="text-xs"><a href="/clientes/{{ $row->CLIENTE }}" class="text-blue-600 hover:underline">{{ $row->DESCRIPCION_CLIENTE ?? $row->CLIENTE ?? '—' }}</a></td>
          <td class="max-w-xs truncate text-sm">{{ $row->TITULO ?? '—' }}</td>
          <td><span class="badge badge-blue">{{ $row->DESCRIPCION_ESTADO ?? $row->ESTADO ?? '—' }}</span></td>
          <td><span class="badge {{ ($row->ESTADO_SERVIDO??'')==='T' ? 'badge-green' : (($row->ESTADO_SERVIDO??'')==='N' ? 'badge-red' : 'badge-yellow') }}">{{ match($row->ESTADO_SERVIDO??'') {'T'=>'Total','N'=>'Nada','P'=>'Parcial',default=>'—'} }}</span></td>
          <td class="td-right font-mono text-sm font-semibold">{{ number_format($row->BASE_IMP ?? 0, 2, ',', '.') }}€</td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
@endif
