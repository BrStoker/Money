require('dotenv').config()
const express = require('express')
const app = express()
const server = require('http').createServer(app)
const port = 3000


const io = require('socket.io')(server, {
    cors: { origin: process.env.APP_URL }
});

app.get('/broadcast', async(req, res) => {

    let returnResp
    let params = req.query

    if(params.channel && params.message) {
        let socket = app.get('WebSocket')
        socket.broadcast.emit(params.channel, params.message)
        returnResp = {'status': true, 'message': 'Broadcast success'}
    } else {
        returnResp = {'status': false, 'message': 'Invalid Request'}
    }

    return res.json(returnResp).status(200)
});

io.on('connection', (socket) => {

    app.set('WebSocket', socket)

    socket.on('sendNotificationToUser', (obj) => {
        socket.broadcast.emit('receiveNotificationToUser_'+obj.user, obj.message)
    })
})

server.listen(port, () => {
    console.log('Server is running. Port: '+port)
});

