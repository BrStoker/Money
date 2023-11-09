import Echo from 'laravel-echo';
import SocketIOClient from 'socket.io-client';

window.io = SocketIOClient;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':3000', // Замените на ваш хост и порт Socket.io
});

window.Echo.channel('chat')
    .listen('NewMessage', (data) => {
        console.log('New message:', data);
        // Обработка нового сообщения и обновление интерфейса
    });
