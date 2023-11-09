<!DOCTYPE html>
<html>
<head>
    <title>Socket.io</title>
    <script src="/socket.io/socket.io.js"></script>
    <script>
        const socket = io('http://localhost:3000');

        socket.on('connect', () => {
            console.log('Connected to Socket.io server');
        });

        socket.on('disconnect', () => {
            console.log('Disconnected from Socket.io server');
        });
    </script>
</head>
<body>
<h1>Socket.io Example</h1>
</body>
</html>
