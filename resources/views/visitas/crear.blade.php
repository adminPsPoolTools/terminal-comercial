@extends('layouts.app')
@section('title', 'Nueva visita')
@section('page-title', 'Nueva visita comercial')

@section('content')
<div class="space-y-4 max-w-3xl">

  @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-700 text-sm font-medium">{{ session('error') }}</div>
  @endif

  <div class="crm-card p-4 flex items-center justify-between">
    <p class="text-sm text-slate-500">Rellena los datos de la nueva visita comercial.</p>
    @if($cliente)
      <a href="{{ route('clientes.detalle', $cliente) }}" class="btn btn-secondary btn-sm">← Volver al cliente</a>
    @endif
  </div>

  <form method="POST" action="{{ route('visitas.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="cliente" value="{{ $cliente }}">

    {{-- Fecha, hora, tipo, proyecto --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Datos generales</h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div>
          <label class="form-label">Fecha <span class="text-red-500">*</span></label>
          <input type="date" name="fecha" class="form-input" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
          <label class="form-label">Hora <span class="text-red-500">*</span></label>
          <input type="time" name="hora" class="form-input" value="{{ date('H:i') }}" required>
        </div>
        <div>
          <label class="form-label">Tipo</label>
          <select name="tipo" class="form-select">
            <option value="0">Telefónica</option>
            <option value="1">Directa</option>
            <option value="2">Correo</option>
          </select>
        </div>
        <div>
          <label class="form-label">Proyecto</label>
          <select name="proyecto" class="form-select">
            <option value="1">Ps-Pool</option>
            <option value="51">PS-Water</option>
          </select>
        </div>
      </div>
    </div>

    {{-- Motivo, contacto, delegación --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Motivo y contacto</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="form-label">Motivo</label>
          <select name="motivo" class="form-select">
            <option value="">— Sin motivo —</option>
            @foreach($motivos as $m)
              <option value="{{ $m->CODIGO ?? '' }}">{{ $m->DESCRIPCION_MOTIVO ?? $m->CODIGO ?? '' }}</option>
            @endforeach
          </select>
        </div>
        @if(!empty($contactos))
        <div>
          <label class="form-label">Contacto</label>
          <select name="contacto" class="form-select">
            <option value="">— Sin contacto —</option>
            @foreach($contactos as $c)
              <option value="{{ $c->CODIGO ?? $c->CONTACTO ?? '' }}">{{ $c->NOMBRE ?? $c->CONTACTO ?? '—' }}</option>
            @endforeach
          </select>
        </div>
        @endif
        @if(!empty($direcciones))
        <div>
          <label class="form-label">Delegación</label>
          <select name="lineaDireccion" class="form-select">
            <option value="">— Principal —</option>
            @foreach($direcciones as $d)
              <option value="{{ $d->LINEA ?? $d->CODIGO ?? '' }}">{{ $d->DESCRIPCION ?? $d->DIRECCION ?? '—' }}</option>
            @endforeach
          </select>
        </div>
        @endif
      </div>
      <div class="mt-4">
        <label class="form-label">Comentario del motivo</label>
        <textarea name="comentario_motivo" rows="3" class="form-input" placeholder="Describe el motivo de la visita...">{{ old('comentario_motivo') }}</textarea>
      </div>
    </div>

    {{-- Acciones --}}
    @if(!empty($acciones))
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Acciones</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @foreach($acciones as $a)
          <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
            <input type="checkbox" name="acciones[]" value="{{ $a->CODIGO ?? '' }}" class="rounded border-slate-300">
            {{ $a->DESCRIPCION_ACCION ?? $a->CODIGO ?? '' }}
          </label>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Asuntos tratados --}}
    @if(!empty($asuntos))
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Asuntos tratados</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @foreach($asuntos as $a)
          <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
            <input type="checkbox" name="asuntos[]" value="{{ $a->CODIGO ?? '' }}" class="rounded border-slate-300">
            {{ $a->DESCRIPCION_ASUNTO ?? $a->CODIGO ?? '' }}
          </label>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Comentario general --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Comentario general</h3>
      <textarea name="comentario" rows="4" class="form-input" placeholder="Comentario sobre la visita...">{{ old('comentario') }}</textarea>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="btn btn-primary">Guardar visita</button>
      @if($cliente)
        <a href="{{ route('clientes.detalle', $cliente) }}" class="btn btn-secondary">Cancelar</a>
      @endif
    </div>
  </form>

</div>
@endsection
