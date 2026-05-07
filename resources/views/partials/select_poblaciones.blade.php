<option value="">Todas</option>
@foreach($poblaciones as $p)
  <option value="{{ $p->POBLACION ?? $p->DESCRIPCION ?? '' }}">{{ $p->DESCRIPCION ?? $p->POBLACION ?? '' }}</option>
@endforeach
