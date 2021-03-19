# Muetzes echo
In this repository is a small Twitch bot including a website for administration.  
The app can be used in multiple channels and multiple bots, and the commands can optionally be limited to desired channels.

## Very simple and small Twitch chatbot
* No token encryption
* No deep functions
* No support

### Requirements
* PHP >7.4
* Node.js
* Registered [Twitch app](https://dev.twitch.tv/docs/authentication/#registration)
* A [Laravel Nova](https://nova.laravel.com/) licence if you want to use the administration

### Structure
* /client: Node.js script (bot client)
* /website: Laravel framework (website)

### Setup
* Copy .env.example to .env and config
* Optional: Change the „µ“ for the commands in the Node.js script
* Change the website/database/seeders/UserSeeder.php and seed Your account or insert it manually in the database
* You can get Your Twitch id on https://hallo.tools/token
* See and use the Laravel commands

### Todo
* Rights for commands