const tmi = require('tmi.js');
const MySql = require('sync-mysql');

require('dotenv').config({path: __dirname + '/../.env'})

const args = process.argv.slice(2);
// const botProcess = args[0] ? args[0] : '';
const botProcess = args[1] ? args[1] : '';

const botId = botProcess.replace("muetzes_echo_", "");

if (!botProcess) {
    console.log("No Bot process selected. Run for example ´npm start muetzes_echo_279904718´");
    return;
}

if (botId <1) {
    console.log("Invalid process started. Run for example ´npm start muetzes_echo_279904718´");
    return;
}

let connection = new MySql({
    host : process.env.DB_HOST,
    user : process.env.DB_USERNAME,
    password : process.env.DB_PASSWORD,
    database : process.env.DB_DATABASE
});

let getBot = connection.query('SELECT * FROM bots WHERE id = ? LIMIT 1', [botId]);
let bot = getBot[0];
let botDisplayName = bot['name'];
let botName = botDisplayName.toLowerCase();
let getChannels = bot['channels'].replace(/[^a-zA-Z0-9_,]/g, "");
let channels = getChannels.split(",");
let prefix = bot['prefix'].toLowerCase();

let opts = {
    identity: {
        username: botName,
        password: "oauth:" + bot['token']
    },
    channels: channels
};

const client = new tmi.client(opts);
client.on('connected', onConnectedHandler);
client.on('message', onMessageHandler);

function onMessageHandler(target, context, msg, self) {
    if (self) {
        return;
    }

    const message = msg.replace(/\s+/g, ' ').trim();
    const lowerMsg = message.toLowerCase();
    if (message.startsWith(prefix)) {
        let cmdLength = prefix.length;
        let splitMessage = lowerMsg.substr(cmdLength).split(" ");
        let splitMessage2 = message.substr(cmdLength).split(" ");
        let command = splitMessage[0] === '' && splitMessage[1] !=='' ? splitMessage[1].toLowerCase() : splitMessage[0].toLowerCase();
        let arg1 = splitMessage[0] === '' && splitMessage[1] !=='' ? splitMessage2[2] : splitMessage2[1];
        let arg2 = splitMessage[0] === '' && splitMessage[1] !=='' ? splitMessage2[3] : splitMessage2[2];
        let streamer = target.replace("#", "");
        if (command) {
            let getCommand = connection.query('SELECT * FROM commands WHERE bot_id = ? AND deleted_at IS NULL AND command = ? AND (channels="[]" OR channels LIKE "%' + streamer + '%") LIMIT 1', [bot['id'], command])
            let cmd = getCommand[0];
            if (cmd) {
                if (cmd['is_protected']===0 || context['user-id']==='279904718') {
                    let output = cmd['content'].replace(/{name}/g, context['display-name']).replace(/{arg1}/g, arg1).replace(/{arg2}/g, arg2);
                    client.say(target, output.replace(/\s+/g, ' ').trim());
                }
            }
        }
    }
}

client.connect();

function onConnectedHandler(addr, port) {
    console.log(`* Connected to ${addr}:${port}`);
}