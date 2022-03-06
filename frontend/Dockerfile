FROM ubuntu/apache2:latest

RUN apt-get update -y && apt-get upgrade -y && apt-get install -y nodejs && apt-get install -y npm

RUN chmod 777 /var/www/html

COPY package.json /var/www/html
COPY ./example.env.production /var/www/html/.env.production

WORKDIR /var/www/html

RUN npm i

COPY ./. /var/www/html

RUN npm run build

COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite
RUN a2enmod headers

EXPOSE 80