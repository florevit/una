# ============================
# Stage 1: Builder
# ============================
FROM php:8.3-fpm AS builder

# Install build dependencies
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libmagickwand-dev \
        libzip-dev \
        unzip \
        pkg-config \        
        autoconf \
        build-essential \
    && rm -rf /var/lib/apt/lists/*

# Configure & install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli zip exif opcache iconv mbstring \
    && pecl install imagick-3.8.0 igbinary \
    && yes '' | pecl install redis \
    && docker-php-ext-enable imagick igbinary redis
    
# ============================
# Stage 2: Production runtime
# ============================
FROM php:8.3-fpm

# Install only runtime packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    msmtp-mta \
    tini \
    cron \
    && rm -rf /var/lib/apt/lists/*

# Copy compiled PHP extensions and config from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

RUN echo "* * * * * /usr/local/bin/php -c /usr/local/etc/php /opt/una/periodic/cron.php 2>&1 | sed -e \"s/\(.*\)/[\`date\`] \1/\" >>/var/log/unacron.log" > /tmp/crontab && crontab /tmp/crontab && rm -f /tmp/crontab

ENTRYPOINT ["/usr/bin/tini", "--"]
CMD ["cron", "-f"]