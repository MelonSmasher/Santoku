#!/usr/bin/env node

let confFilePath = 'socket_config/conf.yaml';
let config = null;
let fs = require('fs');
let yaml = require('js-yaml');
let server = require('http').Server();
let io = require('socket.io')(server);
let Redis = require('ioredis');
let redis = new Redis();


// Load the configuration from config file, or load default hard coded config
if (fs.existsSync(confFilePath)) {
    // Load the configuration from the config file
    config = yaml.safeLoad(fs.readFileSync(confFilePath, 'utf8'));
    console.info('Loaded configuration file from : ' + confFilePath);
} else {
    // Load the default config
    config = {
        'server': {
            'listen_address': '127.0.0.1',
            'listen_port': 3000,
            'bootstrap_user': 'vagrant'
        },
        'redis': {
            'host': '127.0.0.1',
            'port': 6379,
            'db': 0,
            'use_socket': false,
            'socket_path': null,
            'password': null
        }
    };
    // Warning about using the default config
    console.warn('Unable to find configuration file(' + confFilePath + ')... using default settings.');
}

if (config.redis.use_socket) {
    // Create a redis object that we will use to read incoming events
    redis = new Redis(config.redis.socket_path);
    // Create a redis object that allows us to store local objects
    localRedis = new Redis(config.redis.socket_path);
    // We are connected to redis socket
    console.info('Connected to Redis server on socket: ' + config.redis.socket_path)
} else {
    // Create a redis object that we will use to read incoming events
    redis = new Redis({
        port: config.redis.pass,
        host: config.redis.host,
        family: 4,
        password: config.redis.password,
        db: config.redis.db
    });
    // Create a redis object that allows us to store local objects
    localRedis = new Redis({
        port: config.redis.pass,
        host: config.redis.host,
        family: 4,
        password: config.redis.password,
        db: config.redis.db
    });
    // We are connected to redis server
    console.info('Connected to Redis server: ' + config.redis.host + ':' + config.redis.port)
}

redis.subscribe('sessions');

redis.on('message', function (channel, message) {
    message = JSON.parse(message);
    if (message.event === 'provision') {

        let nsp = io.of('/' + message.token);
        nsp.on('connection', function (socket) {
            console.log(message.token + '# ' + socket.client.id + ' joined the session');
            nsp.emit('terminal', 'Starting...');
        });

        let shim = '/tmp/' + message.token + '.sh';
        let shimData = "#! /bin/bash\nsudo -u " + config.server.bootstrap_user + " -H " + message.data.knife_fill_cmd;

        fs.writeFile(shim, shimData, function (err) {
            if (err) {
                return console.log(err);
            }
            console.log("The file was saved!");
        });

        let spawn = require('child_process').spawn;
        let child = spawn('/bin/bash', [shim], {shell: '/bin/bash'});

        child.stdout.on('data', (data) => {
            nsp.emit('terminal', `${data}`);
        });
        child.stderr.on('data', function (data) {
            nsp.emit('terminalErr', `${data}`);
            console.log('CMD received data from STDERR: ' + `${data}`);
        });
        child.on('exit', function (code) {
            nsp.emit('terminalDone', true);
            fs.unlinkSync(shim);
        });
    }
});

io.on('connection', function (socket) {
    //console.log('New connection with socket: ' + socket.id);
    socket.on('disconnect', function () {
        console.log('Socket (' + socket.id + ') closed')
    });
});

server.listen(config.server.listen_port, config.server.listen_address);
