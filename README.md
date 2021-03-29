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

### Database
* Migrations: /website/database/migrations
* Models: /website/app/Models

### Setup
* Copy .env.example to .env and config
* Change the website/database/seeders/UserSeeder.php and seed Your account or insert it manually in the database
* You can get Your Twitch id on https://hallo.tools/token
* See and use the Laravel commands

### Screenshots
> Users index
> ![Users index](https://raw.githubusercontent.com/Muetze42/media-storage/master/muetzes-echo/screenshots/users-index.png)
---
> Users detail
> ![Users detail](https://raw.githubusercontent.com/Muetze42/media-storage/master/muetzes-echo/screenshots/users-detail.png)
---
> Bots index
> ![Bots index](https://raw.githubusercontent.com/Muetze42/media-storage/master/muetzes-echo/screenshots/bots-index.png)
---
> Bots edit
> ![Bots detail](https://raw.githubusercontent.com/Muetze42/media-storage/master/muetzes-echo/screenshots/bots-edit.png)
---
> Commands index
> ![Commands index](https://raw.githubusercontent.com/Muetze42/media-storage/master/muetzes-echo/screenshots/commands-index.png)
---
> Commands edit
> ![Commands detail](https://raw.githubusercontent.com/Muetze42/media-storage/master/muetzes-echo/screenshots/commands-edit.png)