FROM webdevops/php-apache-dev:8.2

WORKDIR /app

COPY --from=composer /usr/bin/composer /usb/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

EXPOSE 8000

CMD symfony serve
