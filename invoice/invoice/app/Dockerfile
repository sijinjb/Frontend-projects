FROM php:8.2-fpm-alpine

RUN apk --update --no-cache add \ 
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    $PHPIZE_DEPS \
    git

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN pecl install redis && docker-php-ext-enable redis

# Clean up unnecessary packages
RUN apk del $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/*

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy existing application directory contents
COPY . /app

RUN composer install

###############################################################################
# For file read and write outside the container.                              #
# Otherwise user will be root and cannot edit file without `sudo`             #
###############################################################################
# Create a non-root user
RUN addgroup -g 1000 -S invoicer \
    && adduser -u 1000 -S invoicer -G invoicer

# Switch to the non-root user
USER invoicer

EXPOSE 9000 8000
CMD ["php-fpm"]