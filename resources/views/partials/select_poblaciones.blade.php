{{-- La API filtra por CÓDIGO de población (buscarExpedientes, obtenerPresupuestosFichaCliente, obtenerListadoClientes) --}}
<option value="">Todas</option>
@foreach($poblaciones as $p)
  <option value="{{ $p->CODIGO ?? '' }}">{{ $p->DESCRIPCION ?? '' }}</option>
@endforeach
