FROM php:8.0-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY ./*mathmpr.com.zip  /home/$user/mathmpr.com.zip

RUN mkdir -p /home/$user/.cache/composer/
COPY ./*composer_cache.zip  /home/$user/.cache/composer/composer_cache.zip

RUN rm /tmp/post_copy.sh
RUN echo "chown -R \$1:\$1 /var/www" >> /tmp/post_copy.sh
RUN echo "[ -f /home/\$1/mathmpr.com.zip && ! -f /tmp/unzip.lock ] && echo \"lock\" >> /tmp/unzip.lock && unzip /home/\$1/mathmpr.com.zip -d /var/www && chown -R \$1:\$1 /var/www && rm /home/\$1/mathmpr.com.zip && rm /tmp/unzip.lock || echo \"zipped project not found or process is locked. skipped...\"" >> /tmp/post_copy.sh
RUN echo "[ -f /home/\$1/.cache/composer/composer_cache.zip && ! -f /tmp/unzip.lock ] && echo \"lock\" >> /tmp/unzip.lock && unzip /home/\$1/.cache/composer/composer_cache.zip -d /home/\$1/.cache/composer/ && rm /home/\$1/.cache/composer/composer_cache.zip && rm /tmp/unzip.lock || echo \"zipped composer cache not found or process is locked. skipped...\"" >> /tmp/post_copy.sh

# Set working directory
WORKDIR /var/www

USER $user
