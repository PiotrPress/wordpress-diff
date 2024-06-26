FROM php:8.3-cli

RUN apt-get update
RUN apt-get install -y git
RUN apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/src/app

CMD ["sleep", "infinity"]