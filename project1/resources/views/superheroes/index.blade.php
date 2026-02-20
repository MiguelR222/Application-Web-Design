<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <title>Base de Datos Superhéroes</title> 
</head> 
<body class="container mt-5"> 
    <h2 class="mb-4">Lista de Personajes</h2> 
    <table class="table table-striped table-bordered"> 
        <thead class="table-dark"> 
            <tr> 
                <th>Nombre</th> 
                <th>Nombre Real</th> 
                <th>Género</th> 
                <th>Universo (Compañía)</th> 
            </tr> 
        </thead> 
        <tbody> 
            @foreach($characters as $character) 
            <tr> 
                <td>{{ $character->name }}</td> 
                <td>{{ $character->real_name }}</td> 
                <td>{{ $character->gender }}</td> 
                <td> 
                    {{ $character->universe->universe }}  
                    ({{ $character->universe->company }}) 
                </td> 
            </tr> 
            @endforeach 
        </tbody> 
    </table> 
</body> 
</html> 
