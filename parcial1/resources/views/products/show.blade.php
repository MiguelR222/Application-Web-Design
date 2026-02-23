<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .no-products {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
        .link a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
        }
        .link a:hover {
            background-color: #45a049;
        }
        .precio {
            font-weight: bold;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Productos</h1>
        
        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if($productos->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Fecha de Creación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto->idproductos }}</td>
                            <td>{{ $producto->nombre ?? 'Sin nombre' }}</td>
                            <td class="precio">${{ number_format($producto->precio ?? 0, 2) }}</td>
                            <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-products">
                <p>No hay productos registrados.</p>
            </div>
        @endif

        <div class="link">
            <a href="{{ route('products.index') }}">Vuelta al formulario</a>
        </div>
    </div>
</body>
</html>
