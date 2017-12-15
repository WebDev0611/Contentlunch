# ContentLaunch APP

## Development Prerequisites

1. Install docker. (https://www.docker.com/get-docker)

2. Install docker-compose (https://docs.docker.com/compose/install/)

3. Install nodejs/npm (https://nodejs.org/en/download/)

4. Install bower `npm install -g bower`

5. Install gulp `npm isntall -g bower`

## Local Environment Setup

1. Make a copy of the `.env-local` file and name it `.env`.

2. From a fresh clone of the project, run `docker-compose build`. This will take a while depending on the speed of your development machine.

3. Now run `docker-compose up -d` to start the newly created containers in a detached state.

4. Next we'll gather php dependencies and setup the db tables for the app. We'll to that by running the following:
    ```
    docker-compose run contentlaunch composer install
    docker-compose run contentlaunch php artisan migrate
    docker-compose run contentlaunch php artisan db:seed
    ```
 
5. On to downloading dependencies and building the front end:
    ```
    bower install
    npm install
    gulp
    ```

6. You should now be able to access the app from http://localhost:3000. 

7. To learn more about how docker-compose works, check out this link https://docs.docker.com/compose/.