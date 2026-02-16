<!DOCTYPE html>
<html>
<head>
    <title>Listado de Alumnos - {{ $curso->anyo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Listado de Alumnos - {{ $curso->anyo }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Empresa</th>
                <th>DNI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($curso->alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->nombre_completo }}</td>
                    <td>
                        {{ $alumno->empresa ? $alumno->empresa->nombre : 'Sin asignar' }}
                    </td>
                    <td>{{ $alumno->dni_encriptado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No hay alumnos en este curso.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
