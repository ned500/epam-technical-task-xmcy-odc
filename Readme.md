# Test task for EPAM xmcy-odc project

## Install
Download the source code and build the containers
```shell
git clone https://github.com/ned500/epam-technical-task-xmcy-odc.git
cd epam-technical-task-xmcy-odc
docker-compose up -d
```

Login to the `project_php` docker container and run the commands
```shell
composer install
cp -i .env.local.dist .env.local
```
Now you can edit `.env.local` file, and you should change the values of `APP_SECRET`
and `HISTORY_API_KEY` variables.

## Usage
After installation procedure you can reach the [application](http://localhost:8080) and the [dummy email client](http://localhost:8081/) via a browser.

Enjoy! :-)
