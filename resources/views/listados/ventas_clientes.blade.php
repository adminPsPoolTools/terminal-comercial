@if(empty($datos))
  <div class="empty-state">Sin datos para el período seleccionado.</div>
@else
<div class="table-wrapper rounded-xl overflow-hidden p-1">
  <table class="crm-table">
    <thead>
      <tr>
        <th>Cliente</th>
        <th>Provincia</th>
        @if(isset($datos[0]->MES_1)) @for($m=1;$m<=12;$m++) <th class="td-right">{{ ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'][$m-1] }}</th> @endfor @endif
        <th class="td-right">Total</th>
      </tr>
    </thead>
    <tbody>
      @php $totalGen = 0; @endphp
      @foreach($datos as $row)
        @if(!is_null($row->CLIENTE ?? null))
        @php $totalGen += $row->TOTAL ?? 0; @endphp
        <tr>
          <td class="font-medium text-sm">
            <a href="{{ route('clientes.detalle', $row->CLIENTE) }}" class="text-blue-600 hover:underline">{{ $row->DESCRIPCION ?? $row->CLIENTE }}</a>
          </td>
          <td class="text-xs text-slate-500">{{ $row->PROVINCIA ?? '—' }}</td>
          @if(isset($row->MES_1))
            @for($m=1;$m<=12;$m++)
              <td class="td-right font-mono text-xs">{{ ($row->{'MES_'.$m} ?? 0) ? number_format($row->{'MES_'.$m}, 0, ',', '.') : '—' }}</td>
            @endfor
          @endif
          <td class="td-right font-mono text-sm font-bold">{{ number_format($row->TOTAL ?? 0, 2, ',', '.') }}€</td>
        </tr>
        @endif
      @endforeach
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="{{ isset($datos[0]->MES_1) ? 14 : 2 }}" class="td-right text-xs font-semibold text-slate-600 pr-4">Total</td>
        <td class="td-right font-mono font-bold">{{ number_format($totalGen, 2, ',', '.') }}€</td>
      </tr>
    </tfoot>
  </table>
</div>
@endif
