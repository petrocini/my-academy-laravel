FROM php:8.3-fpm

# Instala dependências básicas e extensões do PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia os arquivos do composer e instala as dependências de produção
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copia o resto do código da aplicação
COPY . .

# Copia o script de entrypoint e o torna executável
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Define o dono dos arquivos para o usuário do servidor web
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expõe a porta do PHP-FPM
EXPOSE 9000

# Define o script de entrypoint como o comando de início do container
ENTRYPOINT ["entrypoint.sh"]