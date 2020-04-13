FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    ca-certificates \
    build-essential \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    jpegoptim optipng pngquant gifsicle \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl \
    acl \
    sudo \
    vim \
    cron

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Install extensions
RUN docker-php-ext-install pdo_sqlite mbstring zip exif pcntl
RUN docker-php-ext-configure gd
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-enable pdo_sqlite

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Change owner of existing application directory contents
RUN sudo chown -R www:www /var/www

# install from nodesource using apt-get
# https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-an-ubuntu-14-04-server
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && \
    apt-get install -yq nodejs

RUN apt-get install -yq gcc g++ make

# fix npm - not the latest version installed by apt-get
RUN npm install -g npm

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
