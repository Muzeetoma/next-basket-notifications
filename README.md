<h1>Usage Instructions</h1>

    docker-compose up
    docker exec -it next-basket-notifications-app-1 /bin/bash 
    php artisan rabbitmq:listen
    Goto: storage/logs/laravel.log to check for logged info
