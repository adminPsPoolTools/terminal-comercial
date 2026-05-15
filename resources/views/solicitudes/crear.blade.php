@extends('layouts.app')
@section('title', 'Nueva solicitud de presupuesto')
@section('page-title', 'Nueva solicitud de presupuesto')

@section('content')
<div class="space-y-4 max-w-3xl">

  @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-700 text-sm font-medium">{{ session('error') }}</div>
  @endif

  <div class="crm-card p-4 flex items-center justify-between">
    <p class="text-sm text-slate-500">Rellena los datos de la nueva solicitud de presupuesto.</p>
    @if($cliente)
      <a href="{{ route('clientes.detalle', $cliente) }}" class="btn btn-secondary btn-sm">← Volver al cliente</a>
    @endif
  </div>

  <form method="POST" action="{{ route('solicitudes.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="cliente" value="{{ $cliente }}">

    {{-- Fecha, hora, proyecto --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Datos generales</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div>
          <label class="form-label">Fecha <span class="text-red-500">*</span></label>
          <input type="date" name="fecha" class="form-input" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
          <label class="form-label">Hora <span class="text-red-500">*</span></label>
          <input type="time" name="hora" class="form-input" value="{{ date('H:i') }}" required>
        </div>
        <div>
          <label class="form-label">Proyecto <span class="text-red-500">*</span></label>
          <select name="proyecto" class="form-select" required>
            <option value="">— Seleccionar —</option>
            @foreach($proyectos as $p)
              <option value="{{ $p->CODIGO ?? '' }}">{{ $p->DESCRIPCION ?? $p->CODIGO ?? '' }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- Carácter y categoría --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Clasificación</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="form-label">Carácter <span class="text-red-500">*</span></label>
          <select name="caracter" class="form-select" required>
            @foreach($caracteres as $c)
              <option value="{{ $c->CODIGO ?? '' }}" {{ ($c->CODIGO ?? '') == 2 ? 'selected' : '' }}>
                {{ $c->DESCRIPCION ?? $c->CODIGO ?? '' }}
              </option>
            @endforeach
          </select>
          <p class="text-xs text-slate-400 mt-1">Solo se permiten: Urgente, Muy urgente, Pedido.</p>
        </div>
        <div>
          <label class="form-label">Categoría <span class="text-red-500">*</span></label>
          <select name="categoria" class="form-select" required>
            <option value="">— Seleccionar —</option>
            @foreach($categorias as $c)
              <option value="{{ $c->CODIGO ?? '' }}">{{ $c->DESCRIPCIONCATEGORIA ?? $c->DESCRIPCION ?? $c->CODIGO ?? '' }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- Correo y plantilla --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Contenido</h3>
      <div class="space-y-4">
        <div>
          <label class="form-label">Correo electrónico</label>
          <input type="email" name="correo" class="form-input" list="correos-list"
                 value="{{ old('correo') }}" placeholder="correo@ejemplo.com">
          @if(!empty($correos))
          <datalist id="correos-list">
            @foreach($correos as $c)
              @if(!empty($c->CORREO))
                <option value="{{ $c->CORREO }}">
              @endif
            @endforeach
          </datalist>
          @endif
        </div>
        <div>
          <label class="form-label">Texto / Plantilla</label>
          <textarea name="plantilla" rows="6" class="form-input"
                    placeholder="Describe la solicitud de presupuesto...">{{ old('plantilla') }}</textarea>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="btn btn-primary">Guardar solicitud</button>
      @if($cliente)
        <a href="{{ route('clientes.detalle', $cliente) }}" class="btn btn-secondary">Cancelar</a>
      @endif
    </div>
  </form>

</div>
@endsection
