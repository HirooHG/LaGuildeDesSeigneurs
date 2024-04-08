FROM webdevops/php-apache-dev:8.2

WORKDIR /app

COPY --from=composer /usr/bin/composer /usb/bin/composer

CMD composer create-project symfony/skeleton:"7.0.*" LaGuildeDesSeigneurs;
