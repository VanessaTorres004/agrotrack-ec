<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Agrícola - AgroTrack EC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2E7D32;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #2E7D32;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 11px;
        }
        
        .periodo {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #2E7D32;
        }
        
        .estadisticas {
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-row {
            display: table-row;
        }
        
        .stat-item {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #fafafa;
        }
        
        .stat-item h3 {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .stat-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #2E7D32;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #2E7D32;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #2E7D32;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background-color: #2E7D32;
            color: white;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #c8e6c9;
            color: #2e7d32;
        }
        
        .badge-warning {
            background-color: #fff9c4;
            color: #f57f17;
        }
        
        .badge-danger {
            background-color: #ffcdd2;
            color: #c62828;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .resumen-box {
            background-color: #f0f7f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .two-column {
            display: table;
            width: 100%;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>AgroTrack EC</h1>
        <p>Reporte Agrícola Integral</p>
    </div>

    <!-- Período -->
    <div class="periodo">
        <strong>Período del reporte:</strong> {{ date('d/m/Y', strtotime($fecha_inicio)) }} - {{ date('d/m/Y', strtotime($fecha_fin)) }}
        <br>
        <strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    <!-- Estadísticas Generales -->
    <div class="section estadisticas">
        <h2>Estadísticas Generales</h2>
        
        <div class="stats-grid">
            <div class="stat-row">
                <div class="stat-item">
                    <h3>Total Productores</h3>
                    <div class="value">{{ $total_productores ?? 0 }}</div>
                </div>
                <div class="stat-item">
                    <h3>Total Cultivos</h3>
                    <div class="value">{{ $total_cultivos ?? 0 }}</div>
                </div>
                <div class="stat-item">
                    <h3>Producción Total</h3>
                    <div class="value">{{ number_format($total_produccion ?? 0, 0) }} kg</div>
                </div>
            </div>
        </div>

        <div class="resumen-box">
            <strong>Ventas Totales:</strong> ${{ number_format($total_ventas ?? 0, 2) }}
        </div>
    </div>

    <!-- Tabla de Productores -->
    @if(isset($productores) && $productores->count() > 0)
    <div class="section">
        <h2>Resumen de Productores</h2>
        <table>
            <thead>
                <tr>
                    <th>Productor</th>
                    <th>Finca</th>
                    <th>Cultivos</th>
                    <th>Promedio IDC</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productores as $productor)
                <tr>
                    <td>{{ $productor['nombre'] }}</td>
                    <td>{{ $productor['finca'] }}</td>
                    <td>{{ $productor['cultivos'] }}</td>
                    <td>{{ number_format($productor['promedio_idc'], 1) }}</td>
                    <td>
                        <span class="badge {{ $productor['estado'] === 'Bueno' ? 'badge-success' : 'badge-warning' }}">
                            {{ $productor['estado'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Rendimiento por Cultivo -->
    @if(isset($rendimientoPorCultivo) && $rendimientoPorCultivo->count() > 0)
    <div class="section">
        <h2>Rendimiento por Tipo de Cultivo</h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Cultivo</th>
                    <th>Promedio IDC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rendimientoPorCultivo as $cultivo)
                <tr>
                    <td>{{ $cultivo->nombre }}</td>
                    <td>{{ number_format($cultivo->promedio ?? 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>AgroTrack EC - Sistema de Gestión Agrícola</p>
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</p>
    </div>
</body>
</html>