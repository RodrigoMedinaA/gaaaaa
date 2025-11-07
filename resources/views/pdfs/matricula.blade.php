{{-- resources/views/pdfs/matricula.blade.php --}}
@php
    use Illuminate\Support\Carbon;

    // === Datos estáticos de la institución ===
    $institucion = [
        'nombre'        => 'CENTRO DE EDUCACIÓN TÉCNICO PRODUCTIVA — CETPRO',
        'rde'           => 'R.D. N.º 123-2024-ED',
        'ugel'          => 'UGEL LIMA',
        'drep'          => 'DRE LIMA METROPOLITANA',
        'direccion'     => 'Av. Siempre Viva 742 - Lima',
        'telefono'      => '(01) 555-1234',
        'email'         => 'info@cetpro.edu.pe',
        'distrito'      => 'LIMA',
        'provincia'     => 'LIMA',
        'region'        => 'LIMA',
    ];

    // === Aliases de datos ===
    $est = $matricula->estudiante;
    $sec = $matricula->seccion;
    $mod = $sec?->modulo;
    $doc = $sec?->docente;

    $fechaNac = $est?->fecha_nacimiento ? Carbon::parse($est->fecha_nacimiento)->format('d/m/Y') : '';
    $hoy      = Carbon::now()->format('d/m/Y');

    // Si manejas 'genero' y 'estado_civil' como enum/string
    $genero       = $est?->genero ? strtoupper($est->genero) : '';
    $estadoCivil  = $est?->estado_civil ? strtoupper($est->estado_civil) : '';

    // === Académicos base (ciclo/turno/modalidad/fechas) ===
    $ciclo           = $mod?->ciclo ?? $sec?->ciclo ?? null;
    $turnoLabel      = $sec?->turno?->getLabel() ?? ($sec?->turno ? strtoupper((string)$sec->turno) : null);
    $modalidadLabel  = $sec?->modalidad?->getLabel() ?? ($sec?->modalidad ? strtoupper((string)$sec->modalidad) : null);

    $inicio = $sec?->fecha_inicio ? Carbon::parse($sec->fecha_inicio)->format('d/m/Y') : '';
    $fin    = $sec?->fecha_fin ? Carbon::parse($sec->fecha_fin)->format('d/m/Y') : '';

    // === Cálculo Opción C (HORAS TOTALES) SIN tocar el modelo ===

    // 1) Semanas incluyentes (ceil)
    $semanas = null;
    if ($sec?->fecha_inicio && $sec?->fecha_fin) {
        $fi = Carbon::parse($sec->fecha_inicio)->startOfDay();
        $ff = Carbon::parse($sec->fecha_fin)->startOfDay();
        $diasIncl = $fi->diffInDays($ff) + 1;
        $semanas  = (int) ceil($diasIncl / 7);
    }

    // 2) Minutos por día a partir de hora_inicio/hora_fin (con cruce de medianoche)
    $minPorDia = null;
    if ($sec?->hora_inicio && $sec?->hora_fin) {
        $hi = Carbon::parse($sec->hora_inicio);
        $hf = Carbon::parse($sec->hora_fin);
        if ($hf->lessThanOrEqualTo($hi)) {
            $hf = $hf->copy()->addDay();
        }
        $minPorDia = $hi->diffInMinutes($hf);
    }

    // 3) Días por semana (cuenta elementos únicos del array dias_estudio)
    $diasPorSemana = 0;
    if (is_array($sec?->dias_estudio)) {
        $diasPorSemana = count(array_unique(array_map(
            fn($d) => strtolower(trim((string) $d)),
            $sec->dias_estudio
        )));
    }

    // 4) Minutos totales y etiqueta "H h M min"
    $durHoras = '';
    if ($semanas && $minPorDia && $diasPorSemana) {
        $minTot = $semanas * $diasPorSemana * $minPorDia;
        $h = intdiv($minTot, 60);
        $m = $minTot % 60;
        $durHoras = $h . ' h' . ($m ? " {$m} min" : '');
    }

    // 5) Horario legible (DÍAS ABB + "HH:MM - HH:MM")
    $diaMap = [
        '0'=>'DOM','1'=>'LUN','2'=>'MAR','3'=>'MIÉ','4'=>'JUE','5'=>'VIE','6'=>'SÁB',
        'sun'=>'DOM','mon'=>'LUN','tue'=>'MAR','wed'=>'MIÉ','thu'=>'JUE','fri'=>'VIE','sat'=>'SÁB',
        'sunday'=>'DOM','monday'=>'LUN','tuesday'=>'MAR','wednesday'=>'MIÉ','thursday'=>'JUE','friday'=>'VIE','saturday'=>'SÁB',
        'domingo'=>'DOM','lunes'=>'LUN','martes'=>'MAR','miércoles'=>'MIÉ','miercoles'=>'MIÉ','jueves'=>'JUE','viernes'=>'VIE','sábado'=>'SÁB','sabado'=>'SÁB',
    ];
    $diasLabel = '';
    if (is_array($sec?->dias_estudio)) {
        $diasNom = [];
        foreach ($sec->dias_estudio as $d) {
            $k = strtolower(trim((string) $d));
            $diasNom[] = $diaMap[$k] ?? strtoupper($k);
        }
        $diasLabel = implode(', ', array_unique($diasNom));
    }
    $horaIni = $sec?->hora_inicio ? Carbon::parse($sec->hora_inicio)->format('H:i') : '';
    $horaFin = $sec?->hora_fin ? Carbon::parse($sec->hora_fin)->format('H:i') : '';
    $horario = trim(($diasLabel ? $diasLabel.' ' : '') . trim($horaIni . ($horaFin ? ' - '.$horaFin : '')));
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Matrícula</title>
    <style>
        /* DomPDF-friendly CSS */
        @page { margin: 24px 28px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .small { font-size: 11px; }
        .mt-4 { margin-top: 16px; }
        .mb-3 { margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .no-border th, .no-border td { border: 0; padding: 0; }
        .label { font-weight: 700; }
        .center { text-align: center; }
        .w-15 { width: 15%; } .w-20 { width: 20%; } .w-25 { width: 25%; } .w-30 { width: 30%; }
        .w-35 { width: 35%; } .w-40 { width: 40%; } .w-45 { width: 45%; } .w-50 { width: 50%; }
        .sign { height: 70px; }
        .light { color:#333; }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <table class="no-border" style="margin-bottom: 6px;">
        <tr>
            <td style="width: 15%; text-align:center;">
                {{-- <img src="{{ public_path('img/escudo.png') }}" style="height:60px;" alt=""> --}}
            </td>
            <td style="width: 70%; text-align: center;">
                <div class="small">{{ $institucion['drep'] }}</div>
                <div class="small">{{ $institucion['ugel'] }}</div>
                <div style="font-size:16px; font-weight:700;">FICHA DE MATRÍCULA</div>
                <div class="small">{{ $institucion['nombre'] }}</div>
                <div class="small">{{ $institucion['rde'] }}</div>
            </td>
            <td style="width: 15%;"></td>
        </tr>
    </table>

    {{-- DATOS DE LA INSTITUCIÓN --}}
    <table class="mb-3">
        <tr>
            <td class="w-25"><span class="label">REGIÓN</span><br>{{ $institucion['region'] }}</td>
            <td class="w-25"><span class="label">PROVINCIA</span><br>{{ $institucion['provincia'] }}</td>
            <td class="w-25"><span class="label">DISTRITO</span><br>{{ $institucion['distrito'] }}</td>
            <td class="w-25"><span class="label">DIRECCIÓN</span><br>{{ $institucion['direccion'] }}</td>
        </tr>
        <tr>
            <td class="w-25"><span class="label">TELÉFONO</span><br>{{ $institucion['telefono'] }}</td>
            <td class="w-25"><span class="label">CORREO</span><br>{{ $institucion['email'] }}</td>
            <td class="w-25"><span class="label">CÓDIGO MATRÍCULA</span><br>{{ $matricula->codigo }}</td>
            <td class="w-25"><span class="label">ESTADO</span><br>{{ $matricula->estado?->getLabel() }}</td>
        </tr>
    </table>

    {{-- DATOS DEL ESTUDIANTE --}}
    <table class="mb-3">
        <tr><th colspan="4" class="center">DATOS DEL ESTUDIANTE</th></tr>
        <tr>
            <td class="w-25"><span class="label">APELLIDO PATERNO</span><br>{{ strtoupper($est?->apellido_paterno ?? '') }}</td>
            <td class="w-25"><span class="label">APELLIDO MATERNO</span><br>{{ strtoupper($est?->apellido_materno ?? '') }}</td>
            <td class="w-35"><span class="label">NOMBRES</span><br>{{ strtoupper($est?->nombres ?? '') }}</td>
            <td class="w-15"><span class="label">SEXO</span><br>{{ $genero }}</td>
        </tr>
        <tr>
            <td class="w-15"><span class="label">EDAD</span><br>@if($est?->fecha_nacimiento) {{ Carbon::parse($est->fecha_nacimiento)->age }} @endif</td>
            <td class="w-25"><span class="label">ESTADO CIVIL</span><br>{{ $estadoCivil }}</td>
            <td class="w-30"><span class="label">GRADO DE INSTRUCCIÓN</span><br></td>
            <td class="w-30"><span class="label">OCUPACIÓN</span><br></td>
        </tr>
        <tr>
            <td class="w-25"><span class="label">TIPO DOC.</span><br>{{ strtoupper($est?->tipo_documento ?? '') }}</td>
            <td class="w-25"><span class="label">NRO DOC.</span><br>{{ $est?->nro_documento }}</td>
            <td class="w-25"><span class="label">F. NACIMIENTO</span><br>{{ $fechaNac }}</td>
            <td class="w-25"><span class="label">PAÍS DE NAC.</span><br>PERÚ</td>
        </tr>
        <tr>
            <td class="w-40" colspan="2"><span class="label">DOMICILIO</span><br>{{ $est?->direccion }}</td>
            <td class="w-30"><span class="label">PROVINCIA</span><br></td>
            <td class="w-30"><span class="label">DISTRITO</span><br></td>
        </tr>
        <tr>
            <td class="w-25"><span class="label">TELÉFONO</span><br>{{ $est?->telefono }}</td>
            <td class="w-35"><span class="label">CORREO ELECTRÓNICO</span><br>{{ $est?->email }}</td>
            <td class="w-20"><span class="label">APODERADO</span><br>{{ $est?->apoderado?->nombres }}</td>
            <td class="w-20"><span class="label">TEL. APODERADO</span><br>{{ $est?->apoderado?->telefono }}</td>
        </tr>
    </table>

    {{-- DATOS ACADÉMICOS --}}
    <table class="mb-3">
        <tr><th colspan="4" class="center">DATOS ACADÉMICOS</th></tr>
        <tr>
            <td class="w-15">
                <span class="label">CICLO</span><br>{{ strtoupper($ciclo ?? '') }}
            </td>
            <td class="w-45">
                <span class="label">ESPECIALIDAD / OCUPACIONAL</span><br>
                {{ $sec?->nombre }} @if($sec?->codigo) ({{ $sec->codigo }}) @endif
                @if($doc?->nombres)
                    <div class="small">Docente: {{ $doc->nombres }}</div>
                @endif
            </td>
            <td class="w-20">
                <span class="label">MÓDULO</span><br>{{ $mod?->nombre }}
            </td>
            <td class="w-20">
                <span class="label">HORARIO</span><br>
                {{ $horario }}
                @if($turnoLabel)<br><span class="small">Turno: {{ strtoupper($turnoLabel) }}</span>@endif
                @if($modalidadLabel)<br><span class="small">Modalidad: {{ strtoupper($modalidadLabel) }}</span>@endif
            </td>
        </tr>
        <tr>
            <td class="w-20"><span class="label">DURACIÓN</span><br>{{ $durHoras }}</td>
            <td class="w-40"><span class="label">INICIO</span><br>{{ $inicio }}</td>
            <td class="w-40" colspan="2"><span class="label">TÉRMINO</span><br>{{ $fin }}</td>
        </tr>
    </table>

    {{-- FECHA Y FIRMAS --}}
    <table class="no-border mt-4">
        <tr>
            <td class="no-border" style="width: 50%;"><span class="label">FECHA:</span> {{ $hoy }}</td>
            <td class="no-border" style="width: 50%;"></td>
        </tr>
    </table>

    <table class="mt-4">
        <tr>
            <td class="center sign"><div class="light">ESTUDIANTE</div></td>
            <td class="center sign">
                <div class="light">COORDINADOR(A)</div>
                <div class="small light">(Firma, sello, post firma)</div>
            </td>
        </tr>
        <tr>
            <td class="center sign" colspan="2">
                <div class="light">DIRECTOR(A)</div>
                <div class="small light">(Firma, sello, post firma)</div>
            </td>
        </tr>
    </table>

</body>
</html>
