#!/bin/sh

# Otimiza a aplicação para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Inicia o processo do PHP-FPM em primeiro plano
exec php-fpm