<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>PHP Web Sockets With Ratchet</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('css/style.css')}}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

        <div class="chat_window">
            <div class="top_menu">
                <div class="buttons">
                    <div class="button close">

                    </div>
                    <div class="button minimize">

                    </div>
                    <div class="button maximize">

                    </div>
                </div>
                <div class="title">Solicitud</div>
            </div>
            <ul class="messages">

            </ul>
        </div>
        <div class="message_template ">
            <li class="message">
                <div class="appeared">
                    <div class="avatar">

                    </div>
                    <div class="text_wrapper">
                        <div class="text">
                                <input type="hidden" id="solicitud" value="{{$solicitud[0]->id}}">
                                <p>Estado de tu solicitud: {{($solicitud[0]->estado == 0) ? 'Rechazada' : 'Aceptada'}}</p>
                        </div>
                    </div>
                </div>
            </li>
        </div>
        <div hidden class="bottom_wrapper clearfix">
                <div class="message_input_wrapper">
                    <input class="message_input" placeholder="Type your message here..." />
                </div>
                <div class="send_message">
                    <div class="icon">

                    </div>
                    <div class="text">Send</div>
                </div>
            </div>

        <script>
            (() => {
                const conn = new WebSocket('ws://localhost:8080'); // Dirección de tu servidor WebSocket

                conn.onopen = () => {
                    console.log('WebSocket conectado');
                    conn.send('Apareció un cliente del otro lado.');
                };
            
                class Message {
                    constructor(arg) {
                        this.text = arg.text;
                        this.messageSide = arg.messageSide;
                    }
                
                    draw() {
                        const messageTemplate = document.querySelector('.message_template').innerHTML.trim();
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = messageTemplate;
                        const messageElement = tempDiv.firstChild;
                    
                        messageElement.classList.add(this.messageSide);
                        messageElement.querySelector('.text').textContent = this.text;
                    
                        const messagesContainer = document.querySelector('.messages');
                        messagesContainer.appendChild(messageElement);
                    
                        setTimeout(() => {
                            messageElement.classList.add('appeared');
                        }, 0);
                    }
                }
            
                const sendMessage = (text, messageSide = 'left') => {
                    if (!text) return;
                
                    const messageInput = document.querySelector('.message_input');
                    messageInput.value = '';
                
                    const message = new Message({ text, messageSide });
                    message.draw();
                
                    const messagesContainer = document.querySelector('.messages');
                    messagesContainer.scrollTo({
                        top: messagesContainer.scrollHeight,
                        behavior: 'smooth',
                    });
                };
            
                const handleSendMessage = () => {
                    const messageInput = document.querySelector('.message_input');
                    const text = messageInput.value.trim();
                    if (text) {
                        conn.send(text);
                        sendMessage(text, 'left'); // Muestra el mensaje enviado por el cliente
                    }
                };
            
                document.querySelector('.send_message').addEventListener('click', handleSendMessage);
            
                document.querySelector('.message_input').addEventListener('keyup', (e) => {
                    if (e.key === 'Enter') {
                        handleSendMessage();
                    }
                });
            
                conn.onmessage = (event) => {
                    const inputId = document.getElementById('solicitud');
                    const tex = inputId.value;
                
                    const message = JSON.parse(event.data);
                    conn.onmessage = function(event) {
                        try {
                            const message = JSON.parse(event.data); // Asegúrate de que esto sea un JSON válido
                        
                            if (message.record_id == undefined) {
                                console.warn('El mensaje recibido no contiene la propiedad record_id');
                                return;
                            }
                        
                            if (message.record_id != undefined && message.record_id == tex) {
                                let responseText = '';
                            
                                if (message.action === 'ACEP') {
                                    console.log('El registro fue aceptado');
                                    responseText = 'Tu pedido fue aceptado.';
                                } else if (message.action === 'RECH') {
                                    console.log('El registro fue rechazado');
                                    responseText = 'Tu pedido fue rechazado.';
                                }
                            
                                if (responseText) {
                                    sendMessage(responseText, 'right'); // Muestra el mensaje recibido del servidor
                                }
                            }
                        } catch (error) {
                            console.error('Error al procesar el mensaje:', error);
                        }
                    };
                };
            })();


        </script> 
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>














<!-- const conn = new WebSocket('ws://localhost:8080');
conn.onopen = () => {
    console.log("Connection established!");
};

(() => {
    class Message {
        constructor(arg) {
            this.text = arg.text;
            this.messageSide = arg.messageSide;
        }

        draw() {
            const messageTemplate = document.querySelector('.message_template').innerHTML.trim();
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = messageTemplate;
            const messageElement = tempDiv.firstChild;

            messageElement.classList.add(this.messageSide);
            messageElement.querySelector('.text').innerHTML = this.text;

            const messagesContainer = document.querySelector('.messages');
            messagesContainer.appendChild(messageElement);

            setTimeout(() => {
                messageElement.classList.add('appeared');
            }, 0);
        }
    }

    const getMessageText = () => {
        const messageInput = document.querySelector('.message_input');
        const messageText = messageInput.value.trim();
        if (messageText) {
            conn.send(messageText);
        }
        return messageText;
    };

    const sendMessage = (text, messageSide = 'left') => {
        if (!text) return;

        const messageInput = document.querySelector('.message_input');
        messageInput.value = '';

        const message = new Message({ text, messageSide });
        message.draw();

        const messagesContainer = document.querySelector('.messages');
        messagesContainer.scrollTo({
            top: messagesContainer.scrollHeight,
            behavior: 'smooth',
        });
    };

    document.querySelector('.send_message').addEventListener('click', () => {
        sendMessage(getMessageText());
    });

    document.querySelector('.message_input').addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            sendMessage(getMessageText());
        }
    });

    conn.onmessage = (e) => {
        console.log(e.data);
        sendMessage(e.data, 'right');
    };
})(); -->