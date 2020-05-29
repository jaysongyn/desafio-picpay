FROM picpay/php:7.2-fpm-base

COPY ./                             /app
COPY ./support/docker/config/       /

RUN composer install \
    && composer dump-autoload -a \
    # && php artisan optimize \
    && chown -R www-data:www-data /var/tmp/nginx \
    && chown -R www-data:www-data /app/storage \
    && chmod +x /start.sh

ENV DOCKERIZE_VERSION v0.6.1

# entrypoint
ENTRYPOINT ["sh", "-c"]

EXPOSE 80

# start
CMD ["/start.sh"]
