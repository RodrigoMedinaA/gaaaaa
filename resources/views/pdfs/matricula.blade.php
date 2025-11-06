{{-- resources/views/pdfs/matricula.blade.php --}}
@php
    use Illuminate\Support\Carbon;

    // === Datos estáticos de la institución ===
    $institucion = [
        'nombre'        => 'CENTRO DE EDUCACIÓN TÉCNICO PRODUCTIVA — CETPRO',
        'rde'           => 'R.D. N.º 123-2024-ED',     // ejemplo
        'ugel'          => 'UGEL LIMA',                // ejemplo
        'drep'          => 'DRE LIMA METROPOLITANA',   // ejemplo
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

    $fechaNac = $est?->fecha_nacimiento ? Carbon::parse($est->fecha_nacimiento)->format('d/m/Y') : '';
    $hoy      = Carbon::now()->format('d/m/Y');

    // Si manejas 'genero' y 'estado_civil' como enum/string
    $genero       = $est?->genero ? strtoupper($est->genero) : '';
    $estadoCivil  = $est?->estado_civil ? strtoupper($est->estado_civil) : '';
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
        .title { font-size: 18px; font-weight: 700; text-align: center; margin: 6px 0 12px; }
        .small { font-size: 11px; }
        .mt-4 { margin-top: 16px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .no-border th, .no-border td { border: 0; padding: 0; }
        .head th, .head td { border: 0; }
        .label { font-weight: 700; }
        .center { text-align: center; }
        .right { text-align: right; }
        .w-20 { width: 20%; } .w-25 { width: 25%; } .w-30 { width: 30%; }
        .w-35 { width: 35%; } .w-40 { width: 40%; } .w-50 { width: 50%; }
        .w-60 { width: 60%; } .w-80 { width: 80%; }
        .sign { height: 70px; }
        .light { color:#333; }
    </style>
</head>
<body>

    {{-- ENCABEZADO DE INSTITUCIÓN --}}
    <table class="no-border" style="margin-bottom: 6px;">
        <tr>
            <td style="width: 15%; text-align:center;">
                {{-- Si luego agregas escudo/logo como imagen base64 o pública, colócalo aquí --}}
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
            <td class="w-25"><span class="label">ESTADO</span><br>{{ ucfirst($matricula->estado) }}</td>
        </tr>
    </table>

    {{-- DATOS DEL ESTUDIANTE --}}
    <table class="mb-3">
        <tr>
            <th colspan="4" class="center">DATOS DEL ESTUDIANTE</th>
        </tr>
        <tr>
            <td class="w-25"><span class="label">APELLIDO PATERNO</span><br>{{ strtoupper($est?->apellido_paterno ?? '') }}</td>
            <td class="w-25"><span class="label">APELLIDO MATERNO</span><br>{{ strtoupper($est?->apellido_materno ?? '') }}</td>
            <td class="w-35"><span class="label">NOMBRES</span><br>{{ strtoupper($est?->nombres ?? '') }}</td>
            <td class="w-15"><span class="label">SEXO</span><br>{{ $genero }}</td>
        </tr>
        <tr>
            <td class="w-15"><span class="label">EDAD</span><br>
                @if($est?->fecha_nacimiento)
                    {{ Carbon::parse($est->fecha_nacimiento)->age }}
                @endif
            </td>
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
        <tr>
            <th colspan="4" class="center">DATOS ACADÉMICOS</th>
        </tr>
        <tr>
            <td class="w-15"><span class="label">CICLO</span><br></td>
            <td class="w-45"><span class="label">ESPECIALIDAD / OCUPACIONAL</span><br>{{ $sec?->nombre }} @if($sec?->codigo) ({{ $sec->codigo }}) @endif</td>
            <td class="w-20"><span class="label">MÓDULO</span><br></td>
            <td class="w-20"><span class="label">HORARIO</span><br></td>
        </tr>
        <tr>
            <td class="w-20"><span class="label">DURACIÓN</span><br></td>
            <td class="w-40"><span class="label">INICIO</span><br></td>
            <td class="w-40" colspan="2"><span class="label">TÉRMINO</span><br></td>
        </tr>
    </table>

    {{-- FECHA Y FIRMAS --}}
    <table class="no-border mt-4">
        <tr class="no-border">
            <td class="no-border" style="width: 50%;">
                <span class="label">FECHA:</span> {{ $hoy }}
            </td>
            <td class="no-border" style="width: 50%;"></td>
        </tr>
    </table>

    <table class="mt-4">
        <tr>
            <td class="center sign">
                <div class="light">ESTUDIANTE</div>
            </td>
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
