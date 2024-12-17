<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card">
            <div class="card-header">
                Esto es un tarjetoide con info del registro
                <a href="{{route('tabla')}}"><button class="btn btn-primary">Volver</button></a>
            </div>
            <div id="modifiable">
                <div class="card-body">
                    <input type="hidden" value="{{$r->id}}" id="id">
                    <p>id: <strong>{{$r->id}}</strong></p>
                    <p>Estado: <strong>{{$r->estado == 1 ? 'Aceptado' : 'Rechazado'}}</strong></p>

                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary" onclick="aceptar(<?php echo $r->id; ?>)">Aceptar</button>
                    <button class="btn btn-danger" onclick="rechazar(<?php echo $r->id; ?>)">Rechazar</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>


function actualizarVista(id) {
    fetch(`/actualizar/${id}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            
            // Crear elementos del DOM dinámicamente
            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body');

            // Input hidden
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.value = data.r.id; // Asignar el ID desde la respuesta
            hiddenInput.id = 'id';

            // Parrafo para ID
            const idParagraph = document.createElement('p');
            idParagraph.innerHTML = `id: <strong>${data.r.id}</strong>`;

            // Parrafo para Estado
            const estadoParagraph = document.createElement('p');
            const estadoTexto = data.r.estado == 1 ? 'Aceptado' : 'Rechazado';
            estadoParagraph.innerHTML = `Estado: <strong>${estadoTexto}</strong>`;

            // Limpiar el contenedor y agregar los nuevos elementos
            const container = document.getElementById('modifiable'); 
            container.innerHTML = ''; // Limpiar el contenido existente
            container.appendChild(cardBody);
            cardBody.appendChild(hiddenInput);
            cardBody.appendChild(idParagraph);
            cardBody.appendChild(estadoParagraph);
        })
        .catch(error => {
            console.error('Error al actualizar la vista:', error);
        });
}




const conn = new WebSocket('ws://localhost:8080');

conn.onopen = () => {
    console.log('WebSocket conectado');
    conn.send('Abrir Solicitud');
};

conn.onerror = (error) => {
    console.error('Error en WebSocket:', error);
};

conn.onmessage = (event) => {
    console.log('Mensaje recibido:', event.data);
};

conn.onclose = () => {
    console.log('WebSocket desconectado');
};

function sendActionToServer(id, action) {
    const message = {
        record_id: id,
        action: action, // 'aceptar' o 'rechazar'
    };

    // Enviar el mensaje al servidor WebSocket
    conn.send(JSON.stringify(message));
}

function rechazar(id){
    let ack = 'ERR';
    fetch(`/rechazar/${id}`).then(response => response.json()).then(data => {
        //console.log(data);
        if(data.res === 'ACK'){
                sendActionToServer(id, 'RECH');
                let identificador = document.getElementById('id');
                if(identificador){
                    actualizarVista(identificador.value);
                }
        }
    }
    );
}



function aceptar(id){
    let ack = 'ERR';
    fetch(`/aceptar/${id}`).then(response => response.json()).then(data => {
        //console.log(data);
        if(data.res === 'ACK'){
                sendActionToServer(id, 'ACEP');
                let identificador = document.getElementById('id');
                if(identificador){
                    actualizarVista(identificador.value);
                }
        }
    }
    );
}
</script>
</html>