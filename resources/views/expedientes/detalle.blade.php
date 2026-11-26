@extends('layouts.app')
@section('title', 'Expediente ' . $codigo)
@section('page-title', 'Expediente ' . $codigo)

@php
  $fecha = fn ($v) => ($v && ($t = strtotime((string) $v))) ? date('d/m/Y', $t) : '—';
  $hora  = fn ($v) => $v ? substr((string) $v, 0, 5) : '';
  $txt   = fn ($v) => trim((string) ($v ?? ''));
  $e     = $expediente;
@endphp

@section('content')
<div class="space-y-4 max-w-6xl">

  {{-- Cabecera --}}
  <div class="crm-card p-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
      <p class="text-xs text-slate-400 mb-0.5">Expediente · <span class="font-mono font-semibold text-slate-600">{{ $e->CODIGO ?? $codigo }}</span></p>
      <h1 class="font-head font-bold text-lg text-slate-900 leading-tight">{{ $txt($e->DESCRIPCION ?? '') ?: '—' }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        Alta: <strong>{{ $fecha($e->FECHA_ALTA ?? null) }}</strong>
        @if($comercialNombre) · Comercial: <strong>{{ $comercialNombre }}</strong> @endif
        @if($estadoDesc) · Estado: <span class="badge badge-blue">{{ $estadoDesc }}</span> @endif
      </p>
    </div>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('expedientes.index') }}" class="btn btn-secondary btn-sm shrink-0">← Volver</a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Datos --}}
    <div class="crm-card p-4 info-card">
      <h3>Datos</h3>
      <dl class="dl-compact">
        @foreach([
          ['Dirección', $txt($e->DIRECCION ?? '')],
          ['Apdo. correos', $txt($e->APCORREOS ?? '')],
          ['CP / Población', trim($txt($e->CP ?? '') . ' ' . $txt($e->POBLACION ?? ''))],
          ['Comarca', $txt($e->COMARCA ?? '')],
          ['Provincia', $txt($e->PROVINCIA ?? '')],
          ['País', $txt($e->PAIS ?? '')],
          ['Email', $txt($e->CORREO ?? '')],
          ['Web', $txt($e->WEB ?? '')],
        ] as [$l,$v]) @if($v !== '')
        <div class="dl-row"><dt>{{ $l }}</dt><dd>{{ $v }}</dd></div>
        @endif @endforeach
      </dl>
    </div>

    {{-- Comentarios --}}
    <div class="crm-card p-4 info-card">
      <h3>Comentarios</h3>
      @php
        $comentarios = array_filter([
          'General'   => $txt($e->COMENTARIO ?? ''),
          'Comercial' => $txt($e->COMENTARIO_COMERCIAL_EXPEDIENTE ?? ''),
          'Interno'   => $txt($e->COMENTARIO_INTERNO_EXPEDIENTE ?? ''),
        ]);
      @endphp
      @forelse($comentarios as $tipo => $c)
        <p class="text-xs font-semibold text-slate-500 mt-2">{{ $tipo }}</p>
        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $c }}</p>
      @empty
        <p class="text-sm text-slate-500">Sin comentarios.</p>
      @endforelse
    </div>
  </div>

  {{-- Clientes y contactos --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="crm-card overflow-hidden">
      <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Clientes ({{ count($clientes) }})</h3>
      @if(empty($clientes))
        <div class="empty-state">Sin clientes asignados.</div>
      @else
      <div class="table-wrapper"><table class="crm-table">
        <thead><tr><th>Código</th><th>Cliente</th><th>Comentario</th></tr></thead>
        <tbody>
          @foreach($clientes as $c)
          <tr>
            <td class="font-mono text-xs">
              @if($txt($c->CODIGO ?? '') !== '')
                <a href="{{ route('clientes.detalle', $txt($c->CODIGO)) }}" class="text-blue-600 hover:underline">{{ $txt($c->CODIGO) }}</a>
              @else — @endif
            </td>
            <td class="text-sm">{{ $txt($c->NOMBRE ?? '') ?: '—' }}</td>
            <td class="text-xs text-slate-500">{{ $txt($c->COMENTARIO ?? '') ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table></div>
      @endif
    </div>

    <div class="crm-card overflow-hidden">
      <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Contactos ({{ count($contactos) }})</h3>
      @if(empty($contactos))
        <div class="empty-state">Sin contactos.</div>
      @else
      <div class="table-wrapper"><table class="crm-table">
        <thead><tr><th>Nombre</th><th>Cargo</th><th>Teléfono</th><th>Email</th></tr></thead>
        <tbody>
          @foreach($contactos as $c)
          <tr>
            <td class="text-sm">{{ $txt($c->NOMBRE ?? '') ?: '—' }}</td>
            <td class="text-xs text-slate-500">{{ $txt($c->CARGO ?? '') ?: '—' }}</td>
            <td class="text-xs">{{ trim($txt($c->TELEFONOFIJO ?? '') . ' ' . $txt($c->TELEFONOMOVIL ?? '')) ?: '—' }}</td>
            <td class="text-xs">{{ $txt($c->CORREO ?? '') ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table></div>
      @endif
    </div>
  </div>

  {{-- Agendas --}}
  <div class="crm-card overflow-hidden">
    <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Agendas ({{ count($agendas) }})</h3>
    @if(empty($agendas))
      <div class="empty-state">Sin agendas.</div>
    @else
    <div class="table-wrapper"><table class="crm-table">
      <thead><tr><th>Cód.</th><th>Fecha</th><th>Título</th><th>Asignada a</th><th>Estado</th></tr></thead>
      <tbody>
        @foreach($agendas as $a)
        <tr>
          <td class="font-mono text-xs"><a href="{{ route('agenda.detalle', $a->CODIGO) }}" class="text-blue-600 hover:underline">{{ $a->CODIGO }}</a></td>
          <td class="text-xs whitespace-nowrap">{{ $fecha($a->FECHA ?? null) }} {{ $hora($a->HORA ?? null) }}</td>
          <td class="text-sm"><a href="{{ route('agenda.detalle', $a->CODIGO) }}" class="hover:underline">{{ $txt($a->TITULO ?? '') ?: '—' }}</a></td>
          <td class="text-xs text-slate-500">{{ $txt($a->USUARIO ?? '') ?: '—' }}</td>
          <td><span class="badge badge-gray">{{ $txt($a->ESTADO ?? '') ?: '—' }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table></div>
    @endif
  </div>

  {{-- Presupuestos, pedidos y visitas --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="crm-card overflow-hidden">
      <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Presupuestos ({{ count($presupuestos) }})</h3>
      @if(empty($presupuestos))
        <div class="empty-state">Sin presupuestos.</div>
      @else
      <div class="table-wrapper"><table class="crm-table">
        <thead><tr><th>Cód.</th><th>Fecha</th><th>Título</th></tr></thead>
        <tbody>
          @foreach($presupuestos as $p)
          <tr>
            <td class="font-mono text-xs"><a href="{{ route('presupuestos.detalle', $p->CODIGO) }}" class="text-blue-600 hover:underline">{{ $p->CODIGO }}</a></td>
            <td class="text-xs">{{ $fecha($p->FECHA ?? null) }}</td>
            <td class="text-sm">{{ $txt($p->TITULO ?? '') ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table></div>
      @endif
    </div>

    <div class="crm-card overflow-hidden">
      <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Pedidos ({{ count($pedidos) }})</h3>
      @if(empty($pedidos))
        <div class="empty-state">Sin pedidos.</div>
      @else
      <div class="table-wrapper"><table class="crm-table">
        <thead><tr><th>Cód.</th><th>Fecha</th><th>Título</th><th class="td-right">Total</th></tr></thead>
        <tbody>
          @foreach($pedidos as $p)
          <tr>
            <td class="font-mono text-xs"><a href="{{ route('pedidos.detalle', $p->CODIGO) }}" class="text-blue-600 hover:underline">{{ $p->CODIGO }}</a></td>
            <td class="text-xs">{{ $fecha($p->FECHA ?? null) }}</td>
            <td class="text-sm">{{ $txt($p->TITULO ?? '') ?: '—' }}</td>
            <td class="td-right font-mono text-xs">{{ number_format((float) ($p->TOTAL ?? 0), 2, ',', '.') }} €</td>
          </tr>
          @endforeach
        </tbody>
      </table></div>
      @endif
    </div>

    <div class="crm-card overflow-hidden">
      <h3 class="font-head font-semibold text-slate-800 p-4 pb-0">Visitas ({{ count($visitas) }})</h3>
      @if(empty($visitas))
        <div class="empty-state">Sin visitas.</div>
      @else
      <div class="table-wrapper"><table class="crm-table">
        <thead><tr><th>Cód.</th><th>Fecha</th><th>Comentario</th></tr></thead>
        <tbody>
          @foreach($visitas as $v)
          <tr>
            <td class="font-mono text-xs"><a href="{{ route('visitas.detalle', $v->CODIGO) }}" class="text-blue-600 hover:underline">{{ $v->CODIGO }}</a></td>
            <td class="text-xs whitespace-nowrap">{{ $fecha($v->FECHA ?? null) }} {{ $hora($v->HORA ?? null) }}</td>
            <td class="text-xs">{{ \Illuminate\Support\Str::limit($txt($v->COMENTARIO ?? ''), 80) ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table></div>
      @endif
    </div>
  </div>

</div>
@endsection
