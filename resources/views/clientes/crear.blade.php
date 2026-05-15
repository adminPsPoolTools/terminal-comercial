@extends('layouts.app')
@section('title', 'Nuevo Cliente')
@section('page-title', 'Nuevo Cliente')

@section('content')
<div class="max-w-4xl space-y-5">

  {{-- Header --}}
  <div class="crm-card p-4 flex items-center justify-between">
    <h1 class="font-head font-bold text-lg text-slate-900">Nuevo Cliente</h1>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
  </div>

  @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-700 text-sm">{{ session('error') }}</div>
  @endif

  <form method="POST" action="{{ route('clientes.store') }}" class="space-y-5">
    @csrf

    {{-- Datos principales --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Datos principales</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="sm:col-span-2">
          <label class="form-label">Nombre / Razón social <span class="text-red-500">*</span></label>
          <input type="text" name="descripcion" value="{{ old('descripcion') }}" class="form-input @error('descripcion') border-red-400 @enderror" required>
          @error('descripcion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="form-label">CIF / NIF</label>
          <input type="text" name="cif" value="{{ old('cif') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Alias</label>
          <input type="text" name="alias" value="{{ old('alias') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Categoría</label>
          <select name="categoria" class="form-select">
            <option value="">— Seleccionar —</option>
            @foreach($categorias as $c)
              <option value="{{ $c->CODIGO }}" {{ old('categoria') == $c->CODIGO ? 'selected' : '' }}>{{ $c->DESCRIPCIONCATEGORIA }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Tipo de cliente</label>
          <select name="tipocliente" class="form-select">
            <option value="">— Seleccionar —</option>
            @foreach($tipos as $t)
              <option value="{{ $t->CODIGO }}" {{ old('tipocliente') == $t->CODIGO ? 'selected' : '' }}>{{ $t->DESCRIPCION }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Contacto</label>
          <input type="text" name="contacto" value="{{ old('contacto') }}" class="form-input" placeholder="Nombre del contacto">
        </div>

        <div>
          <label class="form-label">Tipo fiscal</label>
          <div class="flex gap-4 mt-1.5">
            @foreach(['N' => 'Nacional', 'I' => 'Intracomunitario', 'E' => 'Extracomunitario'] as $val => $lbl)
            <label class="flex items-center gap-1.5 text-sm text-slate-700 cursor-pointer">
              <input type="radio" name="es_extracomunitario" value="{{ $val }}" {{ old('es_extracomunitario', 'N') === $val ? 'checked' : '' }} class="accent-blue-600">
              {{ $lbl }}
            </label>
            @endforeach
          </div>
        </div>

      </div>
    </div>

    {{-- Dirección --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Dirección</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="sm:col-span-2">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" value="{{ old('direccion') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">CP</label>
          <input type="text" name="cp" value="{{ old('cp') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Población</label>
          <input type="text" name="poblacion" value="{{ old('poblacion') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Provincia</label>
          <select name="provincia" class="form-select">
            <option value="">— Seleccionar —</option>
            @foreach($provincias as $p)
              <option value="{{ $p->DESCRIPCION ?? $p->PROVINCIA ?? $p->CODIGO }}" {{ old('provincia') == ($p->DESCRIPCION ?? $p->PROVINCIA ?? $p->CODIGO) ? 'selected' : '' }}>
                {{ $p->DESCRIPCION ?? $p->PROVINCIA ?? $p->CODIGO }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Comarca</label>
          <input type="text" name="comarca" value="{{ old('comarca') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">País</label>
          <input type="text" name="pais" value="{{ old('pais', 'ESPAÑA') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Ap. Correos</label>
          <input type="text" name="apcorreos" value="{{ old('apcorreos') }}" class="form-input">
        </div>

      </div>
    </div>

    {{-- Contacto --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Contacto</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div>
          <label class="form-label">Teléfono fijo</label>
          <input type="tel" name="telefonofijo" value="{{ old('telefonofijo') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Móvil</label>
          <input type="tel" name="telefonomovil" value="{{ old('telefonomovil') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Fax</label>
          <input type="tel" name="fax" value="{{ old('fax') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Email</label>
          <input type="email" name="correo" value="{{ old('correo') }}" class="form-input">
        </div>

        <div class="sm:col-span-2">
          <label class="form-label">Web</label>
          <input type="text" name="web" value="{{ old('web') }}" class="form-input" placeholder="https://...">
        </div>

      </div>
    </div>

    {{-- Dirección de envío --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Dirección de envío (opcional)</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="sm:col-span-2">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccionenviomercancia" value="{{ old('direccionenviomercancia') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">CP</label>
          <input type="text" name="cpenviomercancia" value="{{ old('cpenviomercancia') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Población</label>
          <input type="text" name="poblacionenviomercancia" value="{{ old('poblacionenviomercancia') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Provincia</label>
          <select name="provinciaenviomercancia" class="form-select">
            <option value="">— Seleccionar —</option>
            @foreach($provincias as $p)
              <option value="{{ $p->DESCRIPCION ?? $p->PROVINCIA ?? $p->CODIGO }}" {{ old('provinciaenviomercancia') == ($p->DESCRIPCION ?? $p->PROVINCIA ?? $p->CODIGO) ? 'selected' : '' }}>
                {{ $p->DESCRIPCION ?? $p->PROVINCIA ?? $p->CODIGO }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Teléfono</label>
          <input type="tel" name="telefonofijoenviomercancia" value="{{ old('telefonofijoenviomercancia') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Móvil</label>
          <input type="tel" name="telefonomovilenviomercancia" value="{{ old('telefonomovilenviomercancia') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Fax</label>
          <input type="tel" name="faxenviomercancia" value="{{ old('faxenviomercancia') }}" class="form-input">
        </div>

        <div>
          <label class="form-label">Email</label>
          <input type="email" name="correoenviomercancia" value="{{ old('correoenviomercancia') }}" class="form-input">
        </div>

      </div>
    </div>

    {{-- Comentarios --}}
    <div class="crm-card p-5">
      <h3 class="font-head font-semibold text-xs text-slate-400 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Comentarios</h3>
      <div class="space-y-4">

        <div>
          <label class="form-label">Comentario comercial</label>
          <textarea name="comentario_comercial_cliente" rows="3" class="form-input">{{ old('comentario_comercial_cliente') }}</textarea>
        </div>

        <div>
          <label class="form-label">Alerta comercial</label>
          <textarea name="comentario_alerta_comercial" rows="2" class="form-input" placeholder="Este texto aparecerá como alerta en la ficha del cliente">{{ old('comentario_alerta_comercial') }}</textarea>
        </div>

        <div>
          <label class="form-label">Observaciones</label>
          <textarea name="libre10_cliente" rows="2" class="form-input">{{ old('libre10_cliente') }}</textarea>
        </div>

      </div>
    </div>

    {{-- Acciones --}}
    <div class="flex items-center justify-end gap-3 pb-4">
      <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
      <button type="submit" class="btn btn-primary">Guardar cliente</button>
    </div>

  </form>
</div>
@endsection
