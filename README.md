# Game Catalog App

This is an API system for a game catalog. The game has players who play it very frequently. The games come in versions so players can own a game in one or more versions of the game.

The system should only store one game play record per player per game per day even if the player played the game(s) multiple times in a day provided they played it alone. If the player played with other people, the system records who started the game and those invited to join.

> Assumption 1: Each of game only allows a maximum of 4 players.
> Assumption 2: Players can only play together if they have the same game versions.

## Testing Apis on Heroku

- Host : https://gbemileke-game-catalog.herokuapp.com
- Post man collection: https://www.getpostman.com/collections/3ffd2fd24b00d0087a99

    [![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/3ffd2fd24b00d0087a99)

## Local Setup Guide(Ducker)

1) Install and set up doker on your machine 
[Getting started with docker](https://docs.docker.com/compose/gettingstarted/)

2) Ensure docker is up and running on your machine

3) Clone the project
    ```bash
    git clone https://github.com/icelake0/GameCatalogApp.git
    ```
4) Change directory to the project root

5) Install composer dependencies

    ```bash
    composer install
    ```

6) Build app image
    ```bash
    docker-compose build app
    ```

7) start the app
    ```bash
    docker-compose up -d
    ```

8) run db migration
    ```bash
    docker-compose exec app php artisan migrate
    ```

9) Set up and start workers

    For this stages use `password` as `password` whenever you are prompted to enter `password`

    - start supervisor
    ```bash
    docker-compose exec app sudo supervisord
    ```
    - read the new config
    ```bash
    docker-compose exec app sudo supervisorctl reread
    ```
    - activate configuration
    ```bash
    docker-compose exec app sudo supervisorctl update 
    ```
    - start workers
    ```bash
    docker-compose exec app sudo supervisorctl start
    ```
10) Run the database seeder script
    ```bash
    docker-compose exec app php artisan setup-script:seed-game-plays
    ```
## Request running stats in response header
- X-Start-Time : Request start time
- X-End-Time: Request end time
- X-Runtime: Request total time teken in Sec
- X-Memory-Used: Request memory usage in byte

## License
This was built with Laravel framework

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
