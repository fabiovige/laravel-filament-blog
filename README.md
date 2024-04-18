## ACL + Laravel 11 + Filament 3

Sistema de posts com controle de usuários, papéis e permissões

docker compose up -d

Dentro do container execute:

docker exec -it laravel-filament-blog-laravel.test-1 bash
composer install

./vendor/bin/sail up -d

alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'

php artisan key:generate
php artisan migrate:refresh --seed

By [fabiovige.dev](https://fabiovige.dev).
