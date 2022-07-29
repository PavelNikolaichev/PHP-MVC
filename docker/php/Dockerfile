FROM php:8.0-apache

# Setup Apache2 config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

RUN apt-get update && apt-get install -y curl git zip && rm -rf /var/lib/apt/lists/*

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql


#Install Cron
RUN apt-get update
RUN apt-get -y install cron

#RUN crontab -l | { cat; echo "* * * * * bash /root/get_date.sh"; } | crontab -

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Run the command on container startup
CMD cron

EXPOSE 80