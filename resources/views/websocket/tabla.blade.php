<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla probando un websocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container d-flex justify-content-center mt-5">
        <table class="table table-striped">
            <thead>
                <th>id</th>
                <th>estado</th>
                <th>DNI</th>
                <th>accion</th>
            </thead>
            <tbody>
                @forelse($registros as $r)
                    <tr>
                        <td>{{$r->id}}</td>
                        <td>{{$r->estado == 0 ? 'Rechazada' : 'Aceptada'}}</td>
                        <td>{{!empty($r->dni) ? $r->dni : 'No encontrado'}}</td>
                        <td>
                            <div class="d-flex justify-content-start align-items-center">
                                <a href="{{route('ver',$r->id)}}"><button class="btn btn-warning">Ver Y abrir WebSocket</button></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Sin registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>