<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Alumnos - {{ $curso->nombre }}</title>
    <style>
        @page {
            margin: 100px 50px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
        }
        .header {
            position: fixed;
            top: -60px;
            left: 0;
            right: 0;
            height: 100px;
            width: 100%;
            display: table; /* Simulate flexbox */
        }
        .header-content {
            display: table-row;
        }
        .logo-text {
            display: table-cell;
            vertical-align: middle;
            text-align: left;
            width: 50%;
            font-size: 24px;
            font-weight: bold;
            color: #003366; /* Dark Blue */
        }
        .logo-img {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 50%;
        }
        .logo-img img {
            height: 40px;
        }
        
        .title-section {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .main-title {
            font-size: 20px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 10px;
        }
        .sub-title {
            font-size: 14px;
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
        }
        .academic-year {
            font-size: 16px;
            font-weight: bold;
            color: #003366;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        th, td {
            border: 1px solid #003366;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e6f3ff; /* Light Blue */
            color: #003366;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        td {
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .course-header {
            background-color: #e6f3ff;
            color: #003366;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border: 1px solid #003366;
            border-bottom: none;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo-text">
                Davante
            </div>
            <div class="logo-img">
                <img src="{{ public_path('images/davante.png') }}" alt="Davante Logo">
            </div>
        </div>
    </div>

    <div class="title-section">
        <div class="main-title">Listado oficial de alumnos</div>
        <div class="sub-title">SEDE: MEDAC ARENA</div>
        <div class="sub-title">MODALIDAD: PRESENCIAL</div>
        <div class="academic-year">Curso {{ $cursoAcademico->anyo }}</div>
    </div>

    <!-- Course Header Row Simulated as a separate div/table -->
    <div style="border: 1px solid #003366; border-bottom: none; background-color: #e6f3ff; color: #003366; font-weight: bold; text-align: center; padding: 10px; text-transform: uppercase;">
        {{ $curso->nombre }} - {{ $curso->modulo->nombre }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ALUMNO</th>
                <th>EMPRESA ASIGNADA</th>
                <th>DNI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumnos as $alumno)
                <tr>
                    <td style="text-align: center;">{{ $alumno->nombre_completo }}</td>
                    <td>{{ $alumno->empresa ? $alumno->empresa->nombre : '-' }}</td>
                    <td>{{ $alumno->dni_encriptado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">No hay alumnos matriculados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
