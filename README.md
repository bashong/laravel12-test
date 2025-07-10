# Music Player

Simple music player app built with Laravel, Vue, Tailwind.

## Features
- Register/login
- List songs
- Play/pause, next, previous
- Random play avoiding immediate repeat
- Plays each song once before looping
- Avoids same artist consecutively if possible

## Setup
Unzip the source code 
cd music-player
composer install
npm install && npm run build
php artisan migrate
php artisan module:migrate MusicPlayer
php artisan module:seed MusicPlayer
php artisan test --testsuite=Modules
